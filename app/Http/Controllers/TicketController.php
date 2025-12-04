<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Ticket::with(['customer.user', 'user']);

        //hanya tampilkan tiket miliknya
        if ($user->customer) {
            $query->where('customer_id', $user->customer->id);
        }

        // Filter Status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter Prioritas
        if ($request->has('priority') && $request->priority != '') {
            $query->where('priority', $request->priority);
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ticket_code', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($c) use ($search) {
                        $c->where('company_name', 'like', "%{$search}%")
                            ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%"));
                    });
            });
        }

        // Urutkan: Prioritas Critical paling atas, lalu tanggal terbaru
        $tickets = $query->orderByRaw("FIELD(priority, 'critical', 'high', 'medium', 'low')")
            ->latest()
            ->paginate(10);

        return view('tickets.index', compact('tickets'));
    }

    // Self-servive Customer
    // public function create()
    // {
    //     // Cek apakah user yang login memiliki data customer
    //     $user = auth()->user();

    //     // Jika user login bukan customer (misal admin yang iseng buka link ini), tolak aksesnya
    //     // Atau bisa diredirect dengan pesan error
    //     if (!$user->customer) {
    //         return redirect()->back()->with('error', 'Anda tidak terdaftar sebagai Customer. Hubungi Admin.');
    //     }

    //     // Tidak perlu ambil list $customers lagi karena otomatis diri sendiri
    //     return view('tickets.create');
    // }
    
    // Internal Sistem
    public function create()
    {
        $customers = Customer::with('user')
            ->where('status', 'active')
            ->get();
            
            $technicians = User::role('teknisi')->get();

        return view('tickets.create', compact('customers', 'technicians'));
    }
    

    // Self Service
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'subject'     => 'required|string|max:255',
    //         'description' => 'required|string',
    //         // Kita validasi issue_type, bukan priority lagi
    //         'issue_type'  => 'required|in:slow,intermittent,no_internet,device,other',
    //         'photo'       => 'nullable|image|max:2048',
    //     ]);

    //     DB::beginTransaction();
    //     try {
    //         $user = auth()->user();
    //         if (!$user->customer) {
    //             throw new \Exception('Data Customer tidak ditemukan.');
    //         }

    //         // --- LOGIKA PENENTUAN PRIORITAS OTOMATIS ---
    //         $priorityMap = [
    //             'no_internet'  => 'critical', // Mati total = Critical
    //             'device'       => 'high',     // Alat rusak = High
    //             'intermittent' => 'medium',   // Putus nyambung = Medium
    //             'slow'         => 'low',      // Lambat = Low
    //             'other'        => 'medium',   // Lainnya = Medium
    //         ];

    //         // Ambil prioritas berdasarkan gejala, default ke medium
    //         $priority = $priorityMap[$request->issue_type] ?? 'medium';
    //         // --------------------------------------------

    //         // Generate Kode
    //         $prefix = 'TIK-' . date('ym');
    //         $random = strtoupper(substr(uniqid(), -4));
    //         $ticketCode = $prefix . '-' . $random;

    //         $photoPath = null;
    //         if ($request->hasFile('photo')) {
    //             $photoPath = $request->file('photo')->store('tickets', 'public');
    //         }

    //         Ticket::create([
    //             'ticket_code' => $ticketCode,
    //             'customer_id' => $user->customer->id,
    //             'user_id'     => null,
    //             'subject'     => $request->subject . ' (' . ucfirst(str_replace('_', ' ', $request->issue_type)) . ')', // Optional: Tambah info gejala ke subjek
    //             'description' => $request->description,
    //             'priority'    => $priority, // <--- Diisi Otomatis oleh Sistem
    //             'status'      => 'open',
    //             'photo'       => $photoPath,
    //         ]);

    //         DB::commit();
    //         return redirect()->route('tickets.index')->with('success', "Laporan diterima. Prioritas diset ke: " . ucfirst($priority));
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return back()->withInput()->with('error', 'Gagal: ' . $e->getMessage());
    //     }
    // }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id', // Wajib pilih customer
            'subject'     => 'required|string|max:255',
            'description' => 'required|string',
            'issue_type'  => 'required|in:slow,intermittent,no_internet,device,other',
            'photo'       => 'nullable|image|max:2048',
        ]);

        DB::beginTransaction();
        try {
            // --- LOGIKA PENENTUAN PRIORITAS OTOMATIS ---
            $priorityMap = [
                'no_internet'  => 'critical',
                'device'       => 'high',
                'intermittent' => 'medium',
                'slow'         => 'low',
                'other'        => 'medium',
            ];
            $priority = $priorityMap[$request->issue_type] ?? 'medium';

            // Generate Kode
            $prefix = 'TIK-' . date('ym');
            $random = strtoupper(substr(uniqid(), -4));
            $ticketCode = $prefix . '-' . $random;

            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('tickets', 'public');
            }

            Ticket::create([
                'ticket_code' => $ticketCode,
                'customer_id' => $request->customer_id, // Diisi dari inputan form
                'user_id'     => null, // Teknisi belum ditentukan
                'subject'     => $request->subject,
                'description' => $request->description . "\n\n(Dibuat oleh: " . auth()->user()->name . ")", // Log siapa yang input
                'priority'    => $priority,
                'status'      => 'open',
                'photo'       => $photoPath,
            ]);

            DB::commit();
            return redirect()->route('tickets.index')->with('success', "Tiket #{$ticketCode} berhasil dibuat untuk pelanggan.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function show(Ticket $ticket)
    {
        $user = auth()->user();

        if ($user->customer && $ticket->customer_id !== $user->customer->id) {
            abort(403, 'Anda tidak memiliki akses ke tiket ini.');
        }

        // load percakapan
        $ticket->load(['replies.user', 'customer.user', 'user']);

        return view('tickets.show', compact('ticket'));
    }

    public function edit(Ticket $ticket)
    {
        $customers = Customer::with('user')->get();
        $technicians = User::role('teknisi')->orderBy('name')->get();

        return view('tickets.edit', compact('ticket', 'customers', 'technicians'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'user_id'     => 'nullable|exists:users,id',
            'priority'    => 'required|in:low,medium,high,critical',
            'status'      => 'required|in:open,in_progress,resolved,closed',
            'description' => 'required|string', // Update progress di deskripsi atau tambah fitur komentar nanti
        ]);

        // Logic Resolved Date
        $resolvedAt = $ticket->resolved_at;

        // Jika status berubah jadi resolved/closed DAN sebelumnya belum ada tanggal selesai
        if (in_array($validated['status'], ['resolved', 'closed']) && !$resolvedAt) {
            $resolvedAt = now();
        }
        // Jika status dikembalikan ke open/progress, reset tanggal selesai
        if (in_array($validated['status'], ['open', 'in_progress'])) {
            $resolvedAt = null;
        }

        $ticket->update([
            'user_id'     => $validated['user_id'],
            'priority'    => $validated['priority'],
            'status'      => $validated['status'],
            'description' => $validated['description'], // Bisa diedit utk update kronologi
            'resolved_at' => $resolvedAt
        ]);

        return redirect()->route('tickets.index')->with('success', 'Tiket berhasil diperbarui.');
    }

    public function destroy(Ticket $ticket)
    {
        if ($ticket->photo) {
            Storage::disk('public')->delete($ticket->photo);
        }
        $ticket->delete();
        return redirect()->route('tickets.index')->with('success', 'Tiket berhasil dihapus.');
    }

    public function reply(Request $request, Ticket $ticket)
    {
        $request->validate([
            'message' => 'required|string',
            'attachment' => 'nullable|image|max:2048'
        ]);

        $path = null;
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('ticket-replies', 'public');
        }

        // Simpan Chat
        $ticket->replies()->create([
            'user_id' => auth()->id(),
            'message' => $request->message,
            'attachment' => $path
        ]);

        // Opsional: Jika customer yang balas, ubah status jadi 'open' lagi (biar admin notice)
        // Jika admin yang balas, ubah status jadi 'in_progress' (menunggu customer)
        // $ticket->update(['status' => auth()->user()->customer ? 'open' : 'in_progress']);

        return back()->with('success', 'Pesan terkirim.');
    }
}
