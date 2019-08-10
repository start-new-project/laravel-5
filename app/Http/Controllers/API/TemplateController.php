<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\TemplateResources;
use App\Http\Requests\Template\StoreRequest;
use App\Http\Requests\Template\UpdateRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
use App\Models\MediaFiles;
use App\Models\Template;
use File;
use Image;
use Zip;

class TemplateController extends Controller
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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return TemplateResources::collection(Template::orderBy('id','desc')->get());
    } 

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {  
        //vars
        $path = "upload/albums/";
        if(!empty($step = $request->step)){
            //make template
            if(($step == "template")&&(!empty($template = $request->template))){
                $key = auth()->id().time();
                $cache_path = $path.$key.'/cache/';
                File::isDirectory($cache_path) or File::makeDirectory($cache_path, 0777, true, true); 
                $templateFile = MediaFiles::find($template);  
                $templateName = 'template.png';
                $FileTemplate = Image::make($templateFile->defaultUrl());
                $FileTemplate->resize(1200,800);
                $FileTemplate->save($cache_path.$templateName); 
                return response()->json([
                    "key" => $key
                ]);
            }
            //make pictures
            if(($step == "pictures")
                &&(!empty($picture = $request->picture))
                &&(!empty($key = $request->key))
                &&(!empty($index = $request->index))){ 
                $cache_path = $path.$key.'/cache/';
                File::isDirectory($cache_path) or File::makeDirectory($cache_path, 0777, true, true); 
                $pictureFile = MediaFiles::find($picture);  
                $pictureName = $index.'_picture.png';
                $FilePicture = Image::make($pictureFile->defaultUrl());
                $FilePicture->resize(1200,800);
                $FilePicture->save($cache_path.$pictureName); 
            }
            //make album
            if(($step == "album") 
                &&(!empty($key = $request->key))
                &&(!empty($index = $request->index))){ 
                $cache_path = $path.$key.'/cache/';
                $album_path = $path.$key.'/album/'; 
                $album_min_path = $path.$key.'/album/minify/'; 
                $preview_path = $path.$key.'/preview/'; 
                File::isDirectory($album_path) or File::makeDirectory($album_path, 0777, true, true); 
                File::isDirectory($album_min_path) or File::makeDirectory($album_min_path, 0777, true, true); 
                File::isDirectory($cache_path) or File::makeDirectory($cache_path, 0777, true, true); 
                File::isDirectory($preview_path) or File::makeDirectory($preview_path, 0777, true, true); 
                $filename = time().$index.'.png';
                //insert template in picture 
                $NewFile = Image::make($cache_path.$index.'_picture.png');
                $NewFile->insert($cache_path.'template.png');
                $NewFile->save($album_path.$filename); 
                //resize
                $previewFile = Image::make($album_path.$filename);
                $previewFile->resize(100,100);
                $previewFile->save($preview_path.$filename); 
                //minify picture 
                $minifyFile = Image::make($album_path.$filename);
                $minifyFile->resize(300,200);
                $minifyFile->save($album_min_path.$filename);

            }
            //zip album
            if(($step == "zip") 
                &&(!empty($key = $request->key))
                &&(!empty($name = $request->name))){  
                $cache_path = $path.$key.'/cache/';
                $album_path = $path.$key.'/album/';  
                $album_min_path = $path.$key.'/album/minify/'; 
                $files = glob($album_path.'*.png'); 
                $minifyFiles = glob($album_min_path.'*.png'); 
                $zip = Zip::create($path.$key.'/'.str_slug($name).'.zip');
                $zip->add($files); 
                $zipMinify = Zip::create($path.$key.'/'.str_slug($name).'-min.zip');
                $zipMinify->add($minifyFiles);  
                //clear cache
                foreach (glob($cache_path.'*.png') as $key => $file) {
                    unlink($file);
                }
                rmdir($cache_path);
            }
            //save album
            if(($step == "save") 
                &&(!empty($key = $request->key))
                &&(!empty($name = $request->name))
                &&(!empty($template = $request->template))
                &&(!empty($picturesList = $request->pictures))
                &&(!empty($page = $request->page))
                ){   
                $pictures = []; 
                foreach ($picturesList as $item) {
                    $pictures[] = $item['id'];
                } 
                $newTemp = new Template();
                $newTemp->name = $name;
                $newTemp->page_id = $page;
                $newTemp->template_id = $template;
                $newTemp->album = json_encode($pictures);
                $newTemp->by = auth()->id();
                $newTemp->key = $key;
                $newTemp->save();
                return new TemplateResources($newTemp); 
            }
        }   
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Template  $template
     * @return \Illuminate\Http\Response
     */
    public function show(Template $template)
    {
        return new TemplateResources($template);
    } 

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Template  $template
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Template $template)
    {
        $template->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Template  $template
     * @return \Illuminate\Http\Response
     */
    public function destroy(Template $template)
    {
        return $template->delete();
    }
}
