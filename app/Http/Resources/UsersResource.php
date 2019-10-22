<?php

namespace Emrad\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UsersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'userId' => $this->id,
            'userFirstName' => $this->first_name,
            'userMiddleName' => $this->middle_name,
            'userLastName' => $this->last_name,
            'userGender' => $this->gender,
            'userCompany' => $this->company_id,
            // 'userIsVerified' => $this->checkIfUserIsVerified(),
            // 'userIsPro' => $this->checkIfUserIsPro(),
            // 'userHasUpdateProfile' => $this->checkIfUserHasUpdateProfile(),
            // 'userIsFollowing' => $this->checkIfUserIsFollowing(),
            'username' => $this->username,
            'userPhoneNumber' => $this->phone_number,
            // 'userFollowersCount' => $this->followers()->count(),
            // 'userBvn' => $this->bvn,
            'userEmail' => $this->email,
            // 'userTools' => $this->merchant->tools->pluck('id'),
            'userAvater' => $this->avater,
            'userAddress' => $this->address,
            // 'userSkills' => json_decode($this->skill),
            'userLocation' => $this->location,
            // 'userWebsite' => $this->when($this->website === "null", function() {
            //     return '';
            // }, $this->website),
            'userBio' => $this->bio,
            'userPermissions' => $this->permissions,
            'createdAt' => $this->created_at
        ];
    }
    /**
     * checks if the current user passed to the resource is verified via email
     *
     * @return boolean
     */
    public function checkIfUserIsVerified()
    {
        return $this->when($this->email_verified_at !== null, function() {
            return true;
        }, false);
    }

    /**
     * check whether the current authenticated user is following
     * the user passed to the resource if true returns True if false
     * or no auth user return false
     *
     * @return boolean
     */
    public function checkIfUserIsFollowing()
    {
        return $this->when(auth('api')->user(), function() {
            return auth('api')->user()->isFollowing(\FlexiCreative\User::find($this->id)) ?? true;
        }, false);
    }

    /**
     * check if user has an active subscription
     *
     * @return boolean
     */
    public function checkIfUserIsPro()
    {
        return $this->when($this->subscription_status == 1, function() {
            return true;
        }, false);
    }

    /**
     * check if user has updated his/her profile
     * using the bio
     */
    public function checkIfUserHasUpdateProfile()
    {
        return $this->when(strlen($this->bio) > 2, function() {
            return true;
        }, false);
    }
}
