<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class CashierController extends Controller
{
    public function listCashiers(Request $request)
    {
        $businessId = Auth::user()->business_id;

        // Fetch all active users belonging to this business
        $cashiers = User::query()
            ->where('business_id', $businessId)
            ->where('status', 'active')
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->first_name,
                    'avatar_url' => $user->avatar_path ? Storage::url($user->avatar_path) : null,
                    'is_current' => $user->id === Auth::id(),
                ];
            });

        return response()->json($cashiers);
    }

    public function switchCashier(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'credentials' => 'required|string', // PIN or Password
        ]);

        $targetUser = User::findOrFail($request->user_id);
        $credentials = $request->credentials;

        $authenticated = false;

        // Check if credentials match pin_hash (if set) or fallback to password
        if ($targetUser->pin_hash && Hash::check($credentials, $targetUser->pin_hash)) {
            $authenticated = true;
        } elseif (Hash::check($credentials, $targetUser->password)) {       
            $authenticated = true;
        } elseif (strlen($credentials) === 4 && $targetUser->code === $credentials) {
            // Optional: Support quick 4-digit code match if stored in code column
            $authenticated = true;
        }

        if (!$authenticated) {
            return response()->json([
                'success' => false,
                'message' => __('Contraseña o PIN incorrecto.')
            ], 422);
        }

        Auth::login($targetUser);
        $request->session()->regenerate();

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $targetUser->id,
                'name' => $targetUser->full_name,
                'avatar_url' => $targetUser->avatar_path ? Storage::url($targetUser->avatar_path) : null,
            ]
        ]);
    }
}