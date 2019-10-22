<?php

namespace Emrad\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    public function users()
    {
        return $this->hasMany(\Emrad\User::class);
    }
}
