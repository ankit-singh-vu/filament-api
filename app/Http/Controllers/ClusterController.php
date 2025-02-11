<?php

namespace App\Http\Controllers;

use App\Jobs\ClusterDeploy;
use App\Jobs\ClusterDestroy;
use App\Models\Clusters;
use Symfony\Component\Yaml\Yaml;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

use \App\Jobs\CreateCluster;
use App\Jobs\ScaleCluster;
use App\Models\ClusterType;
use App\Models\Providers;
use App\Models\VmPackage;

class ClusterController extends Controller
{
    //

     /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $all_job = \App\Models\Clusters::all()->where("status",1);
        return response()->json($all_job);
    }

    public function store(Request $request)
    {


        try {
            $cluster_uuid=Str::uuid()->toString();
            
            $request->validate([
            ]);
        
            // $file = $request->file('file');
            // if(!$file){
            //     return response()->json(["status"=>false,"message"=>"File not found"]);
            // }
            // $path = $file->store('uploads', 'public');


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
            // $old_yml_data=Yaml::parseFile(storage_path('app/public/'.$path));

            //$server_create_data=[];

            $cluster_type=$request->post("type");
            $cluster_name=$request->post("name");
            $vm_count=$request->post("hosting_node_count");
            $cluster_group=$request->post("cluster_group");
            $controller_node = $request->post("controller_node");
            $node_config=$request->post("hosting_node");
            $location=$request->post("location");
            $scm_network=$request->post("scm_network");
            $shared_disk_size = $request->post("shared_disk_size");


            $cluster_check=Clusters::where("cluster_name",$cluster_name)->where("status",CLUSTER_STATUS_RUNNING)
            ->orWhere("status",CLUSTER_STATUS_PROVISIONING)->first();
            if($cluster_check){
                return response()->json(['status'=>false,'error'=>true,"message"=>"Cluster with same name exists. Please Try with diffrent
                cluster name"]);
            }

            $cluster_type_data=ClusterType::where("id",$cluster_type)->first();
            $provider_id=$request->post("provider");
            $provider=Providers::where("id",$provider_id)->first();
            $controller_conf=VmPackage::where("id",$controller_node)->first();
            $node_conf=VmPackage::where("id",$node_config)->first();

            $controller_conf_arr=json_decode($controller_conf->data,true);
            $node_conf_arr=json_decode($node_conf->data,true);

            $yml_data=json_decode($provider->cluster_config,true);
            // print_r($provider->cluster_config);exit;
            Log::info("Starting Cluster creation");
            try {
                $job_uuid=Str::uuid()->toString();
            

                $yml_data['nodes']['count']=$vm_count;
                $yml_data['nodes']['resources']['cpu']=$node_conf_arr['vcpu'];
                $yml_data['nodes']['resources']['memory']=$node_conf_arr['ram']."GB";
                $yml_data['nodes']['resources']['root_disk']=$node_conf_arr['rootDisk']."GB";
                for($numberofstorage=0; $numberofstorage<count($yml_data['nodes']['storage']); $numberofstorage++ ){
                   if($yml_data['nodes']['storage'][$numberofstorage]["source"] == "shared_volume") {
                    $yml_data['nodes']['storage'][$numberofstorage]["size"]=$shared_disk_size."GB";
                   }
                }
                

                $yml_data['controller']['resources']['cpu']=$controller_conf_arr['vcpu'];
                $yml_data['controller']['resources']['memory']=$controller_conf_arr['ram']."GB";
                $yml_data['controller']['resources']['root_disk']=$controller_conf_arr['rootDisk']."GB";


                $yml_data['controller']['resources']['network']=$scm_network;

                $ymltoarraynow = $yml_data; 
                $ymltoarraynow['cluster_group']=strtolower($cluster_group);
                $ymltoarraynow['location']=$location;
                $ymltoarraynow['cluster_name']=$cluster_name;
                $ymltoarraynow['cluster_type']=$cluster_type_data->name;
                $ymltoarraynow['provider']=$provider->provider_name;

                ///// $create_server=\App\Models\Servers::create_vm($server_create_data,Log::class);
                $createjob=new \App\Models\Job();
                $createjob->uuid = $job_uuid;
                $createjob->data = json_encode($ymltoarraynow);
                $createjob->job = "DeployCluster";
                $createjob->save();
            
                ///// $newCluster = new \App\Models\Clusters();
                $job_id = $createjob->id;
                ///// Dispatch the job to the queue
                ///// CreateCluster::dispatch($job_id);
                // $createjob->exec();
                
                ClusterDeploy::dispatch($job_id);

                ///// $createjob->exec();
                $data=[];
                ///// $data=Clusters::create([],$job_id);
                return response()->json(['status'=>true,"message"=>"Cluster Table created Successfully","ymldaa"=>$ymltoarraynow,
                "data"=>$data,"job"=>$createjob,"job_id"=>$job_id]);

            } catch (\Throwable $th) {
                //throw $th;
                Log::info(json_encode(["message"=>$th->getMessage(),"line"=>$th->getLine(),"file"=>$th->getFile()]));
                return response()->json(["message"=>$th->getMessage(),"line"=>$th->getLine(),"file"=>$th->getFile()]);
            }
            Log::info("End Cluster creation");
            

            // return response()->json(['status'=>true,"message"=>"Cluster Table created Successfully","path_new"=>$path]);
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
        $cluster_details=\App\Models\Clusters::where("uuid",$id)->first();
        return response()->json($cluster_details);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(string $id){}
    public static function scale(Request $request)
    {
        
        //
        $cluster=$request->post("cluster");;
        $cluster_details=\App\Models\Clusters::where("uuid",$cluster)->first();
        $data=[];
        $cluster_config_data=$cluster_details->data;
        $cluster_provider = $cluster_details->provider;
        if($request->post('action') == "add"){
            
            $data=json_decode($cluster_config_data,true);
            $data['action']="add";
            $data["provider"]=$cluster_provider;
            $data["cluster"]=$cluster_details->uuid;
            $data["cluster_name"]=$cluster_details->cluster_name;

        }elseif($request->post("action") == "remove"){
            
            $server_uuid=$request->post("server");
            $data=json_decode($cluster_config_data,true);
            $data['action']="remove";
            $data["server"]=$server_uuid;
            $data["cluster"]=$cluster_details->uuid;
        }
        $job_uuid=Str::uuid()->toString();
        $job = new \App\Models\Job();
        $job->uuid = $job_uuid;
        $job->data = json_encode($data);
        $job->job = "ScaleCluster";
        $job->save();
        $job_id = $job->id;
        ScaleCluster::dispatch($job_id);

        return response()->json(['status'=>true,"message"=>"Scalling Cluster Successfully Started","job_id"=>$job_id]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $cluster_uuid)
    {
        //
        try {
            //code...

            $job_uuid=Str::uuid()->toString();
            $createjob=new \App\Models\Job();
            $createjob->uuid = $job_uuid;
            $createjob->data = json_encode(["uuid"=>$cluster_uuid]);
            $createjob->job = "DestroyCluster";
            $createjob->save();    
            // $createjob->exec();
            ClusterDestroy::dispatch($createjob->id);
            return response()->json(['status'=>true,"message"=>"Cluster Deletion Started Successfully",
        "job_id"=>$createjob->id]);

        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['status'=>false,"error"=>true,
            "message"=>$th->getMessage(),"error_line"=>$th->getLine(),
            "error_file"=>$th->getFile()]);
        }
        
        
    }

}
