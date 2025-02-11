<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Models\Clusters;
use App\Models\Providers;
use App\Models\ClusterType;
use App\Models\Cluster_location;
use App\Models\SCM_Network;
use App\Models\SharedStorageType;
use App\Models\Partners;

class PartnerInfraController extends Controller
{
    public function Infraoverview(Request $request) {

        return view('partner.infrastructureoverview');
    }
    public function Clusterlist(Request $request) {
        $partner_id = userData()->id;
        $chkcluster = Partners::select('clusters')
                            ->where('id',$partner_id)->first();
        $data = array();
        if ($chkcluster->clusters != NULL) {
        $data['all_providers']=Providers::all();
        $data['cluster_type']=ClusterType::all();
        $data['all_locations'] = Cluster_location::all();
        $data['all_scm_network'] = SCM_Network::all();
        $data['all_clusters'] = Clusters::all();
        $data['all_shared_storages'] = SharedStorageType::all();
        return view("partner.clusterlist",["data"=>$data]);
        
        }else{
            
            return view('partner.clusterlist',["data"=>$data]);
        }
    }
    public function Clusterserver(Request $request) {
        
        return view('partner.clusterservers');
    }
}
