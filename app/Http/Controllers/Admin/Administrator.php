<?php

namespace App\Http\Controllers\Admin;

// use App\Events\sendClusterjobdata;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Support\Str;
use App\Models\AdminAccessToken;
use App\Models\Application;
use App\Models\CatalogCategory;
use App\Models\Cluster_location;
use App\Models\Clusters;
use App\Models\ClusterType;
use App\Models\GlobalCatalogItem;
use App\Models\PrivateCatalogItem;
use App\Models\Providers;
use App\Models\SCM_Network;
use App\Models\Servers;
use App\Models\SharedStorageType;
use App\Models\TenantCatalogItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class Administrator extends Controller
{
    //
    public static function login(){
        $token = csrf_token();
        return view('admin/login');
    }


    public static function dashboard (){
        $copyjob_files_cmd="cp /var/www/html/app/Components/Orchestration/Nomad/systemjobs/agent.hcl /tmp/38b03620-c025-4a3c-bb31-52c44c78d825/agent.hcl &&
               cp /var/www/html/app/Components/Orchestration/Nomad/systemjobs/kopia.hcl /tmp/38b03620-c025-4a3c-bb31-52c44c78d825/kopia.hcl &&
               cp /var/www/html/app/Components/Orchestration/Nomad/systemjobs/lb-envoy.hcl /tmp/38b03620-c025-4a3c-bb31-52c44c78d825/lb-envoy.hcl &&
               cp /var/www/html/app/Components/Orchestration/Nomad/systemjobs/letsencrypt.hcl /tmp/38b03620-c025-4a3c-bb31-52c44c78d825/letsencrypt.hcl &&
               cp /var/www/html/app/Components/Orchestration/Nomad/systemjobs/netreap.hcl /tmp/38b03620-c025-4a3c-bb31-52c44c78d825/netreap.hcl &&
               cp /var/www/html/app/Components/Orchestration/Nomad/systemjobs/sftpserver.hcl /tmp/38b03620-c025-4a3c-bb31-52c44c78d825/sftpserver.hcl &&
               cp /var/www/html/app/Components/Orchestration/Nomad/systemjobs/sqlite-db.hcl /tmp/38b03620-c025-4a3c-bb31-52c44c78d825/sqlite-db.hcl";
            exec($copyjob_files_cmd);
        if(isset($_COOKIE['admin_access_token'])){
            $cookie_data = $_COOKIE['admin_access_token'];
        }else{
            $cookie_data=false;
        }
       
        if($cookie_data){
            
            $token = AdminAccessToken::where('access_token', $cookie_data)->first();
            if($token){
                return view("admin/dashboard");
            }else{
                return redirect()->route('admin.signin');
            }
        }else{
            return redirect()->route('admin.signin');
        }
        
    }


    public static function infrastructure (){
        if(isset($_COOKIE['admin_access_token'])){
            $cookie_data = $_COOKIE['admin_access_token'];
        }else{
            $cookie_data=false;
        }
       
        if($cookie_data){
            
            $token = AdminAccessToken::where('access_token', $cookie_data)->first();
            if($token){
                $all_providers=Providers::all();
                $cluster_type=ClusterType::all();
                $all_locations = Cluster_location::all();
                $all_scm_network = SCM_Network::all();
                $all_clusters = Clusters::all()->where("status","!=",CLUSTER_STATUS_DELETED)->sortByDesc('id');;
                $all_shared_storages = SharedStorageType::all();
                return view("admin/infrastructure",["all_providers"=>$all_providers,
                "cluster_type"=>$cluster_type,"all_scm_network"=>$all_scm_network
                ,"all_locations"=>$all_locations,"all_shared_storage"=>$all_shared_storages,
            "all_clusters"=>$all_clusters]);
            }else{
                return redirect()->route('admin.signin');
            }
        }else{
            return redirect()->route('admin.signin');
        }
        
    }

    public static function infrastructureDeployCluster($request,$job_id){
        $job=\App\Models\Job::where("id",$job_id)->first();
        $data_to_send=[];
        $whatjob="nojob";
        if($job){
            $jobdata=json_decode($job->data,true);
            if($job->job == "DeployCluster"){
                $whatjob="deploy";
                $cluster_name=$jobdata["cluster_name"];
            }elseif($job->job == "DestroyCluster"){
                $whatjob="destroy";
                $cluster_uuid=$jobdata['uuid'];
                $cluster=\App\Models\Clusters::where("uuid",$cluster_uuid)->first();
                $cluster_name=$cluster->cluster_name;
            }elseif($job->job == "ScaleCluster"){
                
                $cluster_uuid=$jobdata['cluster'];
                $cluster=\App\Models\Clusters::where("uuid",$cluster_uuid)->first();
                $cluster_name=$cluster->cluster_name;
                $whatjob="scale";
            }
            $data_to_send["cluster_name"]=$cluster_name;
            $data_to_send["whatjob"]=$whatjob;
            $data_to_send["status"]=$job->status;
        }else{
            $whatjob="nojob";
        }
        $logfile="/tmp/job/".$job_id.".log";
        if(file_exists($logfile)){
            $job_file_data=file($logfile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        }else{
            $job_file_data=[];
        }
        
        
        return view("admin/clusterjob",["clusterjobID"=>$job_id,"job_data"=>$job_file_data,
    "data_to_send"=>$data_to_send]);

    }
    public static function infrastructureClusterJobs(){
        \App\Events\sendClusterjobdata::dispatch("hello world =>",3);
    }

    public static function infrastructureCluster($id){

        $clusters=Clusters::where("id",$id)->first();
        $server=Servers::all()->where("cluster_id",$clusters->uuid);
        $all_nodes_resouces=json_decode($clusters->data,true)["nodes"]["resources"];
        return view("admin/clusteroverview",["cluster"=>$clusters,"servers"=>$server,
    "node_resources"=>$all_nodes_resouces]);

    }
    public static function infrastructureClusterServers($id){
        $clusters=Clusters::where("id",$id)->first();
        // $allservers=Servers::all()->where("cluster_id",$clusters->uuid);
        $all_active_Servers=Servers::all()->where("cluster_id",$clusters->uuid)->where("status",SERVER_STATUS_RUNNING);
        $all_deleted_Servers=Servers::all()->where("cluster_id",$clusters->uuid)->where("status",SERVER_STATUS_DELETED);
        $all_nodes_resouces=json_decode($clusters->data,true)["nodes"]["resources"];
        return view("admin/clusterservers",["cluster"=>$clusters,"all_active_servers"=>$all_active_Servers,
        "all_deleted_servers"=>$all_deleted_Servers,"node_resources"=>$all_nodes_resouces]);
    }

    public static function applicationlists(){
        $applications_data = Application::all()->where('status',1);
        // print_r($applications_data);exit;
        // return view("admin/clusteroverview",["cluster"=>$applications_data]);
        return view("admin/applist",["applications_data"=>$applications_data]);
        // return view('admin.applist',["applications_data"=>$applications_data]);
    }

    public static function cataloglist()
    {


        
            $tenant_id = 2;
            $globalcatalog = GlobalCatalogItem::all();
            $proccessedCatalog =  array();
            $c = 0;
            foreach ($globalcatalog as $key => $globalcatalogitem) {
                $proccessedCatalog[$c] = $globalcatalogitem;
                $proccessedCatalog[$c]->type = "global";
                $catalog = TenantCatalogItem::all();
    
                if (!empty($catalog)) {
                    $proccessedCatalog[$c]->status = 1; //enabled
                } else {
                    $proccessedCatalog[$c]->status = 0; //disabled
                }
                $c++;
            }
    
            $privatecatalog = PrivateCatalogItem::where('tenant_id',  $tenant_id)->get();
            foreach ($privatecatalog as $key => $privatecatalogitem) {
                $proccessedCatalog[$c] = $privatecatalogitem;
                $proccessedCatalog[$c]->type = "private";
                $catalog = TenantCatalogItem::where('tenant_id',  $tenant_id)
                    ->where('private_catalog_item_id',  $privatecatalogitem->id)
                    ->first();
    
                if (!empty($catalog)) {
                    $proccessedCatalog[$c]->status = 1; //enabled
                } else {
                    $proccessedCatalog[$c]->status = 0; //disabled
                }
                $c++;
            }

            // return response()->json($proccessedCatalog);
            return view('admin/catalog/list', ['catalog' => $proccessedCatalog]);
        


        // $response = array();
        // $services = array();

        // $catalogitems = GlobalCatalogItem::all();
        // if ($catalogitems) {
        //     foreach ($catalogitems as $catalogitem) {
        //         $catalogarr = array();
        //         $catalogarr["name"] = $catalogitem->name;
        //         $catalogarr["imagesrc2"] = $catalogitem->imagesrc2;
        //         $catalogarr["imagesrc"] = $catalogitem->imagesrc;
        //         $catalogarr["src"] = $catalogitem->src;
        //         $catalogarr["description"] = $catalogitem->description;
        //         $catalogarr["category"] = CatalogCategory::find($catalogitem->category);
        //         array_push($services, $catalogarr);
        //     }
        //     $response['services'] = $services;
        //     return response()->json($response);
        // }else{
        //     $response['services'] = $services;
        //     return response()->json($response);
        // }

        // $catalog = TenantCatalogItem::where('tenant_id',  $tenant_id)
        //     ->get();
        
        
        // foreach ($catalog as $key => $value) {
        //     if ($value->global_catalog_item_id != 0) {
        //         $catalogitem = GlobalCatalogItem::find($value->global_catalog_item_id);
        //         if ($catalogitem) {
        //             $catalogarr = array();
        //             $catalogarr["name"] = $catalogitem->name;
        //             $catalogarr["imagesrc2"] = $catalogitem->imagesrc2;
        //             $catalogarr["imagesrc"] = $catalogitem->imagesrc;
        //             $catalogarr["src"] = $catalogitem->src;
        //             $catalogarr["description"] = $catalogitem->description;
        //             $catalogarr["category"] = CatalogCategory::find($catalogitem->category);
        //             array_push($services, $catalogarr);
        //         }
        //     }
        // }
        // foreach ($catalog as $key => $value) {
        //     if ($value->private_catalog_item_id != 0) {
        //         $catalogitem = PrivateCatalogItem::find($value->private_catalog_item_id);
        //         if ($catalogitem) {
        //             $catalogarr = array();
        //             $catalogarr["name"] = $catalogitem->name;
        //             $catalogarr["imagesrc2"] = $catalogitem->imagesrc2;
        //             $catalogarr["imagesrc"] = $catalogitem->imagesrc;
        //             $catalogarr["src"] = $catalogitem->src;
        //             $catalogarr["description"] = $catalogitem->description;
        //             $catalogarr["category"] = CatalogCategory::find($catalogitem->category);
        //             array_push($services, $catalogarr);
        //         }
        //     }
        // }
        // if (!empty($catalog)) {
        //     $response['services'] = $services;
        //     return response()->json($response);
        // } else {
        //     $response = array();
        //     $response["message"] = "Catalog not found";
        //     $response["error"] = true;
        //     return response()->json($response, 404);  //401 Not Found
        // }
    }
   
    public static function partners(){
        return view("admin/cooming-soon-main");
    }
    public static function customers(){
        return view("admin/cooming-soon-main");
    }
    public static function monitoring(){
        return view("admin/cooming-soon-main");
    }
    public static function catalog(){
        return view("admin/cooming-soon-main");
    }
    
    public static function settings(){
        return view("admin/cooming-soon-main");
    }


}
