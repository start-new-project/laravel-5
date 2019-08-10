<?php

namespace App\Http\Controllers\API;

use App\Models\Client;
use App\Http\Resources\ClientsResources;
use App\Http\Requests\Client\StoreRequest;
use App\Http\Requests\Client\UpdateRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClientsController extends Controller
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
    public function index()
    {
        return ClientsResources::collection(Client::all());
    } 

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $client = new Client();
        $client->name = $request->name;
        $client->phone = ($request->phone)?$request->phone:'';
        $client->phone2 = ($request->phone2)?$request->phone2:'';
        $client->address = ($request->address)?$request->address:'';
        $client->address2 = ($request->address2)?$request->address2:'';
        $client->active = ($request->active)?true:false;
        $client->category = ($request->category)?json_encode($request->category):'';
        $client->by = auth()->id();
        $client->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show(Client $client)
    {
        return new ClientsResources($client);
    } 

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Client $client)
    {
        $client->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        return $client->delete();
    }
}
