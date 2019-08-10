<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Models\MediaFiles;

use App\Http\Resources\UsersResources;
use App\Http\Resources\MediaResources;

use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Modules\UploadFiles;

class UsersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(!empty($filter = $request->filter)){
            if($filter == "team")
                $users = User::where('active',true)->whereIn('role',['user','admin'])->get();
            if($filter == "client")
                $users = User::where('active',true)->where('role','client')->get();
            if($filter == "student")
                $users = User::where('active',true)->where('role','student')->get();
        }else{
            $users = User::all();
        }
        return UsersResources::collection($users);
    } 

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    { 
        $this->authorize('create',User::class);
        $user = new User();
        $user->name = $request->name;
        $user->username = generate_username($request->name);
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->active = ($request->active);
        $user->gender = ($request->gender == "1");
        $user->role = $request->role;
        $user->birthday = $request->birthday;
        $user->save(); 
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $this->authorize('view',$user);
        return new UsersResources($user);
    } 

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, User $user)
    {
        $this->authorize('update',$user);
        //type of update
        if(!empty($type = $request->type)){
            if(($type == "avatar")&&!empty($request->avatar)){
                $user->avatar = $request->avatar;
            }
            if(($type == "cover")&&!empty($request->cover)){
                $user->cover = $request->cover;
            }
            $user->save();
        }else{
            $user->name = $request->name; 
            $user->email = $request->email;
            if($request->password)
                $user->password = bcrypt($request->password);
            $user->active = ($request->active);
            $user->gender = ($request->gender == "1");
            $user->role = $request->role;
            $user->birthday = $request->birthday;
            $user->save(); 
        }
        return new UsersResources($user);
        //$user->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $this->authorize('delete',$user);
        $user->delete();
        return response()->json(true);
    } 

    public function upload($user_id,$type,Request $request){
        $user = User::find($user_id); 
        $this->authorize('update',$user);
        if(!empty($type)&&(!empty($user->id))&&(($user->id == auth()->user()->id)OR(auth()->user()->isAdmin()))){ 
            if($type == "avatar"){ 
                $uploadFile = new UploadFiles($request->merge);
                $uploadFile->file = "image";
                $uploadFile->path = "upload/avatar/";
                $uploadFile->type = "avatar";
                $uploadFile->album = "avatar";
                $uploadFile->postedIn = $user_id;
                $uploadFile->resize = true;
                $uploadFile->run();
                if(!empty($uploadFile->model->id))
                    $user->avatar = $uploadFile->model->id;
                    $user->save();

            }
            if($type == "cover"){ 
                $uploadFile = new UploadFiles($request->merge);
                $uploadFile->file = "image";
                $uploadFile->path = "upload/cover/";
                $uploadFile->type = "cover";
                $uploadFile->album = "cover"; 
                $uploadFile->postedIn = $user_id;
                $uploadFile->resize = true;
                $uploadFile->run();
                if(!empty($uploadFile->model->id))
                    $user->cover = $uploadFile->model->id;
                    $user->save();
            }
        } 

    }

    public function albums($user_id,$type=null){  
        if(!empty($type)){
            return MediaResources::collection(MediaFiles::where([
                "deleted" => false,
                "album" => $type.'-'.$user_id,
                "posted_in" => $user_id,
            ])->get());
        }
    }


}
