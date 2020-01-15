<?php

namespace Emrad\Http\Controllers;

use Illuminate\Support\Str;
use Emrad\Events\NewCompanyCreated;
use Emrad\Http\Requests\CreateUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Emrad\Facade\UsersServicesFacade;
use Emrad\Http\Requests\ResetPassword;
use Emrad\Http\Resources\RolesResource;
use Emrad\Http\Resources\UsersResource;
use Emrad\Facade\CompaniesServicesFacade;
use Illuminate\Auth\Events\PasswordReset;
use Emrad\Http\Resources\PermissionsResource;
use Symfony\Component\HttpFoundation\Request;

class AuthController extends Controller
{

    public $successStatus = 200;

    /**
     * login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request){
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])
        || Auth::attempt(['email' => request('email'), 'password' => request('password')])){
            $user = Auth::user();
            $success['token'] =  $user->createToken('Emrad')->accessToken;
            $success['details'] =  new UsersResource($user);
            $success['roles'] =  RolesResource::collection($user->roles);
            $success['permissions'] = PermissionsResource::collection($user->permissions);
            return response()->json(['status' => 'success', 'data' => $success], 200);
        }
        else{
            return response()->json(['status' => 'fail', 'error'=>'password and email mismatch'], 401);
        }
    }

    /**
     * Registration api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(CreateUser $request)
    {
        $pathToFile = config('app.url')."/default-user-icon.jpg";
        $company = CompaniesServicesFacade::createCompany($request);
        $user = UsersServicesFacade::createUser(
                                            $company->id,
                                            $request->firstName,
                                            $request->lastName,
                                            $request->gender,
                                            $pathToFile,
                                            $request->phoneNumber,
                                            $request->email,
                                            $request->password,
                                            $request->address,
                                            $request->rememberToken
                                        );


        event(new NewCompanyCreated($user, $company));
                            // dd('test');
        $user->assignRole('Retailer');

        $message = 'Please confirm yourself by clicking on verify user button sent to your email';

        return response()->json([
                                    'status' => 'success',
                                    'message'=>$message,
                                    'data' => []
                                    ], 201);
    }

    /**
     * Delete all users token
     * and log user out
     */
    public function logout()
    {
        auth()->user()->tokens->each(function ($token, $key) {
            $token->delete();
        });
        return response()->json([
                                    'status' => 'success',
                                    'message'=>"User logged out successfully",
                                    'data' => []
                                ], 200);
    }

    /**
     * Resets users password
     *
     * @param $request
     *
     * @return void
     */
    public function setPassword(ResetPassword $request)
    {
        $user = auth()->user();
        $oldPassword = $request->oldPassword;
        $newPassword = $request->password;

        if (Hash::check($oldPassword, $user->password)) {

            $user->password = Hash::make($newPassword);

            $user->setRememberToken(Str::random(60));

            $user->save();

            event(new PasswordReset($user));

            // $this->guard()->login($user);

            return response()->json([
                                    'status' => 'success',
                                    'message' => 'password reset successfully'
                                    ]);
        }
        return response()->json([
                                'status' => 'fail',
                                'message' => 'password does not match'
                                ],422);
    }

    /**
     * forgot password function sends a passpord reset link
     *
     * @param string $email
     *
     * @return response
     */
    public function forgotPassword(Request $request)
    {
        UsersServicesFacade::forgotPassword($request);
        return response([
                            'status' => "success",
                            'message' => "If you account exist we will send you a mail",
                        ],200);
    }
}
