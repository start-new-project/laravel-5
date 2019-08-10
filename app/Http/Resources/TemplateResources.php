<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TemplateResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request); 
        $pictures = $this->pictures();
        $album = $this->album();
        $preview = $this->preview();
        $previewLinks = $this->preview(true);
        return [
            "id" => $this->id, 
            "name" => $this->name, 
            "key" => $this->key, 
            "page" => $this->page(), 
            "template" => $this->template(), 
            "pictures" => [
                "count" => count($pictures),
                "list" => $pictures
            ],  
            "preview" => [ 
                'first' => (count($preview)>1)?asset($preview[0]):'',
                'list' => $preview,
                'links' => $previewLinks,
            ],  
            "album" => [
                'count' => count($album),
                'first' => (count($album)>1)?asset($album[0]):'',
                'list' => $album,
                'links' => $this->album(true),
            ],     
            "created" => [
                "carbon" => $this->created_at->diffForHumans(),
                "time" => $this->created_at
            ],
            "updated" => [
                "carbon" => $this->updated_at->diffForHumans(),
                "time" => $this->updated_at
            ],
            "by" => $this->by(),
            "download" => [
                "zip" => $this->download(),
                "min" => $this->download(true)
            ]
        ];
    }
}
