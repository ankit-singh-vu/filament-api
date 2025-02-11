<?php

namespace App\Http\Controllers;

use App\Events\CreateBackup;
use App\Jobs\Createbackup as JobsCreatebackup;
use App\Models\Application;
use App\Models\BackupRecord;
use App\Models\Clusters;
use App\Models\Servers;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BackupController extends Controller
{
    public static function takebackup($content)
    {
        $content = request()->post();

        if (!isset($content['uuid'])) {
            return response()->json(['status' => false, "message" => "Please Provide Application uuid"]);
        }
        if (!isset($content['drive'])) {
            return response()->json(['status' => false, "message" => "Please Provide Application drive"]);
        }
        $application = Application::where('uuid', $content['uuid'])->first();

        if (!$application) {
            return response()->json(['status' => false, "message" => "Application Not found"]);
        }
        $cluster_urls = Clusters::where('id', 33)->first();

        if (!$cluster_urls) {
            return response()->json(['status' => false, "message" => "Cluster Not found"]);
        }

        $servers = Servers::where('host_name', $cluster_urls->cluster_name)
            ->where('vm_name', 'not like', '%-c%')
            ->first();

        if (!$servers) {
            return response()->json(['status' => false, "message" => "Server Not found"]);
        }





        $content['server'] = $servers->id;
        $content['platform'] = "Nomad";

        $job_uuid = str::uuid()->toString();
        $createjob = new \App\Models\Job();
        $createjob->uuid = $job_uuid;
        $createjob->data = json_encode($content);
        $createjob->job = "CreateBackup";
        $createjob->save();

        JobsCreatebackup::dispatch(json_encode($content), $createjob->id);

        return response()->json(['status' => true, 'job_id' => $createjob->id, "message" => "Bcakup created success fully"]);

        // return \App\Components\Appdeployer\Nomad\Backup\Createbackup::create($content);
    }
}
