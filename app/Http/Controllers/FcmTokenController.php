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
            auth()->user()->fcmTokens()->firstOrCreate([
                'token' => $request->token
            ]);

            return response()->json(['message' => 'Token successfully stored.']);
        }

        return response()->json(['message' => 'Unauthenticated.'], 401);
    }
}
