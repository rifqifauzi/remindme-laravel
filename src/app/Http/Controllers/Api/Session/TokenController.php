<?php

namespace App\Http\Controllers\Api\Session;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class TokenController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except('create');
    }

    public function create(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email/password salah'],
            ]);
        }

        // only 1 active api token per user
        $user->tokens()->delete();

        return $user->createToken($request->device_name)->plainTextToken;
    }

    public function destroy(Request $request)
    {
        $user = $request->user();

        $user->tokens()->delete();
    }
}
