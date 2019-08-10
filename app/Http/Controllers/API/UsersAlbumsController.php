<?php

namespace App\Http\Controllers\API;

use App\Models\MediaFiles;
use App\Modules\UploadFiles;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Resources\MediaResources;

class UsersAlbumsController extends Controller
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
        if(!empty($type = $request->type)&&!empty($by = $request->by)){
            $list = [];
            $albums = MediaFiles::where([
                                    'deleted' => false,
                                    'file' => 'image',
                                    'posted_in' => $by
                                ])
                                ->distinct()->get('album')->toArray(); 
            foreach($albums as $k=>$item){
                $pictures = MediaResources::collection(MediaFiles::where('deleted',false)
                                        ->where([
                                            'album' => $item['album'],
                                            'posted_in' => $by,
                                            'deleted' => false
                                        ])
                                        ->get());
                $list[$k] = [
                    "id"       => $k,
                    "type"     => $item['album'],
                    "count"    => count($pictures),
                    "pictures" => $pictures,
                    "preview"  => $pictures[0]
                ];
            }
        } 
        //return $list;
        return response()->json($list);
    } 

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!empty($type = $request->type)){
            if($type == "template"){ 
                $uploadFile = new UploadFiles($request->merge);
                $uploadFile->file = "template";
                $uploadFile->path = "upload/template/"; 
                $uploadFile->album = "album-template-".time().rand(1,100);
                $uploadFile->resize = true;
                $uploadFile->by = auth()->id();
                $uploadFile->run();
                if(!empty($uploadFile->model->id))
                    return response()->json($uploadFile->model);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MediaFiles  $mediaFiles
     * @return \Illuminate\Http\Response
     */
    public function show(MediaFiles $mediaFiles)
    {
        //
    } 

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MediaFiles  $mediaFiles
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MediaFiles $mediaFiles)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MediaFiles  $mediaFiles
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $file = MediaFiles::find($id);
        /*$mediaFiles->deleted = true;
        $mediaFiles->save();*/ 
        $file->delete();
    } 


}
