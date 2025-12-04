<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Leave;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    public function index()
    {
        $leaves = Leave::where('user_id', auth()->id())
            ->with('approver')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('leave.index', compact('leaves'));
    }

    public function create()
    {
        return view('leave.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:leave,sick,business_trip',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:1000',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only(['type', 'start_date', 'end_date', 'reason']);
        $data['user_id'] = auth()->id();

        // Upload attachment jika ada
        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('leave/attachments', 'public');
        }

        Leave::create($data);

        return redirect()->route('leave.index')->with('success', 'Pengajuan izin berhasil dibuat!');
    }

    public function show(Leave $leave)
    {
        // Pastikan hanya pemilik atau admin yang bisa lihat
        if ($leave->user_id !== auth()->id() && !auth()->user()->hasRole('admin')) {
            abort(403);
        }

        return view('leave.show', compact('leave'));
    }

    public function destroy(Leave $leave)
    {
        // Hanya bisa hapus jika masih pending
        if ($leave->user_id !== auth()->id()) {
            abort(403);
        }

        if ($leave->status !== 'pending') {
            return back()->with('error', 'Hanya pengajuan yang masih pending yang bisa dihapus!');
        }

        $leave->delete();

        return redirect()->route('leave.index')->with('success', 'Pengajuan izin berhasil dihapus!');
    }

    // Admin: Approve/Reject Leave
    public function approve(Request $request, Leave $leave)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:approved,rejected',
            'approval_notes' => 'nullable|string|max:500',
        ]);

        $leave->update([
            'status' => $request->status,
            'approved_by' => auth()->id(),
            'approval_notes' => $request->approval_notes,
            'approved_at' => now(),
        ]);

        // Jika approved, buat attendance records
        if ($request->status === 'approved') {
            $this->createAttendanceFromLeave($leave);
        }

        return back()->with('success', 'Pengajuan berhasil diproses!');
    }

    private function createAttendanceFromLeave(Leave $leave)
    {
        $userShift = $leave->user->getActiveShift();

        if (!$userShift) {
            return;
        }

        // Tentukan status berdasarkan tipe leave
        $statusMap = [
            'leave' => 'leave',
            'sick' => 'sick',
            'business_trip' => 'business_trip',
        ];

        $status = $statusMap[$leave->type] ?? 'leave';

        // Buat attendance untuk setiap hari dalam periode
        $period = CarbonPeriod::create($leave->start_date, $leave->end_date);

        foreach ($period as $date) {
            // Skip weekend jika shift tidak include hari tersebut
            $dayName = strtolower($date->format('l'));

            if ($userShift->shift->isActiveOnDay($dayName)) {
                Attendance::updateOrCreate(
                    [
                        'user_id' => $leave->user_id,
                        'date' => $date,
                    ],
                    [
                        'shift_id' => $userShift->shift_id,
                        'status' => $status,
                        'notes' => $leave->reason,
                    ]
                );
            }
        }
    }

    // Admin: List semua pengajuan
    public function adminIndex(Request $request)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $query = Leave::with(['user', 'approver']);

        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->has('type') && $request->type !== '') {
            $query->where('type', $request->type);
        }

        $leaves = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('leave.admin-index', compact('leaves'));
    }
}
