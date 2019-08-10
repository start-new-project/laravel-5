<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Image;
use File;

class SystemController extends Controller
{
    public function index(){
        return view('admin.system');
    }

    public function store(Request $request){



        $fragment_path = "_system/".auth()->id().'/';
        $upload_path = "_system/";


        $path = public_path($fragment_path . $request->name); 
        $file = $request->file('file');  
        if(!empty($request->merge)){

            //merge data
            $collectData = "";
            
            foreach(glob($path.'/*.fragment') as $item){
                $collectData .= file_get_contents($item);
                unlink($item);
            }
            file_put_contents($fragment_path."/full_".$request->name,$collectData);
            rmdir($path); 
            $ext = '.'.last(explode('.',$request->name));
            $filename = time().auth()->id().md5($request->name).$ext; 
            copy($fragment_path."/full_".$request->name,$upload_path.$filename);
            unlink($fragment_path."/full_".$request->name);

            return response()->json([
                "path" => $upload_path,
                "filename" => $filename,
            ]);
        }else{ 
            //upload fragments
            File::isDirectory($path.'/') or File::makeDirectory($path, 0777, true, true); 
            $fragmentname = $request->name.'-'.$request->index.'.fragment';
            $file->move($path.'/',$fragmentname);  
            return response()->json($request->all());
        }
    }


    public function resize(Request $request){
        $filename = $request->filename;
        $upload_path = $request->path;
        //resize 
        $resizeArray = [
            'xs' => "50",
            'sm' => "100",
            'md' => "250",
            'lg' => "500",
        ];  
        $img = Image::make($upload_path.$filename); 
        foreach($resizeArray as $k=>$size){ 
            $resize_path = $upload_path.$k.'/';
            $img->heighten($size, function ($constraint) {
                $constraint->upsize();
            });
            File::isDirectory($resize_path) or File::makeDirectory($resize_path, 0777, true, true); 
            $img->save($resize_path.$filename);
        } 
        //preview picture 
        $preview_path = $upload_path.'preview/';  
        $img->resize(25, 25); 
        File::isDirectory($preview_path) or File::makeDirectory($preview_path, 0777, true, true); 
        $img->save($preview_path.$filename);
        

        $picture = [
            "path" => $upload_path,
            "filename" => $filename,
            "url" => [
                "original" => asset($upload_path.$filename),
                "preview" => asset($preview_path.$filename),
                "xs" => asset($upload_path.'xs/'.$filename),
                "sm" => asset($upload_path.'sm/'.$filename),
                "md" => asset($upload_path.'md/'.$filename),
                "lg" => asset($upload_path.'lg/'.$filename),
            ]
        ];
        return response()->json($picture);
    }




}
