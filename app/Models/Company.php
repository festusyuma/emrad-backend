<?php

namespace Emrad\Models;

use Emrad\Filters\QueryFilter;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    
    public function users()
    {
        return $this->hasMany(\Emrad\User::class);
    }

    /**
     * scope for quering product
     *
     * @param $query
     * @param QueryFilter $filters
     */
    public function scopeFilter($query, QueryFilter $filters)
    {
        return $filters->apply($query);
    }

}
