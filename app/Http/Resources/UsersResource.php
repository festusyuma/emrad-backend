<?php

namespace Emrad\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UsersResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'userId' => $this->id,
            'userFirstName' => $this->first_name,
            'userLastName' => $this->last_name,
            'userGender' => $this->gender,
            'userPhoneNumber' => $this->phone_number,
            'userEmail' => $this->email,
            'userAvater' => $this->avater,
            'userAddress' => $this->address,
            'userPermissions' => $this->permissions,
            'createdAt' => $this->created_at,
            'userCompany' => new CompanyResource($this->company),
            'roles' => RolesResource::collection($this->roles),
            'permissions' => PermissionsResource::collection($this->permissions)
        ];
    }
}
