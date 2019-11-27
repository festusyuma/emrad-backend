<?php

namespace Emrad\Models;

use Illuminate\Database\Eloquent\Model;

class RetailerOrder extends Model
{
    public function user()
    {
        return $this->belongsTo(\Emrad\User::class);
    }

}
