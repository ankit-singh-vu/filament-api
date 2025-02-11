<?php

namespace App\Http\Controllers;

use App\Jobs\DeleteApp as JobsDeleteApp;
use Illuminate\Support\Str;

use App\Models\Application;
use Illuminate\Http\Request;

class deleteapp extends Controller
{
    public static function delete($arg)
    {
        $data = $arg->post();

        if (isset($data['uuid'])) {
            $all_param = [];
            $application = Application::where('uuid', $data['uuid'])->first();
            if ($application) {
                $all_param['uuid'] = $application->uuid;
                $all_param['platform'] = $application->platform;
                $job_uuid = str::uuid()->toString();
                $createjob = new \App\Models\Job();
                $createjob->uuid = $job_uuid;
                $createjob->data = json_encode($all_param);
                $createjob->job = "DeleteApplication";
                $createjob->save();
                JobsDeleteApp::dispatch(json_encode($all_param), $createjob->id);
            } else {
                return response()->json(['status' => false, "message" => "Site Not Found."]);
            }
        } else {
            return response()->json(['status' => false, "message" => "parameter UUID Not Found."]);
        }

        return response()->json(['status' => true, 'job_id' => $createjob->id, "message" => "Application Deletetion Started Successfully"]);
    }
}
