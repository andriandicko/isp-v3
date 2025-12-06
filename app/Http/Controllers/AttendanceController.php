<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\UserShift;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AttendanceController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // 1. CEK ROLE
        if ($user->hasRole('admin')) {
            return redirect()->route('attendance.recap');
        }

        // Ambil Timezone dari config
        $timezone = config('app.timezone', 'Asia/Jakarta');
        $today = Carbon::now()->setTimezone($timezone)->startOfDay();

        // 2. AMBIL SHIFT & KANTOR
        // (Function ini di User.php sudah kita perbaiki agar pakai timezone config juga)
        $allowedShifts = $user->getAllowedShiftsToday();
        $allowedShifts->load(['office', 'shift']);

        $userShift = $allowedShifts->first();

        // 3. Cek status absen hari ini
        $todayAttendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        // 4. Cek Izin/Cuti
        $leaveToday = $user->getLeaveTypeOnDate($today);

        // 5. Riwayat Absensi
        $attendances = Attendance::where('user_id', $user->id)
            ->whereYear('date', $today->year)
            ->whereMonth('date', $today->month)
            ->with(['shift'])
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('attendance.index', compact(
            'userShift', 
            'allowedShifts', 
            'todayAttendance', 
            'leaveToday', 
            'attendances'
        ));
    }

    public function checkIn(Request $request)
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            return response()->json(['success' => false, 'message' => 'Admin tidak perlu absensi!'], 403);
        }

        try {
            $request->validate([
                'latitude'  => 'required|numeric',
                'longitude' => 'required|numeric',
                'photo'     => 'required|image|max:4096',
            ]);

            // Setup Timezone
            $timezone = config('app.timezone', 'Asia/Jakarta');
            $now = Carbon::now()->setTimezone($timezone);
            
            $todayDate = $now->copy()->startOfDay();
            $yesterdayDate = $now->copy()->subDay()->startOfDay();
            
            // 1. AMBIL SHIFT (Cek Hari Ini & Kemarin)
            $shiftsToday = $user->getAllowedShiftsToday($todayDate);
            $shiftsYesterday = $user->getAllowedShiftsToday($yesterdayDate);

            if ($shiftsToday->isEmpty() && $shiftsYesterday->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'Tidak ada jadwal shift aktif!'], 400);
            }

            // 2. CARI LOKASI VALID & TENTUKAN TANGGAL
            $locationCheck = $this->findNearestValidOffice($shiftsToday, $request->latitude, $request->longitude);
            $selectedDate = $todayDate; 

            // Jika Hari Ini Gagal, Coba Shift Kemarin
            if (!$locationCheck['isValid'] && $shiftsYesterday->isNotEmpty()) {
                $locCheckYesterday = $this->findNearestValidOffice($shiftsYesterday, $request->latitude, $request->longitude);
                
                if ($locCheckYesterday['isValid']) {
                    $locationCheck = $locCheckYesterday;
                    $selectedDate = $yesterdayDate; 
                }
            }

            if (!$locationCheck['isValid']) {
                return response()->json([
                    'success' => false,
                    'message' => "Di luar jangkauan! Kantor: {$locationCheck['nearestName']} (" . round($locationCheck['minDistance']) . "m)"
                ], 400);
            }

            // 3. SECURITY CHECK
            $existingAttendance = Attendance::where('user_id', $user->id)
                ->whereDate('date', $selectedDate)
                ->first();

            if ($existingAttendance && $existingAttendance->check_in_time) {
                return response()->json(['success' => false, 'message' => 'Anda sudah check in untuk shift ini!'], 400);
            }

            $matchedShift = $locationCheck['matchedShift'];
            $photoPath = $request->file('photo')->store('attendance/check-in', 'public');

            // --- PERBAIKAN BUG PARSING DOUBLE DATE ---
            // Karena start_time dicasting datetime, kita harus format paksa ke H:i:s string
            // Agar tanggal bawaan dari model Shift TIDAK ikut tergabung
            $jamMulai = Carbon::parse($matchedShift->shift->start_time)->format('H:i:s');
            
            // Gabungkan Tanggal Terpilih + Jam Mulai String
            $shiftStartFull = Carbon::parse($selectedDate->format('Y-m-d') . ' ' . $jamMulai, $timezone);
            
            $status = $now->gt($shiftStartFull) ? 'late' : 'present';

            // Simpan Data
            $attendance = Attendance::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'date' => $selectedDate, 
                ],
                [
                    'shift_id'           => $matchedShift->shift_id,
                    'check_in_time'      => $now->format('H:i:s'),
                    'check_in_latitude'  => $request->latitude,
                    'check_in_longitude' => $request->longitude,
                    'check_in_photo'     => $photoPath,
                    'check_in_distance'  => round($locationCheck['minDistance'], 2),
                    'status'             => $status,
                ]
            );
            
            $tglAbsenStr = $selectedDate->locale('id')->isoFormat('dddd, D MMMM Y');

            return response()->json([
                'success' => true,
                'message' => "Check in berhasil ({$tglAbsenStr})! Status: " . ($status == 'late' ? 'Terlambat' : 'Tepat Waktu'),
                'data'    => $attendance
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function checkOut(Request $request)
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            return response()->json(['success' => false, 'message' => 'Admin tidak perlu absensi!'], 403);
        }

        try {
            $request->validate([
                'latitude'  => 'required|numeric',
                'longitude' => 'required|numeric',
                'photo'     => 'required|image|max:4096',
            ]);

            // Setup Timezone
            $timezone = config('app.timezone', 'Asia/Jakarta');
            $today = Carbon::now()->setTimezone($timezone)->startOfDay();

            $attendance = Attendance::where('user_id', $user->id)
                ->whereDate('date', $today)
                ->first();

            if (!$attendance || !$attendance->check_in_time) {
                return response()->json(['success' => false, 'message' => 'Anda belum check in hari ini!'], 400);
            }

            if ($attendance->check_out_time) {
                return response()->json(['success' => false, 'message' => 'Anda sudah check out hari ini!'], 400);
            }

            // --- VALIDASI LOKASI CHECKOUT ---
            $validUserShifts = UserShift::with('office')
                ->where('user_id', $user->id)
                ->where('shift_id', $attendance->shift_id)
                ->where('is_active', true)
                ->get();

            $locationCheck = $this->findNearestValidOffice($validUserShifts, $request->latitude, $request->longitude);

            if (!$locationCheck['isValid']) {
                return response()->json([
                    'success' => false,
                    'message' => "Terlalu jauh dari kantor! Terdekat: {$locationCheck['nearestName']} (" . round($locationCheck['minDistance']) . "m)"
                ], 400);
            }

            // --- PERHITUNGAN DURASI ---
            $photoPath = $request->file('photo')->store('attendance/check-out', 'public');
            
            // Waktu Sekarang (Check Out Realtime dengan Timezone Config)
            $checkOutFull = Carbon::now()->setTimezone($timezone);

            // Re-create Timestamp CheckIn dengan Timezone Config
            $attendanceDate = Carbon::parse($attendance->date)->format('Y-m-d');
            $checkInFull = Carbon::createFromFormat('Y-m-d H:i:s', $attendanceDate . ' ' . $attendance->check_in_time, $timezone);
            
            $diffInMinutes = $checkInFull->diffInMinutes($checkOutFull);
            
            $hours = floor($diffInMinutes / 60);
            $minutes = $diffInMinutes % 60;

            $attendance->update([
                'check_out_time'      => $checkOutFull->format('H:i:s'),
                'check_out_latitude'  => $request->latitude,
                'check_out_longitude' => $request->longitude,
                'check_out_photo'     => $photoPath,
                'check_out_distance'  => round($locationCheck['minDistance'], 2),
            ]);

            return response()->json([
                'success' => true,
                'message' => "Check out berhasil! Durasi: {$hours} jam {$minutes} menit",
                'data'    => $attendance
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function recap(Request $request)
    {
        $user = auth()->user();
        
        $timezone = config('app.timezone', 'Asia/Jakarta');
        $now = Carbon::now()->setTimezone($timezone);

        $month = (int) $request->get('month', $now->month);
        $year = (int) $request->get('year', $now->year);

        $query = Attendance::with(['user', 'shift'])
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->orderBy('date', 'desc');

        $users = [];

        if ($user->hasRole('admin')) {
            $users = User::whereDoesntHave('roles', function($q) {
                $q->where('name', 'admin');
            })->get();

            if ($request->has('user_id') && $request->user_id != null) {
                $query->where('user_id', $request->user_id);
            }
        } else {
            $query->where('user_id', $user->id);
        }

        $attendances = $query->paginate(20); 

        $statsQuery = clone $query;
        $statsQuery->getQuery()->limit = null;
        $statsQuery->getQuery()->offset = null;
        $allDataForStats = $statsQuery->get();

        $stats = [
            'total_days'    => $allDataForStats->count(),
            'present'       => $allDataForStats->where('status', 'present')->count(),
            'late'          => $allDataForStats->where('status', 'late')->count(),
            'leave'         => $allDataForStats->where('status', 'leave')->count(),
            'sick'          => $allDataForStats->where('status', 'sick')->count(),
            'business_trip' => $allDataForStats->where('status', 'business_trip')->count(),
            'absent'        => $allDataForStats->where('status', 'absent')->count(),
        ];

        return view('attendance.recap', compact('attendances', 'stats', 'month', 'year', 'users'));
    }

    // --- HELPER FUNCTIONS ---
    private function findNearestValidOffice($shifts, $lat, $lng)
    {
        $matchedShift = null;
        $minDistance = 99999999;
        $nearestName = '';
        $isValid = false;

        foreach ($shifts as $shift) {
            $office = $shift->office;
            $dist = $this->calculateHaversine($lat, $lng, $office->latitude, $office->longitude);

            if ($dist < $minDistance) {
                $minDistance = $dist;
                $nearestName = $office->name;
            }

            if ($dist <= $office->radius) {
                $matchedShift = $shift;
                $isValid = true;
                break;
            }
        }

        return [
            'isValid'      => $isValid,
            'matchedShift' => $matchedShift,
            'minDistance'  => $minDistance,
            'nearestName'  => $nearestName
        ];
    }

    private function calculateHaversine($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // meter

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}