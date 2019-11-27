<?php

namespace Emrad\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'categoryId' => $this->id,
            'categoryName' => $this->name,
            'categorySlug' => $this->slug,
            'categoryDescription' => $this->description,
            'categoryLogo' => $this->logo,
            'createdAt' => $this->created_at,
        ];
    }
}
