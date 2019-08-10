<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens,Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];  

    public function cover($size="xs"){
        //RQ: contenue fixed default cover ...
        $roles = ['user','admin','client','student'];
        $sizes = ['xs','sm','md','lg'];
        $size = (in_array($s,$sizes))?$s:"xs";
        $type = (in_array($this->role, $roles))?$role:'user';
        $link = ($size != 'lg')? $type.'-'.$size:$type;
        if(!empty($this->cover)){
            $picture = MediaFiles::find($this->cover);
            if(!empty($picture->id)){
                return $picture->url($size);
            }
        }
        return asset("images/default/cover/".$link.".png");
    }

    public function avatar($s="xs"){
        $gender = ['women','men'];
        $sizes = ['xs','sm','md','lg'];
        $size = (in_array($s,$sizes))?$s:"xs";
        $link = $gender[$this->gender];
        $link .= ($size != 'lg')?'-'.$size:'';
        if(!empty($this->avatar)){
            $picture = MediaFiles::find($this->avatar);
            if(!empty($picture->id)){
                return $picture->url($size);
            }
        }
        return asset("images/default/avatar/".$link.".png");
    }

    public function status($type='text'){
        if($type == "color")
            return ($this->active)?'success':'light';
        return ($this->active)?trans('lib.active'):trans('lib.deactive');
    }

    public function isAdmin(){
        return ($this->role == 'admin')?true:false;
    }

    public function isActive(){
        return ($this->active);
    } 

    public function isBlocked(){
        return (!$this->active);
    }

    public function isSuperAdmin(){
        return ($this->id == 1);
    } 

    public function haveAccess($accsess){
        if($this->isAdmin()){
            return true;
        }
        $userAccess = (array)json_decode($this->access);
        return (in_array($accsess,$userAccess));
    }

    public function canUpload(){
        return ((user()->isAdmin())OR(user()->id == $this->id));
    }





}
