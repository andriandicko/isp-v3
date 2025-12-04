<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // --- DATA AKUN ---
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:residential,business',
            'status' => 'required|in:active,isolir,inactive', // Tambahan Baru
            'company_name' => 'nullable|required_if:type,business|string|max:255',
            'contact_person' => 'nullable|string|max:255',

            // --- LOKASI ---
            'coverage_area_id' => 'required|exists:coverage_areas,id',
            'address' => 'nullable|string',
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',

            // --- DATA TEKNIS (WAJIB DITAMBAHKAN) ---
            'korlap_id' => 'nullable|exists:korlaps,id',
            'no_odp' => 'nullable|string|max:255',
            'mac_ont' => 'nullable|string|max:255',

            // --- DOKUMENTASI FOTO (WAJIB DITAMBAHKAN) ---
            // Validasi: Harus Gambar (jpeg,png,jpg) & Maksimal 2MB (2048 KB)
            'foto_rumah' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_ktp' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_redaman' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'User wajib dipilih.',
            'coverage_area_id.required' => 'Area coverage wajib dipilih.',
            'type.required' => 'Tipe layanan wajib dipilih.',
            'status.required' => 'Status layanan wajib dipilih.',
            'lat.required' => 'Lokasi (Latitude) wajib diisi dari peta.',
            'lng.required' => 'Lokasi (Longitude) wajib diisi dari peta.',

            // Pesan Error untuk Foto
            'foto_rumah.image' => 'File foto rumah harus berupa gambar.',
            'foto_rumah.max' => 'Ukuran foto rumah maksimal 2MB.',
            'foto_ktp.image' => 'File KTP harus berupa gambar.',
            'foto_ktp.max' => 'Ukuran foto KTP maksimal 2MB.',
        ];
    }
}
