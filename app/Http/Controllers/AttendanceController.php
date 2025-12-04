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

        // 1. CEK ROLE (Spatie)
        // Jika Admin, lempar ke halaman rekap. Admin tidak absen via menu ini.
        if ($user->hasRole('admin')) {
            return redirect()->route('attendance.recap');
        }

        $today = Carbon::today();

        // 2. AMBIL SHIFT & KANTOR (Multi-Office)
        // Menggunakan UserShift yang memiliki relasi ke Office
        $allowedShifts = $user->getAllowedShiftsToday();
        
        // Eager load 'office' dan 'shift' agar bisa diakses di Blade
        $allowedShifts->load(['office', 'shift']);

        // Default tampilan kartu atas
        $userShift = $allowedShifts->first();

        // 3. Cek status absen hari ini
        $todayAttendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        // 4. Cek Izin/Cuti
        $leaveToday = $user->getLeaveTypeOnDate($today);

        // 5. Riwayat Absensi Bulan Ini
        // FIX: Hapus 'shift.office' karena relasi office ada di UserShift, bukan Shift.
        $attendances = Attendance::where('user_id', $user->id)
            ->whereYear('date', $today->year)
            ->whereMonth('date', $today->month)
            ->with(['shift']) // Cukup load shift saja
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

        // PROTEKSI: Admin dilarang absen
        if ($user->hasRole('admin')) {
            return response()->json([
                'success' => false, 
                'message' => 'Admin tidak perlu melakukan absensi!'
            ], 403);
        }

        try {
            $request->validate([
                'latitude'  => 'required|numeric',
                'longitude' => 'required|numeric',
                'photo'     => 'required|image|max:4096', // Max 4MB
            ]);

            $today = Carbon::today();

            // Cek Double Checkin
            $existingAttendance = Attendance::where('user_id', $user->id)
                ->whereDate('date', $today)
                ->first();

            if ($existingAttendance && $existingAttendance->check_in_time) {
                return response()->json(['success' => false, 'message' => 'Anda sudah check in hari ini!'], 400);
            }

            // --- VALIDASI LOKASI MULTI-OFFICE ---
            $allowedShifts = $user->getAllowedShiftsToday();

            if ($allowedShifts->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'Tidak ada jadwal shift hari ini!'], 400);
            }

            // Cari kantor terdekat yang valid
            $locationCheck = $this->findNearestValidOffice($allowedShifts, $request->latitude, $request->longitude);

            if (!$locationCheck['isValid']) {
                return response()->json([
                    'success' => false,
                    'message' => "Anda berada di luar jangkauan! Kantor terdekat: {$locationCheck['nearestName']} (" . round($locationCheck['minDistance']) . "m)"
                ], 400);
            }

            $matchedShift = $locationCheck['matchedShift'];

            // Upload Foto
            $photoPath = $request->file('photo')->store('attendance/check-in', 'public');

            // Tentukan Status (Telat/Tepat)
            $checkInTime = Carbon::now('Asia/Jakarta');
            $shiftStartTime = Carbon::parse($matchedShift->shift->start_time, 'Asia/Jakarta');
            
            // Logika toleransi keterlambatan bisa ditambahkan di sini
            $status = $checkInTime->format('H:i:s') > $shiftStartTime->format('H:i:s') ? 'late' : 'present';

            // Simpan Data
            $attendance = Attendance::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'date' => $today,
                ],
                [
                    'shift_id'           => $matchedShift->shift_id, // Kunci ke shift yang valid lokasinya
                    'check_in_time'      => $checkInTime->format('H:i:s'),
                    'check_in_latitude'  => $request->latitude,
                    'check_in_longitude' => $request->longitude,
                    'check_in_photo'     => $photoPath,
                    'check_in_distance'  => round($locationCheck['minDistance'], 2),
                    'status'             => $status,
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Check in berhasil di ' . $matchedShift->office->name . '!',
                'data'    => $attendance
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function checkOut(Request $request)
    {
        $user = auth()->user();

        // PROTEKSI: Admin dilarang absen
        if ($user->hasRole('admin')) {
            return response()->json([
                'success' => false, 
                'message' => 'Admin tidak perlu melakukan absensi!'
            ], 403);
        }

        try {
            $request->validate([
                'latitude'  => 'required|numeric',
                'longitude' => 'required|numeric',
                'photo'     => 'required|image|max:4096',
            ]);

            $today = Carbon::today();

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
            // Cari UserShift yang sesuai dengan shift_id saat checkin
            $validUserShifts = UserShift::with('office')
                ->where('user_id', $user->id)
                ->where('shift_id', $attendance->shift_id)
                ->where('is_active', true)
                ->get();

            $locationCheck = $this->findNearestValidOffice($validUserShifts, $request->latitude, $request->longitude);

            if (!$locationCheck['isValid']) {
                return response()->json([
                    'success' => false,
                    'message' => "Anda terlalu jauh dari kantor! Terdekat: {$locationCheck['nearestName']} (" . round($locationCheck['minDistance']) . "m)"
                ], 400);
            }

            // --- PERHITUNGAN DURASI (FIX DURASI NEGATIF) ---
            $photoPath = $request->file('photo')->store('attendance/check-out', 'public');
            
            // Pakai Timezone Asia/Jakarta agar konsisten
            $now = Carbon::now('Asia/Jakarta');

            // Gabungkan Tanggal Absen + Jam Check In agar menjadi Timestamp yang utuh
            $attendanceDate = Carbon::parse($attendance->date)->format('Y-m-d');
            $checkInFull = Carbon::createFromFormat('Y-m-d H:i:s', $attendanceDate . ' ' . $attendance->check_in_time, 'Asia/Jakarta');
            
            // Waktu Check Out adalah sekarang
            $checkOutFull = $now;

            // Hitung selisih menit secara absolut
            $diffInMinutes = $checkInFull->diffInMinutes($checkOutFull);
            $hours = floor($diffInMinutes / 60);
            $minutes = $diffInMinutes % 60;

            $attendance->update([
                'check_out_time'      => $now->format('H:i:s'),
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
        
        $month = (int) $request->get('month', now()->month);
        $year = (int) $request->get('year', now()->year);

        // Query Dasar
        $query = Attendance::with(['user', 'shift'])
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->orderBy('date', 'desc');

        // --- FILTER BERDASARKAN ROLE ---
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

        // --- PERBAIKAN DISINI ---
        // Ganti ->get() menjadi ->paginate(20)
        // Angka 20 adalah jumlah data per halaman
        $attendances = $query->paginate(20); 

        // Karena sekarang pakai paginate(), kita tidak bisa pakai collection method standar
        // untuk menghitung stats secara langsung dari $attendances (karena isinya cuma 1 page).
        // Kita buat query terpisah untuk statistik agar angkanya tetap AKURAT (Total sebulan).
        
        // Clone query untuk statistik (agar tidak merusak query pagination)
        $statsQuery = clone $query;
        // Hapus limit/offset pagination dari clone (reset query ke 'ambil semua')
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

    // --- HELPER FUNCTION UNTUK MENGHITUNG JARAK ---
    private function findNearestValidOffice($shifts, $lat, $lng)
    {
        $matchedShift = null;
        $minDistance = 99999999;
        $nearestName = '';
        $isValid = false;

        foreach ($shifts as $shift) {
            $office = $shift->office;
            
            // Gunakan method di model Office jika ada, atau hitung manual di sini
            // Asumsi Model Office punya method calculateDistance($lat, $lng)
            // Jika belum ada, gunakan rumus Haversine manual:
            
            $dist = $this->calculateHaversine($lat, $lng, $office->latitude, $office->longitude);

            if ($dist < $minDistance) {
                $minDistance = $dist;
                $nearestName = $office->name;
            }

            // Cek apakah masuk radius
            if ($dist <= $office->radius) {
                $matchedShift = $shift;
                $isValid = true;
                break; // Ketemu yang valid, stop loop
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