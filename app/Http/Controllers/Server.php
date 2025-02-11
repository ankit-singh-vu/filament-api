<?php

namespace App\Http\Controllers;

use \App\Models;
use Illuminate\Http\Request;
use Symfony\Component\Translation\Loader\YamlFileLoader;
use Symfony\Component\Yaml\Yaml;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class Server extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        try {
            $cluster_uuid=Str::uuid()->toString();
            
            $request->validate([
            ]);
        
            $file = $request->file('file');
            $path = $file->store('uploads', 'public');


            // $clusters_data= new \App\Models\Servers();
            // $clusters_data->uuid = $cluster_uuid;
            // $clusters_data->cluster_name = $request->cluster_name;
            // $clusters_data->provider = $request->provider;
            // $clusters_data->vm_count = $request->count;
            // $clusters_data->status = 0;
            // $clusters_data->cluster_url = $request->cluster_url;
            // $clusters_data->lb_url=$request->lb_url;
            // $clusters_data->cluster_type = $request->cluster_type;
            // $clusters_data->save();
            $yml_data=Yaml::parseFile(storage_path('app/public/'.$path));
            $server_create_data=[];

            $server_create_data['data']=$yml_data;
            $server_create_data['cluster_id']=$request->cluster_id;
            $server_create_data['vm_name']=$request->vm_name;
            $server_create_data['provider']=$request->provider;
            $server_create_data['host_name']=$request->host_name;

            Log::info("Starting VM creation");
            try {
                $create_server=\App\Models\Servers::create_vm($server_create_data,Log::class);
            } catch (\Throwable $th) {
                //throw $th;
                Log::info(json_encode(["message"=>$th->getMessage(),"line"=>$th->getLine(),"file"=>$th->getFile()]));
            }
            Log::info("End VM creation");
            

            return response()->json(['status'=>true,"message"=>"Cluster Table created Successfully","path_new"=>$path]);
        } catch (\Throwable $th) {
            return response()->json(['status'=>false,"error"=>true,
            "message"=>$th->getMessage(),"error_line"=>$th->getLine(),
            "error_file"=>$th->getFile()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
