<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Clusters;
use App\Models\Servers;
use Illuminate\Http\Request;

class clusterUiController extends Controller
{
    //
    public static function jobs($id,$page){

        $clusters=Clusters::where("id",$id)->first();
        // $allservers=Servers::all()->where("cluster_id",$clusters->uuid);
        $all_active_Servers=Servers::all()->where("cluster_id",$clusters->uuid)->where("status",SERVER_STATUS_RUNNING);
        $all_deleted_Servers=Servers::all()->where("cluster_id",$clusters->uuid)->where("status",SERVER_STATUS_DELETED);
        $all_nodes_resouces=json_decode($clusters->data,true)["nodes"]["resources"];
        return view("admin/cluster/comming-soon",["cluster"=>$clusters,"all_active_servers"=>$all_active_Servers,
        "all_deleted_servers"=>$all_deleted_Servers,"node_resources"=>$all_nodes_resouces,"class"=>$page]);

    }
}
