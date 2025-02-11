<?php

namespace App\Http\Controllers\Compiler;

use App\Http\Controllers\Controller;
use App\Jobs\CreateApp;
use App\Jobs\Createbackup;
use App\Models\AccessToken;
use App\Models\Job;
use Illuminate\Support\Str;
use App\Models\TempApp;
use App\Models\user_profile;

class CompileserviceController extends Controller
{

    /***
     * @desc this function entrypoint function to run import compiler job take file path as argument
     *
     * <code>
     *  $data= Import::run($filepath);
     * </code>
     * @param $args
     * @return array
     */
    public static function run($path, $log_id = 0)
    {
        $access_token = $_GET['access_token'];
        $data = $path->post();
        $path = $path->post();
        $platform = "nomad";
        if (is_array($path)) {
            $platform = $path["platform"];
        }
        $details = TempApp::where('job_uuid', $data["uuid"])->first();
        if ($details) {
            $repotype = $details->repo_type;
            $details = json_decode($details->data, true);
            $deploy = [];
            foreach ($data as $key => $value) {
                if ($key == 'deployment') {
                    continue;
                } elseif ($key == 'uuid') {
                    continue;
                } elseif ($key == 'cluster') {
                    $details['cluster'] = $value;
                    $details["author"]["cluster_name"] = $value;
                } else {
                    $deploy[$key] = $value;
                }
            }
            $details['deployemts'] = $deploy;
            $details['repo_type'] = $repotype;
            $access = AccessToken::where("access_token", $access_token)->first();
            if ($access) {
                $authors = user_profile::where('id', $access->user_id)->first();
                if (empty($authors)) {
                    $respons = [];
                    $respons["resposns"] = "Your Access Token not assosiated with a user please use valid one....";
                    $respons["status"] = false;
                    return $respons;
                } else {
                    $path['user_id'] = $authors->id;
                }
            } else {
                $respons = [];
                $respons["resposns"] = "Unauthorised Access Token";
                $respons["status"] = false;
                return $respons;
            }
        } else {
            $resp["status"] = "ERROR";
            $resp['error'] = "there are some techincal issue please start start the process from the start..";
            return  $resp;
        }
        $job_uuid = str::uuid()->toString();
        $createjob = new \App\Models\Job();
        $createjob->uuid = $job_uuid;
        $createjob->data = json_encode($path);
        $createjob->job = "DeployApplication";
        $createjob->save();

        CreateApp::dispatch(json_encode($path), $createjob->id);

        return response()->json(['status' => true, 'job_id' => $createjob->id, "message" => "Cluster Deletion Started Successfully"]);
    }
}
