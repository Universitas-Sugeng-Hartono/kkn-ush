<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Kelompok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserRequest;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['roles', 'kelompok'])->get();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        $kelompok = Kelompok::all();
        return view('users.create', compact('roles', 'kelompok'));
    }

    public function store(UserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'nim' => $request->nim,
            'nip' => $request->nip,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'kelompok_id' => $request->kelompok_id,
        ]);

        if ($request->hasFile('foto_profil')) {
            $path = $request->file('foto_profil')->store('public/foto_profil');
            $user->foto_profil = str_replace('public/', '', $path);
            $user->save();
        }

        $user->assignRole($request->role);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function show(User $user)
    {
        $user->load(['roles', 'kelompok']);
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $kelompok = Kelompok::all();
        return view('users.edit', compact('user', 'roles', 'kelompok'));
    }

    public function update(UserRequest $request, User $user)
    {
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'nim' => $request->nim,
            'nip' => $request->nip,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'kelompok_id' => $request->kelompok_id,
        ]);

        if ($request->password) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        if ($request->hasFile('foto_profil')) {
            $path = $request->file('foto_profil')->store('public/foto_profil');
            $user->foto_profil = str_replace('public/', '', $path);
            $user->save();
        }

        $user->syncRoles([$request->role]);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')
            ->with('success', 'User berhasil dihapus.');
    }
} 