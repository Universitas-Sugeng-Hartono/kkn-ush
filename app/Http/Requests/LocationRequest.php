<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_desa' => ['required', 'string', 'max:255'],
            'nama_kecamatan' => ['required', 'string', 'max:255'],
            'nama_kabupaten' => ['required', 'string', 'max:255'],
            'nama_provinsi' => ['required', 'string', 'max:255'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'deskripsi' => ['nullable', 'string']
        ];
    }

    public function messages(): array
    {
        return [
            'nama_desa.required' => 'Nama desa wajib diisi.',
            'nama_kecamatan.required' => 'Nama kecamatan wajib diisi.',
            'nama_kabupaten.required' => 'Nama kabupaten wajib diisi.',
            'nama_provinsi.required' => 'Nama provinsi wajib diisi.',
            'latitude.required' => 'Latitude wajib diisi.',
            'latitude.numeric' => 'Latitude harus berupa angka.',
            'latitude.between' => 'Latitude harus berada di antara -90 dan 90.',
            'longitude.required' => 'Longitude wajib diisi.',
            'longitude.numeric' => 'Longitude harus berupa angka.',
            'longitude.between' => 'Longitude harus berada di antara -180 dan 180.',
        ];
    }
} 