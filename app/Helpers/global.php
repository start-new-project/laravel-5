<?php

if(!function_exists('generate_username')){
    function generate_username($name,$i=null){
        $username = str_slug($name,'.');
        if(!empty($i)){
            $username .= '.'.$i;
        }
        $user = App\Models\User::where('username',$username)->first();
        if(!empty($user->id)){
            return generate_username($name,$i++);
        }
        return $username;
    }
}

if(!function_exists('user')){
    function user(){
        return auth()->user();
    }
}