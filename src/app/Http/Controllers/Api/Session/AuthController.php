<?php

namespace App\Http\Controllers\Api\Session;

use App\Enums\PenggunaStatusEnum;
use App\Enums\ResponseMessageEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\LoginPenggunaRequest;
use App\Http\Requests\SessionUserRequest;
use App\Http\Requests\UpdateAuthenticatedPenggunaRequest;
use App\Http\Response\BaseResponse;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function register(SessionUserRequest $request): BaseResponse
    {
        if ($request->validator->fails()) {
            return new BaseResponse(
                ResponseMessageEnum::VALIDATION_ERROR,
                $request->validator->messages(),
                Response::HTTP_BAD_REQUEST
            );
        }

        $validated = $request->validated();

        $pengguna = User::create($validated);
        $token = $pengguna->createToken('auth_token')->plainTextToken;

        return new BaseResponse(
            ResponseMessageEnum::SUCCESS,
            ['pengguna' => $pengguna, 'bearer_token' => $token],
            Response::HTTP_CREATED
        );
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
                ResponseMessageEnum::FALSE,
                ['err' => 'ERR_INVALID_CREDS',
                 'msg' => 'incorect username or password'],
                Response::HTTP_UNAUTHORIZED
            );
        }

        $user = User::where('email', $request->safe()->only(['email']))
                    ->select('id', 'email', 'name')
                    ->firstOrFail();
        //$token = $user->createToken('auth_token')->plainTextToken;

        $token = $user->createTokenWithUuid('auth_token');


        return new BaseResponse(
            ResponseMessageEnum::TRUE,
            [
                'user' => $user,
                'access_token' => $token['access_token'],
                'refresh_token' => $token['refresh_token']
                ]
        );
    }

    public function profile(): BaseResponse
    {
        $pengguna = auth()->user();
        
        return new BaseResponse(
            ResponseMessageEnum::SUCCESS,
            $pengguna
        );
    }
/*
    public function updateProfile(UpdateAuthenticatedPenggunaRequest $request): BaseResponse
    {
        if ($request->validator->fails()) {
            return new BaseResponse(
                ResponseMessageEnum::VALIDATION_ERROR,
                $request->validator->messages(),
                Response::HTTP_BAD_REQUEST
            );
        }

        $validated = $request->validated();
        $pengguna = Auth::user();
        $pengguna->update($validated);

        return new BaseResponse(
            ResponseMessageEnum::SUCCESS,
            $pengguna
        );
    }

    public function changePassword(ChangePasswordRequest $request): BaseResponse
    {
        if ($request->validator->fails()) {
            return new BaseResponse(
                ResponseMessageEnum::VALIDATION_ERROR,
                $request->validator->messages(),
                Response::HTTP_BAD_REQUEST
            );
        }

        $validated = $request->validated();
        $validated['status'] = PenggunaStatusEnum::ACTIVE;

        $pengguna = Auth::user();
        $pengguna->update($validated);

        $request->user()->currentAccessToken()->delete();
        $token = $pengguna->createToken('auth_token')->plainTextToken;

        return new BaseResponse(
            ResponseMessageEnum::SUCCESS,
            ['bearer_token' => $token]
        );
    }*/
}
