<?php

namespace App\Http\Controllers\API\Upload;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use File;
use Image;
use App\Models\MediaFiles;
use App\Http\Resources\MediaResources;

class ImagesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
        ini_set('memory_limit','256M');
        ini_set('max_execution_time', 0);
    }

    public $upload_path = "upload/images/";

    public function upload(Request $request){ 
        
        $fragment_path = $this->upload_path.auth()->id().'/';
        $path = public_path($fragment_path . $request->name); 
        $file = $request->file('file'); 
        File::isDirectory($path.'/') or File::makeDirectory($path, 0777, true, true); 
        $fragmentname = $request->name.'-'.$request->index.'.fragment';
        $file->move($path.'/',$fragmentname);  
        return response()->json($request->all());
    }

    public function merge(Request $request){ 
        
        $collectData = ""; 
        $fragment_path = $this->upload_path.auth()->id().'/';
        $path = public_path($fragment_path . $request->name); 
        foreach(glob($path.'/*.fragment') as $item){
            $collectData .= file_get_contents($item);
            unlink($item);
        }
        file_put_contents($fragment_path."/full_".$request->name,$collectData);
        rmdir($path); 
        $ext = '.'.last(explode('.',$request->name));
        $filename = time().auth()->id().md5($request->name).$ext; 
        copy($fragment_path."/full_".$request->name,$this->upload_path.$filename);
        unlink($fragment_path."/full_".$request->name);

        return response()->json([
            "path" => $this->upload_path,
            "filename" => $filename,
        ]);
    }

    public function resize(Request $request){ 
        
        $filename = $request->filename;
        $upload_path = $request->path;
        //resize 
        $resizeArray = [
            'xs' => 200,
            'sm' => 400,
            'md' => 600,
            'lg' => 800,
        ];  
        foreach($resizeArray as $k=>$size){ 
            $img = Image::make($upload_path.$filename); 
            $resize_path = $upload_path.$k.'/';
            $img->heighten($size, function ($constraint) {
                $constraint->upsize();
            });
            File::isDirectory($resize_path) or File::makeDirectory($resize_path, 0777, true, true); 
            $img->save($resize_path.$filename);
        } 
        //preview picture 
        $preview_path = $upload_path.'preview/';  
        $img = Image::make($upload_path.$filename); 
        $img->resize(100, 100); 
        File::isDirectory($preview_path) or File::makeDirectory($preview_path, 0777, true, true); 
        $img->save($preview_path.$filename);

        //save template
        $image = new MediaFiles(); 
        $image->path = $upload_path;
        $image->filename = $filename;
        $image->resize = true;
        $image->by = auth()->id();
        $image->save();

        return new MediaResources($image);

    }
}
