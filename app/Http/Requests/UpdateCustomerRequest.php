<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'coverage_area_id' => 'required|exists:coverage_areas,id',
            'korlap_id' => 'nullable|exists:korlaps,id',
            'type' => 'required|in:residential,business',
            'status' => 'required|in:active,isolir,inactive', // <-- TAMBAHAN PENTING
            'company_name' => 'nullable|required_if:type,business|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',

            // Validasi Data Teknis & Foto
            'no_odp' => 'nullable|string|max:255',
            'mac_ont' => 'nullable|string|max:255',
            'foto_rumah' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_ktp' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_redaman' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'user_id.required' => 'User harus dipilih',
            'user_id.exists' => 'User tidak valid',
            'coverage_area_id.required' => 'Coverage area harus dipilih',
            'coverage_area_id.exists' => 'Coverage area tidak valid',
            'korlap_id.exists' => 'Korlap tidak valid',
            'type.required' => 'Tipe customer harus dipilih',
            'type.in' => 'Tipe customer harus residential atau business',
            'company_name.required_if' => 'Nama perusahaan harus diisi untuk tipe business',
            'company_name.max' => 'Nama perusahaan maksimal 255 karakter',
            'contact_person.max' => 'Contact person maksimal 255 karakter',
            'lat.required' => 'Latitude harus diisi',
            'lat.numeric' => 'Latitude harus berupa angka',
            'lat.between' => 'Latitude harus antara -90 dan 90',
            'lng.required' => 'Longitude harus diisi',
            'lng.numeric' => 'Longitude harus berupa angka',
            'lng.between' => 'Longitude harus antara -180 dan 180',
        ];
    }
}
