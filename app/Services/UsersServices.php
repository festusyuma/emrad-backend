<?php

namespace Emrad\Services;

use Emrad\User;
use Emrad\Models\SocialProfile;
use Emrad\Services\SubsServices;
use Emrad\Repositories\Contracts\UserRepositoryInterface;
use Emrad\Util\CustomResponse;

class UsersServices
{
    /**
     * @var $userRepositoryInterface
     */
    public $userRepositoryInterface;

    public function __construct(UserRepositoryInterface $userRepositoryInterface)
    {
        $this->userRepositoryInterface = $userRepositoryInterface;
    }

    /**
     * create a new User Instance
     *
     * @param String $companyId
     * @param String $firstName
     * @param String $lastName
     * @param String $gender
     * @param String $pathToAvater
     * @param String $phoneNumber
     * @param String $email
     * @param String $password
     * @param String $address
     * @param Bool $rememberToken
     *
     * return Emrad\User
      */

    public function createUser(
        $companyId,
        $firstName,
        $lastName,
        $gender,
        $pathToAvater = "https://emrad-test.flexi.ng/default-user-icon.jpg",
        $phoneNumber,
        $email,
        $password,
        $address = NULL,
        $rememberToken = NULL
    )
    {
        // dd($firstName);
        // instatiate a new user
        $user = new User;

        try {
            $user->company_id = $companyId;
            $user->first_name = $firstName;
            $user->last_name = $lastName;
            $user->gender = $gender ?? "male";
            $user->avater = $pathToAvater;
            $user->phone_number = $phoneNumber;
            $user->email = $email;
            $user->address = $address;
            $user->password = bcrypt($password);
            $user->remember_token = $rememberToken;

            $user->save();

            return $user;
        } catch (\Exception $e) {
            return $e;
        }
    }

    /**
     * update an existing User
     *
     * @param String $firstName
     * @param String $middleName
     * @param String $lastName
     * @param String $gender
     * @param String $pathToAvater
     * @param String $phoneNumber
     * @param String $email
     * @param String $password
     * @param String $address
     * @param Bool $rememberToken
     *
     * @return Emrad\User
      */

      public function updateUser(
        User $user,
        ?String $firstName,
        ?String $lastName,
        ?String $gender,
        ?String $pathToAvater,
        ?String $phoneNumber,
        ?String $email,
        ?String $address,
        ?String $password,
        ?String $rememberToken = null
    )
    {
        try {
            $user->first_name = $firstName;
            $user->last_name =$lastName;
            $user->phone_number =$phoneNumber;
            $user->gender =$gender;
            if(!$pathToAvater == null ||!$pathToAvater == "") {
                $user->avater = $pathToAvater;
            }
            $user->email = $email;
            $user->address = $address;
            if(!$password == null || !$password == "") {
                $user->password = bcrypt($password);
            }
            $user->remember_token = $rememberToken;

            $user->save();

            return $user;
        } catch (\Exception $e) {
            return $e;
        }
    }

    /**
     * get the social url of the auth user
     *
     * @param string $id
     *
     * @return getSocialProfile $social
     */
    public function getSocialProfile()
    {
        return auth()->user()->socialProfile()->first();
    }

    public function getUser(): CustomResponse
    {
        try {
            $user = auth()->user();
            return CustomResponse::success($user);
        } catch (\Exception $e) {
            return CustomResponse::serverError($e);
        }
    }

    /**
     * Toogle user as follow or unfollow status
     *
     * @param Request $request
     *
     * @return Emrad\Models\Merchant $merchant
     */
    public function toggleFollow($request)
    {
        // get the auth user
        $authUser = auth()->user();

        // find the user by id
        $user = $this->userRepositoryInterface->find($request->userId);

        // toggles the user like option
        $authUser->toggleFollow($user);

        return $user->merchant;

    }

    /**
     * delete a user and all user products
     *
     * @param int|string $userId
     *
     * @return response
     */
    public function deleteUser($userId)
    {
        $user = $this->userRepositoryInterface->find($userId);
        return $user->delete();

    }

    /**
     * forget password
     *
     * $param $request
     *
     * @return
     */
    public function forgotPassword($request)
    {
        return $findUser = UsersFacade::findByEmail($request->email);
    }
}


