<?php

namespace Emrad\Services;

use Emrad\Events\NewCompanyCreated;
use Emrad\Facade\CompaniesServicesFacade;
use Emrad\Facade\UsersServicesFacade;
use Emrad\Http\Resources\PermissionsResource;
use Emrad\Http\Resources\RolesResource;
use Emrad\Http\Resources\UsersResource;
use Emrad\User;
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

    function register($request): CustomResponse
    {
        try {
            $pathToFile = config('app.url')."/default-user-icon.jpg";
            $company = CompaniesServicesFacade::createCompany($request);
            $user = UsersServicesFacade::createUser(
                $company->id,
                $request->firstName,
                $request->lastName,
                $request->gender,
                'https://w7.pngwing.com/pngs/419/473/png-transparent-computer-icons-user-profile-login-user-heroes-sphere-black-thumbnail.png',
                $request->phoneNumber,
                $request->email,
                $request->password,
                $request->address,
                $request->rememberToken
            );

            event(new NewCompanyCreated($user, $company));
            $user->assignRole($request->userType);
            $message = 'Please confirm yourself by clicking on verify user button sent to your email';

            return CustomResponse::success($user, $message);
        } catch (\Exception $e) {
            return CustomResponse::serverError($e);
        }
    }
}
