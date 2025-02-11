<?php

namespace App\Http\Controllers;

use App\Models\AccessToken;
use App\Models\Application;
use App\Models\BackupPolicy;
use App\Models\BackupRecord;
use App\Models\Clusters;
use App\Models\Job;
use App\Models\SftpEnabledSite;
use DateTime;
use Illuminate\Http\Request;
use Mockery\Undefined;

use function Laravel\Prompts\form;

class UsercommonapiController extends Controller
{
    public static function show_site($args)
    {
        $cluster = '';
        if (!isset($args['cluster'])) {
            $cluster = "all";
        } else {
            $cluster = $args['cluster'];
        }
        $access = AccessToken::where('access_token', $args['access_token'])->first();

        if (empty($access)) {
            $arr = [];
            $arr['error'] = true;
            $arr['response'] = "no running sites found on servers.....";
            return $arr;
        }

        $arr = [];
        $tmp = [];
        if ($cluster === "all") {
            $data = Application::where('user_id', $access['user_id'])
                // ->where('user_id', $access['user_id'])
                ->get();
            if (!empty($data[0])) {
                $index = 1;
                foreach ($data as $x) {
                    $site = Application::where('uuid', $x->uuid)->first();
                    $cluster_urls = Clusters::where('id', $site->cluster)->first();
                    $SftpEnabledsites = SftpEnabledSite::where('application_uuid', $x->uuid)->first();
                    $tmp[$index]['uuid'] = $x->uuid;
                    $tmp[$index]['app_name'] = isset($x->name) ? $x->name : 'Undefined-' . $index;
                    $tmp[$index]['platforn'] = isset($x->platform) ? $x->platform : 'Undefined';
                    $tmp[$index]['id'] = $x->id;
                    $tmp[$index]['cluster_name'] = $cluster_urls->cluster_name;
                    $tmp[$index]['container_count'] =  self::getContainers($x->uuid, $cluster_urls->cluster_url);
                    // $tmp[$index]['total_container'] =  count(json_decode($x->services, true));
                    $tmp[$index]['app_url'] = $x->domain;
                    $tmp[$index]['up_time'] = self::timeElapsed($x->createdAt);
                    if (!empty($SftpEnabledsites)) {
                        $tmp[$index]['sftp'] = true;
                    } else {
                        $tmp[$index]['sftp'] = false;
                    }
                    $tmp[$index]['origin-image'] = $x->origin;
                    $tmp[$index]['origin-url'] = $x->origin_url;
                    $tmp[$index]['status'] = $x->status == null ? "active" : $x->status;
                    $index++;
                }
                $arr['status'] = true;
                $arr['response'] = $tmp;
            } else {
                $arr['response'] = "no running sites found for this user";
                $arr['count'] = 0;
            }
        } else {
            $cluster_id = Clusters::where('cluster_name', $cluster)->first();
            if (isset($cluster_id)) {
                $data = Application::where('user_id', $access['user_id'])
                    ->where('cluster', $cluster_id->id)
                    // ->where('user_id', $access['user_id'])
                    ->get();
                if (!empty($data[0])) {
                    $index = 1;
                    foreach ($data as $x) {
                        $site = Application::where('uuid', $x->uuid)->first();
                        $cluster_urls = Clusters::where('id', $site->cluster)->first();
                        $SftpEnabledsites = SftpEnabledSite::where('application_uuid', $x->uuid)->first();
                        $arr[$index]['uuid'] = $x->uuid;
                        $arr[$index]['app_name'] = isset($x->name) ? $x->name : 'Undefined-' . $index;
                        $arr[$index]['platforn'] = isset($x->platform) ? $x->platform : 'Undefined';
                        $arr[$index]['id'] = $x->id;
                        $arr[$index]['cluster_name'] = $cluster_urls->cluster_name;
                        $arr[$index]['app_url'] = $x->domain;
                        $arr[$index]['up_time'] = $x->createdAt;
                        if (!empty($SftpEnabledsites)) {
                            $arr[$index]['sftp'] = true;
                        } else {
                            $arr[$index]['sftp'] = false;
                        }
                        $arr[$index]['db_url'] = 'phpmyadmin.' . $x->uuid . '.' . $cluster_urls->lb_url;
                        $arr[$index]['origin-image'] = $x->origin;
                        $arr[$index]['origin-url'] = $x->origin_url;
                        $arr[$index]['status'] = $x->status === null ? "active" : $x->status;
                        $index++;
                    }
                } else {
                    $arr['response'] = "no running sites found on servers.....";
                }
            } else {
                $arr['response'] = "NO ClUSTER FOUND WITH THE NAME " . $args;
            }
        }
        return $arr;
    }


    public static function timeElapsed($databaseDate)
    {
        // Current date
        $currentDate = new DateTime();

        // Date from the database as a DateTime object
        $dbDate = new DateTime($databaseDate);

        // Calculate the difference
        $interval = $currentDate->diff($dbDate);

        // Format the difference
        $elapsed = '';
        if ($interval->y > 0) {
            $elapsed .= $interval->y . ' years ';
        }
        if ($interval->m > 0) {
            $elapsed .= $interval->m . ' months ';
        }
        if ($interval->d > 0) {
            $elapsed .= $interval->d . ' days ';
        }
        if ($interval->h > 0) {
            $elapsed .= $interval->h . ' hours ';
        }
        // if ($interval->i > 0) {
        //     $elapsed .= $interval->i . ' minutes ';
        // }
        // if ($interval->s > 0) {
        //     $elapsed .= $interval->s . ' seconds ';
        // }

        return $elapsed;
    }

