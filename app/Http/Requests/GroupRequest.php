<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GroupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_kelompok' => ['required', 'string', 'max:255'],
            'angkatan_id' => ['required', 'exists:angkatan,id'],
            'lokasi_id' => ['required', 'exists:lokasi,id'],
            'dpl_id' => ['required', 'exists:users,id'],
            'deskripsi' => ['nullable', 'string'],
            'mahasiswa_ids' => ['nullable', 'array'],
            'mahasiswa_ids.*' => ['exists:users,id']
        ];
    }

    public function messages(): array
    {
        return [
            'nama_kelompok.required' => 'Nama kelompok wajib diisi.',
            'angkatan_id.required' => 'Angkatan wajib dipilih.',
            'angkatan_id.exists' => 'Angkatan tidak valid.',
            'lokasi_id.required' => 'Lokasi wajib dipilih.',
            'lokasi_id.exists' => 'Lokasi tidak valid.',
            'dpl_id.required' => 'DPL wajib dipilih.',
            'dpl_id.exists' => 'DPL tidak valid.',
            'mahasiswa_ids.array' => 'Format data mahasiswa tidak valid.',
            'mahasiswa_ids.*.exists' => 'Mahasiswa tidak valid.'
        ];
    }
} 