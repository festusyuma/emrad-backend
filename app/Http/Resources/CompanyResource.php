<?php

namespace Emrad\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
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
            'companyId' => $this->id,
            'companyName' => $this->name,
            'companyAddress' => $this->address,
            'companyOfficialMail' => $this->official_mail,
            'companyCAC' => $this->cac,
            'createdAt' => $this->created_at,
        ];
    }
}
