<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($this->user)],
            'role' => ['required', 'string', 'exists:roles,name'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string'],
            'foto_profil' => ['nullable', 'image', 'max:2048'], // max 2MB
            'kelompok_id' => ['nullable', 'exists:kelompok,id'],
        ];

        if ($this->isMethod('POST')) {
            $rules['password'] = ['required', 'string', 'min:8', 'confirmed'];
        }

        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['password'] = ['nullable', 'string', 'min:8', 'confirmed'];
        }

        // NIM dan Jurusan hanya wajib untuk mahasiswa
        if ($this->input('role') === 'mahasiswa') {
            $rules['nim'] = ['required', 'string', 'max:20', Rule::unique('users')->ignore($this->user)];
            $rules['jurusan'] = ['required', 'string', 'in:informatika,bisnis digital,gizi'];
            $rules['nip'] = ['nullable', 'string', 'max:20'];
        }

        // NIP hanya wajib untuk dpl
        if ($this->input('role') === 'dpl') {
            $rules['nip'] = ['required', 'string', 'max:20', Rule::unique('users')->ignore($this->user)];
            $rules['nim'] = ['nullable', 'string', 'max:20'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'role.required' => 'Role wajib dipilih.',
            'role.exists' => 'Role tidak valid.',
            'nim.required' => 'NIM wajib diisi untuk mahasiswa.',
            'nim.unique' => 'NIM sudah digunakan.',
            'jurusan.required' => 'Jurusan wajib dipilih untuk mahasiswa.',
            'jurusan.in' => 'Jurusan harus dipilih dari opsi yang tersedia.',
            'nip.required' => 'NIP wajib diisi untuk dosen.',
            'nip.unique' => 'NIP sudah digunakan.',
            'foto_profil.image' => 'File harus berupa gambar.',
            'foto_profil.max' => 'Ukuran foto maksimal 2MB.',
            'kelompok_id.exists' => 'Kelompok tidak valid.',
        ];
    }
} 