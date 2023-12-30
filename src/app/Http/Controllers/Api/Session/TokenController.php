<?php

namespace App\Http\Controllers\Api\Session;

use App\Enums\ResponseMessageEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\SessionUserRequest;
use App\Http\Response\BaseResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class TokenController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except('session');
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
        //dd("test");
        return $user->createToken($request->device_name)->plainTextToken;
    }

    public function destroy(Request $request)
    {
        $user = $request->user();

        $user->tokens()->delete();
    }

    public function session(SessionUserRequest $request): BaseResponse
    {
        
        if ($request->validator->fails()) {
            return new BaseResponse(
                ResponseMessageEnum::VALIDATION_ERROR,
                $request->validator->messages(),
                Response::HTTP_BAD_REQUEST
            );
        }

        $validated = $request->validated();

        if (!Auth::attempt($validated)) {
            return new BaseResponse(
                ResponseMessageEnum::UNAUTHENTICATED,
                (object)[],
                Response::HTTP_UNAUTHORIZED
            );
        }

        $pengguna = User::where('email', $request->safe()->only(['email']))->firstOrFail();

        $token = $pengguna->createToken('auth_token')->plainTextToken;

        return new BaseResponse(
            ResponseMessageEnum::SUCCESS,
            ['bearer_token' => $token]
        );
    }
    
    public function profile(): BaseResponse
    {
        $pengguna = auth()->user()->load(['region']);
        $pengguna['region_nama'] = $pengguna['region']->nama;
        unset($pengguna['region']);

        return new BaseResponse(
            ResponseMessageEnum::SUCCESS,
            $pengguna
        );
    }
}
