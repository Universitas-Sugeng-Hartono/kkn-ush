<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API untuk mendapatkan data mahasiswa berdasarkan ID
Route::get('/students/{user}', function (App\Models\User $user) {
    return response()->json([
        'id' => $user->id,
        'name' => $user->name,
        'nim' => $user->nim,
        'kelompok' => $user->kelompok ? [
            'id' => $user->kelompok->id,
            'nama' => $user->kelompok->nama
        ] : null
    ]);
}); 