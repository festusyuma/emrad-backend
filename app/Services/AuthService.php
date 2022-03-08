<?php

namespace Emrad\Services;

use Emrad\Http\Resources\PermissionsResource;
use Emrad\Http\Resources\RolesResource;
use Emrad\Http\Resources\UsersResource;
use Emrad\Util\CustomResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthService
{
    function login($request): CustomResponse
    {
        try {

            $correct = Auth::attempt(['email' => request('email'), 'password' => request('password')]);
            if (!$correct) return CustomResponse::unAuthorized('username or password is incorrect');

            $user = Auth::user();
            $data = [
                'token' => $user->createToken('Emrad')->accessToken,
                'details' => new UsersResource($user)
            ];

            return CustomResponse::success($data);
        } catch (\Exception $e) {
            return CustomResponse::serverError($e);
        }
    }
}
