<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        // Group permissions berdasarkan nama depan (users.index -> users)
        $permissions = Permission::all()->groupBy(function($perm) {
            return explode('.', $perm->name)[0]; 
        });
        
        // Panggil daftar nama modul bahasa Indonesia
        $moduleNames = $this->getModuleNames();
        
        return view('roles.create', compact('permissions', 'moduleNames'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'required|array'
        ]);

        DB::beginTransaction();
        try {
            $role = Role::create(['name' => $request->name]);
            $role->syncPermissions($request->permissions);
            
            DB::commit();
            return redirect()->route('roles.index')->with('success', 'Role berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal membuat role: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        
        if($role->name === 'super-admin') {
            // return back()->with('error', 'Role Super Admin tidak bisa diedit.');
        }

        $permissions = Permission::all()->groupBy(function($perm) {
            return explode('.', $perm->name)[0]; 
        });
        
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        // Panggil daftar nama modul bahasa Indonesia
        $moduleNames = $this->getModuleNames();

        return view('roles.edit', compact('role', 'permissions', 'rolePermissions', 'moduleNames'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $id,
            'permissions' => 'nullable|array'
        ]);

        DB::beginTransaction();
        try {
            $role = Role::findOrFail($id);
            $role->name = $request->name;
            $role->save();

            $role->syncPermissions($request->permissions ?? []);
            
            DB::commit();
            return redirect()->route('roles.index')->with('success', 'Role berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal update role: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        if($role->name === 'super-admin') {
            return back()->with('error', 'Role Super Admin tidak bisa dihapus.');
        }
        
        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Role berhasil dihapus.');
    }

    /**
     * PRIVATE HELPER: Daftar Terjemahan Modul
     * Ubah di sini, otomatis berubah di Create & Edit
     */
    private function getModuleNames()
    {
        return [
            // System & Users
            'dashboard'         => 'Dashboard Utama',
            'users'             => 'Manajemen Pengguna',
            'roles'             => 'Role & Hak Akses',
            
            // Operasional ISP
            'coverage_areas'    => 'Area Jangkauan (Coverage)',
            'packages'          => 'Paket Internet',
            'offices'           => 'Kantor Cabang',
            'shifts'            => 'Master Shift Kerja',
            'user-shifts'       => 'Jadwal Shift Pegawai',
            'korlaps'           => 'Data Korlap',
            
            // Pelanggan & Billing
            'customers'         => 'Data Pelanggan',
            'tickets'           => 'Tiket & Support',
            'billings'          => 'Tagihan Pelanggan',
            'payments'          => 'Riwayat Pembayaran',
            
            // Gudang (Inventory)
            'warehouse'         => 'Gudang (General)',
            'item-category'     => 'Kategori Barang',
            'item'              => 'Data Barang',
            'supplier'          => 'Data Supplier',
            'incoming-goods'    => 'Barang Masuk',
            'outgoing-goods'    => 'Barang Keluar',
            'warehouse-transfer'=> 'Mutasi / Transfer Barang',
            'stock-report'      => 'Laporan Stok',
            
            // HR & Absensi
            'attendance'        => 'Absensi Pegawai',
            'leave'             => 'Pengajuan Cuti',
        ];
    }
}