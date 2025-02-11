<?php

namespace App\Http\Controllers;

use App\Jobs\CreateCluster;
use Illuminate\Http\Request;

class Jobrun extends Controller
{
    //
    public function show(Request $request){

        $job_id=$request->get("id");
        $job_db=\App\Models\Job::where("id",$job_id)->first();
        if($job_db){
            if($job_db->status==1){
                return response()->json(["status"=>"complete"]);
    
            }elseif($job_db->status == 0){
                return response()->json(["status"=>"running"]);
    
            }elseif($job_db->status == 2){
                return response()->json(["status"=>"failed"]);
            }
        }else{
            return response()->json(["status"=>"not found"]);
        }
        
    }
   
}
