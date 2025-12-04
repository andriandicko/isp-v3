<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    // Pastikan hanya admin yang bisa akses controller ini
    public function __construct()
    {
        // $this->middleware('permission:role.view|role.create|role.edit|role.delete');
        // Atau handle di route/middleware lain
    }

    public function index()
    {
        $roles = Role::with('permissions')->get();
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        // Mengelompokkan permission berdasarkan nama depan (misal: 'attendance.index' jadi grup 'attendance')
        // Ini agar tampilan checkbox rapi per modul
        $permissions = Permission::all()->groupBy(function($perm) {
            return explode('.', $perm->name)[0]; 
        });
        
        return view('roles.create', compact('permissions'));
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
        
        // Jangan biarkan edit super-admin jika hardcoded
        if($role->name === 'super-admin') {
            // return back()->with('error', 'Role Super Admin tidak bisa diedit.');
        }

        $permissions = Permission::all()->groupBy(function($perm) {
            return explode('.', $perm->name)[0]; 
        });
        
        // Ambil ID permission yang sudah dimiliki role ini
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('roles.edit', compact('role', 'permissions', 'rolePermissions'));
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

            // Update hak akses
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
        if($role->name === 'super-admin') { // Proteksi role vital
            return back()->with('error', 'Role Super Admin tidak bisa dihapus.');
        }
        
        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Role berhasil dihapus.');
    }
}