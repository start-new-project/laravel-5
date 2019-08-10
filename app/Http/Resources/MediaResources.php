<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MediaResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $by = $this->by();
        return [
            "id" => $this->id,
            "url" => [
                "xs" => $this->url('xs'),
                "sm" => $this->url('sm'),
                "md" => $this->url('md'),
                "lg" => $this->url('lg'),
                "default" => $this->url(),
                "preview" => $this->url('preview'),
            ],
            "by" => [
                "id" => $this->by,
                "fullname" => $by->name,
                "avatar" => [
                    "xs" => $by->avatar('xs'),
                    "sm" => $by->avatar('sm'),
                    "md" => $by->avatar('md'),
                ], 
                "link" => route('admin.profile',$this->by)
            ],
            "created" => [
                "carbon" => $this->created_at->diffForHumans(),
                "time" => $this->created_at
            ]
        ];
    }
}
