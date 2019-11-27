<?php

namespace Emrad\Models;

use Illuminate\Database\Eloquent\Model;

class RetailerOrder extends Model
{
    public function company()
    {
        return $this->belongsTo(\Emrad\Model\Company::class);
    }

    public function product()
    {
        return $this->belongsTo(\Emrad\Model\Product::class);
    }

}
