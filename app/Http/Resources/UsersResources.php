<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UsersResources extends JsonResource
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
            "id" => $this->id,
            "username" => $this->username,
            "avatar" => [
                "xs" => $this->avatar("xs"),
                "sm" => $this->avatar("sm"),
                "md" => $this->avatar("md"),
                "lg" => $this->avatar("lg"),
                "default" => $this->avatar(),
            ],
            "cover" => [
                "xs" => $this->cover("xs"),
                "sm" => $this->cover("sm"),
                "md" => $this->cover("md"),
                "lg" => $this->cover("lg"),
                "default" => $this->cover(),
            ],
            "fullname" => $this->name,
            "email" => $this->email,
            "birthday" => $this->birthday,
            "active" => $this->active,
            "gender" => $this->gender,
            "role" => $this->role,
            "status" => [
                "text" => $this->status(),
                "color" => $this->status('color'),
            ],
            "links" => [
                "profile" => route('admin.profile',$this->username)
            ],
            "isAdmin" => $this->isAdmin()
        ];
    }
}
