<?php

namespace App\Modules; 

use File;
use Image;

use App\Models\MediaFiles;

class UploadFiles
{ 
 
    public $merge = false;
    public $path = 'upload/';
    public $type = 'default';
    public $album = 'default';
    public $postedIn = null;
    public $filename = "";
    public $by;
    public $resize = true;
    public $file = "image";
    public $saveInDatabase = true;
    public $model = null;

    public function __construct($merge=false){
        $this->merge = ($merge == true)?true:false;
        $listType = ['default','avatar','cover'];
        $listFile = ['image','video','pdf','template'];
        $this->type = (in_array($this->type,array_keys($listType)))?$this->type:'default'; 
        $this->file = (in_array($this->file,array_keys($listFile)))?$this->file:'image'; 
    } 

    public function run(){
        $this->upload($this->merge);
    }

    private function upload($merge=false){
        if($merge){
            $this->mergeFragmentFile();
            if($this->resize)
                $this->resizeFile();
            if($this->saveInDatabase)
                $this->database();
        }else{ 
            $this->fragmentFile();
        }
    }

    private function fragmentFile(){

        $upload_path = $this->path;

        $filename   = $_SERVER['HTTP_X_FILE_NAME']; 
        $filesize   = $_SERVER['HTTP_X_FILE_SIZE'];
        $index      = $_SERVER['HTTP_X_INDEX'];
        // name must be in proper format
        if (!isset($_SERVER['HTTP_X_FILE_NAME'])) {
            throw new Exception('Name required');
        }
        if (!preg_match('/^[-a-z0-9_][-a-z0-9_.]*$/i', $_SERVER['HTTP_X_FILE_NAME'])) {
            throw new Exception('Name error');
        }
        // index must be set, and number
        if (!isset($_SERVER['HTTP_X_INDEX'])) {
            throw new Exception('Index required');
        }
        if (!preg_match('/^[0-9]+$/', $_SERVER['HTTP_X_INDEX'])) {
            throw new Exception('Index error');
        }
        // we store chunks in directory named after filename
        $path = public_path($upload_path . $filename .'/');
        File::isDirectory($path) or File::makeDirectory($path, 0777, true, true); 
        $target = public_path($upload_path . $filename . '/' . $filename . '-' . $index); 
        $input = fopen("php://input", "r");
        file_put_contents($target, $input);
    }

    private function mergeFragmentFile(){
        $upload_path = $this->path;
        // name must be in proper format
        if (!isset($_REQUEST['name'])) {
            throw new Exception('Name required');
        }
        if (!preg_match('/^[-a-z0-9_][-a-z0-9_.]*$/i', $_REQUEST['name'])) {
            throw new Exception('Name error');
        }
        // index must be set, and number
        if (!isset($_REQUEST['index'])) {
            throw new Exception('Index required');
        }
        if (!preg_match('/^[0-9]+$/', $_REQUEST['index'])) {
            throw new Exception('Index error');
        } 
        $rename = $this->renameFile($_REQUEST['name']);
        $target = $upload_path."/full_" . $_REQUEST['name'];
        $dst = fopen($target, 'wb');
        for ($i = 0; $i < $_REQUEST['index']; $i++) {
            $slice = $upload_path.'/' . $_REQUEST['name'] . '/' . $_REQUEST['name'] . '-' . $i;
            $src = fopen($slice, 'rb');
            stream_copy_to_stream($src, $dst);
            fclose($src);
            unlink($slice);
        }
        fclose($dst);
        rmdir($upload_path . $_REQUEST['name']);
        copy($upload_path."full_" . $_REQUEST['name'], $upload_path . $rename);
        unlink($upload_path."full_" . $_REQUEST['name']);
    }

    private function renameFile($name){
        $exts = ['jpg','png'];
        $newname = time().md5($name); 
        $ext = last(explode('.',$name));  
        $_name = $newname.'.'.$ext;
        $this->filename = $_name;
        return $_name;
    }

    private function resizeFile(){
        $file = $this->path.$this->filename;

        $resizeArray = $this->resizeArray();

        foreach($resizeArray as $k=>$size){ 
            $path = $this->path.$k.'/';
            $img = Image::make($file);
            //default
            if($this->type == "default"){
                $img->heighten($size, function ($constraint) {
                    $constraint->upsize();
                });
            }
            //avatar
            if($this->type == "avatar"){
                $img->resize($size, $size);
            }
            //cover
            if($this->type == "cover"){
                $img->heighten($size, function ($constraint) {
                    $constraint->upsize();
                });
            }
            File::isDirectory($path) or File::makeDirectory($path, 0777, true, true); 
            $img->save($path.$this->filename);
        } 
        //preview picture 
        $path = $this->path.'preview/';
        $img = Image::make($file);
        $img->crop(250, 250); 
        File::isDirectory($path) or File::makeDirectory($path, 0777, true, true); 
        $img->save($path.$this->filename);
    }

    private function resizeArray(){

        //types: avatar, cover, default
        $list = [
            'default' => [
                'xs' => "50",
                'sm' => "100",
                'md' => "250",
                'lg' => "500",
            ],
            'avatar' => [
                'xs' => "25",
                'sm' => "100",
                'md' => "250",
                'lg' => "500",
            ],
            'cover' => [
                'xs' => "100",
                'sm' => "200",
                'md' => "400",
                'lg' => "800",
            ],
        ];

        
        return $list[$this->type];

    }

    private function database(){
        $media = new MediaFiles();
        $media->type = $this->type;
        $media->file = $this->file;
        $media->path = $this->path;
        $media->album = $this->album;
        $media->filename = $this->filename;
        $media->resize = $this->resize;
        $media->by = (!empty($this->by))?$this->by:auth()->id();
        if(!empty($this->postedIn))
            $media->posted_in = $this->postedIn;
        $media->save();
        $this->model = $media;
    }


}