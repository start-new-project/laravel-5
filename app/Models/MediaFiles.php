<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class MediaFiles extends Model
{
    public function url($size=null){
        $list = ['xs','sm','md','lg','preview'];
        if(!empty($size)&&(in_array($size,$list))&&($this->resize)){
            return asset($this->path.'/'.$size.'/'.$this->filename);
        }
        return asset($this->path.'/'.$this->filename);
    } 
    
    public function defaultUrl($size=null){
        return $this->path.'/'.$this->filename;
    } 

    public function by(){
        return User::find($this->by);
    }


}
