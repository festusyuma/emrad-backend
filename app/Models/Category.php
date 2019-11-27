<?php

namespace Emrad\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public $table = "categories";

    /**
     * use slug instead of id for route
     *
     * @return void
     */
    // public function getRouteKeyName()
    // {
    //     return 'slug';
    // }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
