<?php

namespace Emrad;

use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $guard_name = 'api';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function company()
    {
        return $this->belongsTo(\Emrad\Models\Company::class);
    }

    public function order()
    {
        return $this->hasMany(\Emrad\User::class);
    }

    /**
     * user belongs to offer
     *
     * @return \Emrad\Models\Offer
     */
    public function offers()
    {
        return $this->belongsToMany(\Emrad\Models\Offer::class);
    }

    /**
     * users products
     *
     * @return \Emrad\Models\Product
     */
    public function products()
    {
        return $this->hasMany(\Emrad\Models\Product::class);
    }
}
