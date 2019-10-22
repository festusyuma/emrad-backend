<?php

namespace Emrad\Repositories;

use Emrad\User;
use Emrad\Repositories\Contracts\UserRepositoryInterface;


class UserRepository extends BaseRepository implements UserRepositoryInterface {

    public $model;

    /**
     * UserRepository Constructor
     *
     * @param Emrad\User $user
      */
    public function __construct(User $user)
    {
        $this->model = $user;
    }

    /**
     * get the user by email
     *
     * @param string $email
     *
     * @return User $user
     */
    public function findByEmail($email, $relations = []){
        return $this->model
        ->where('email', $email)
        ->with($relations)
        ->first();
    }
}
