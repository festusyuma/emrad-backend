<?php

namespace Emrad\Models;

use Eloquent;
use Emrad\Filters\QueryFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Post
 *
 * @mixin Eloquent
 * @property string $name
 * @property string $sku
 * @property string $description
 * @property int|mixed|null $user_id
 * @property int|mixed|null $category_id
 * @property int $size
 * @property double $price
 * @property double $selling_price
 * @property string $image
 */
class Product extends Model
{

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

    public function category()
    {
        return $this->belongsTo(\Emrad\Models\Category::class);
    }

    public function user()
    {
        return $this->belongsTo(\Emrad\User::class);
    }

}
