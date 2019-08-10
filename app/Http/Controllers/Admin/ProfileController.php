<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UsersResources;
use App\Models\User;

class ProfileController extends Controller
{
    public function show($username,Request $request){
        $user = User::where([
            'username' => $username,
            'active' => true
        ])->first();

        if(!empty($user->id)){ 
            return view('admin.profile.show',compact('user'));
        }
        return abort(404);
    }
}
