<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
use App\Modules\UploadFiles;

use App\Models\User;


use File;
use Image;

class DashboardController extends Controller
{
    public function index(){
        return view('admin.dashboard');
    }
    public function users(){
        $this->authorize('create',User::class);
        return view('admin.users');
    }
    public function clients(){
        //$this->authorize('create',User::class);
        return view('admin.clients');
    }
    public function pages(){
        //$this->authorize('create',User::class);
        return view('admin.pages');
    }
    public function posts(){
        //$this->authorize('create',User::class);
        return view('admin.posts');
    }
    public function uploads(){
        //$this->authorize('create',User::class);
        return view('admin.uploads');
    }
    public function api(){
        return abort('200');
    }
    public function showUpload(){
        return view('admin.upload');
    }
    public function upload(Request $request){ 
        return response()->json($request->all());
        /*
        $uploadFile = new UploadFiles($request->merge);
        $uploadFile->file = "image";
        //$uploadFile->path = "upload/avatar/";
        //$uploadFile->type = "avatar";
        $uploadFile->resize = true;
        $uploadFile->run();
        */
    }




    public function minify(Request $request){ 
        ini_set('max_execution_time', 0);
        $directory = 'app_minify/folder/';
        $files = glob($directory."*.png");
        if(!empty($request->start)){ 
            ini_set('memory_limit','256M');
            $ext = (!empty($request->ext))?$request->ext:'png';
            $size = (!empty($request->size))?$request->size:'500';
            $path= "app_minify/folder/minify".time()."/";
            foreach($files as $file){
                $img = Image::make($file);  
                $img->heighten($size, function ($constraint) {
                    $constraint->upsize();
                }); 
                File::isDirectory($path) or File::makeDirectory($path, 0777, true, true); 
                $img->save($path.time().'.'.$ext); 
            }
            echo "Done !";
            return redirect()->route('minify');
        } 
        return view('minify',compact('files'));
    } 










}
