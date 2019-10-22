<?php

namespace Emrad\Http\Controllers;

use Illuminate\Http\Request;
use Emrad\Services\RolesServices;
use Emrad\Services\UsersServices;
use Emrad\Http\Requests\CreateUser;
use Emrad\Facade\UsersServicesFacade;
use Emrad\Http\Resources\UsersResource;
use Emrad\Services\PermissionsServices;

class UsersController extends Controller
{
    /**
     * @var UsersServices $usersServices
     */
    public $usersServices;

    /**
     * @var RolesServices $rolesServices
     */
    public $rolesServices;

    /**
     * @var PermissionsServices $permissionsServices
     */
    public $permissionsServices;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        UsersServices $usersServices,
        RolesServices $rolesServices,
        PermissionsServices $permissionsServices
    )
    {
        $this->usersServices = $usersServices;
        $this->permissionsServices = $permissionsServices;
        $this->rolesServices = $rolesServices;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createUser(CreateUser $request)
    {
        $pathToFile = config('app.url')."/default-user-icon.jpg";
        $user = UsersServicesFacade::createUser(
                                                    auth('api')->user()->company_id,
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

        return response([
            'status' => 'success',
            'message' => 'user created successfully',
            'data' => new UsersResource($user)
        ], 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function getUser()
     {
        $user = $this->usersServices->getUser();
        return response([
                            'status' => 'success',
                            'message' => 'user retrieved successfully',
                            'data' => new UsersResource($user)
                        ], 200);
     }
}