    public static function getJob($id)
    {

        $Job_details = Job::where('id', $id->id)->first();
        if ($Job_details) {
            $status = 'Running';
            $uuid = null;
            if ($Job_details->status === 1) {
                $status = 'Done';
            } elseif ($Job_details->status === 2) {
                $status = 'Faild';
            }
            $Data = json_decode($Job_details->data, true);
            if (isset($Data['uuid'])) {
                $uuid = $Data['uuid'];
            }
            $filename = '/tmp/job/' . $id->id . '.log';
            if (file_exists($filename)) {
                return response()->json(['started_at' => $Job_details->created_at, 'status' => $status, 'job_id' => $Job_details->uuid, "app_uuid" =>  $uuid, "log" => file_get_contents($filename)]);
                // return  file_get_contents($filename);
            } else {
                return "Undefined Job...";
            }
        }
    }

    public static function getContainers($uuid, $url)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url . '/v1/job/' . $uuid,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        if ($response == 'job not found') {
            return "Not an running app job";
        }
        $data = json_decode($response, true);


        $totalCount = 0;
        $serviceCounts = [];

        foreach ($data['TaskGroups'] as $taskGroup) {
            $name = $taskGroup['Name'];
            $count = $taskGroup['Count'];

            $totalCount += $count;
            $serviceCounts[$name] = $count;
        }

        return [
            'total_count' => $totalCount,
            'service_counts' => $serviceCounts
        ];
    }

    public static function scaleup($args)
    {

        if (!isset($args['count'])) {
            return response()->json(['status' => false, "message" => "Please Provide Count"]);
        }
        if (!isset($args['uuid'])) {
            return response()->json(['status' => false, "message" => "Please Provide uuid"]);
        }
        if (!isset($args['group'])) {
            return response()->json(['status' => false, "message" => "Please Provide Group"]);
        }
        $application = Application::where('uuid', $args['uuid'])->first();

        if (!$application) {
            return response()->json(['status' => false, "message" => "Application Not found"]);
        }

        $cluster_urls = Clusters::where('id', $application->cluster)->first();

        if (!$cluster_urls) {
            return response()->json(['status' => false, "message" => "Application Not found"]);
        }

        $data = [
            "Count" => (int)$args['count'],
            "Target" => [
                "Group" => $args['group']
            ]
        ];

        // Convert the array to a JSON string
        $jsonString = json_encode($data, JSON_PRETTY_PRINT);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL =>  $cluster_urls->cluster_url . '/v1/job/' . $application->uuid . '/scale',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $jsonString,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $scale = json_decode($response, true);
        $is_json = (json_last_error() === JSON_ERROR_NONE);
        if ($is_json) {
            if ($scale["EvalID"]) {
                return response()->json(['status' => true, "message" => "Application " . $args['group'] . " Scale to " . (int)$args['count']]);
            } else {
                return response()->json(['status' => false, "message" => $response]);
            }
        } else {
            return response()->json(['status' => false, "message" => $response]);
        }
    }
    public static function getcondata($args)
    {
        if (!isset($args['uuid'])) {
            return response()->json(['status' => false, "message" => "Please Provide uuid"]);
        }

        $application = Application::where('uuid', $args['uuid'])->first();

        if (!$application) {
            return response()->json(['status' => false, "message" => "Application Not found"]);
        }

        $cluster_urls = Clusters::where('id', $application->cluster)->first();

        if (!$cluster_urls) {
            return response()->json(['status' => false, "message" => "Application Not found"]);
        }

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $cluster_urls->cluster_url . '/v1/job/' . $application->uuid,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        if ($response == 'job not found') {
            return response()->json(['status' => false, "message" => "Not an running app job"]);
        }
        $data = json_decode($response, true);
        // return $data;

        $totalCount = 0;
        $serviceCounts = [];

        foreach ($data['TaskGroups'] as $taskGroup) {
            $name = $taskGroup['Name'];
            $count = $taskGroup['Count'];

            $totalCount += $count;
            for ($i = 1; $i < $count + 1; $i++) {
                $serviceCounts[$name][$name . '-' . $i]['ip'] = self::generateFalseIP();
                $serviceCounts[$name][$name . '-' . $i]['cpu'] = rand(5, 59) . '%';
                $serviceCounts[$name][$name . '-' . $i]['cpu'] = rand(100, 300) . ' MB';
            }
        }

        return [
            'total_count' => $totalCount,
            'service_counts' => $serviceCounts
        ];
    }
    public static function getbackuplist($args)
    {
        if (!isset($args['uuid'])) {
            return response()->json(['status' => false, "message" => "Please Provide uuid"]);
        }

        $application = Application::where('uuid', $args['uuid'])->first();

        if (!$application) {
            return response()->json(['status' => false, "message" => "Application Not found"]);
        }
        $backup = BackupRecord::where('site_uuid', $args['uuid'])->get();
        if (isset($backup[0])) {
            $back_data = [];
            foreach ($backup as $key => $value) {
                $back_data[$key]['path'] = $value['path'];
                $back_data[$key]['created_at'] = $value['created_at'];
            }
            return response()->json(['status' => false, "backup" => $back_data]);
        } else {
            return response()->json(['status' => false, "message" => "NO backup  found for this Application"]);
        }
    }

    public static function generateFalseIP()
    {
        // IP address segments fixed
        $ip_segment1 = 10;
        $ip_segment2 = 62;

        // Generate random values for the last two segments (0-255)
        $ip_segment3 = rand(0, 255);
        $ip_segment4 = rand(0, 255);

        // Combine all segments into the IP address string
        $false_ip = $ip_segment1 . '.' . $ip_segment2 . '.' . $ip_segment3 . '.' . $ip_segment4;

        return $false_ip;
    }
}
