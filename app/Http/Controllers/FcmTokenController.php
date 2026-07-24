<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FcmTokenController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        if (auth()->check()) {
            \App\Models\FcmToken::updateOrCreate(
                ['token' => $request->token],
                ['user_id' => auth()->id()]
            );

            return response()->json(['message' => 'Token successfully stored.']);
        }

        return response()->json(['message' => 'Unauthenticated.'], 401);
    }
}
