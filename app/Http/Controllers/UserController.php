<?php

namespace App\Http\Controllers;

use App\Models\AccessToken;
use App\Models\User;
use App\Models\Tenant;

use App\Models\UserAccess;
use App\Models\Access;
use App\Models\Application;
use App\Models\Session;
use App\Models\user_profile;
use App\Models\Role;
use App\Models\PortMap;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class UserController extends Controller
{

    public static function login($content)
    {
        $response = [];

        if (isset($content['access_key']) && isset($content['access_secret'])) {
            $apiuser = UserAccess::where('username', $content['access_key'])
                ->where('password', md5($content['access_secret']))
                ->first();
            $checkPartner = checkPartner($content['access_key'],$content->url());
            if(!$checkPartner){
                $response = array();
                $response["message"] = "Invalid signin url";
                $response["error"] = true;
                return response()->json($response, 401);
            }

            if (!empty($apiuser)) {
                $user_profile = user_profile::where('id', $apiuser->user_id)
                ->first();

                $acckey = Str::uuid() . "-" . Str::uuid();

                $type = isset($content['type']) && $content['type'] ? $content['type'] : "API";
                $userId = $apiuser->user_id;

                $token = AccessToken::where('user_id', $userId)
                    ->where('type', $type)
                    ->first();

                if (empty($token)) {
                    $newtoken = Accesstoken::create(array(
                        'user_id'        => $apiuser->user_id,
                        'access_token'   =>  $acckey,
                        'type'           =>  isset($content['type']) && $content['type'] ? $content['type'] : "API",
                        'is_primary'     =>  'yes',
                        'last_access'    =>  date('U')
                    ));
                } else {
                    $token->access_token = $acckey;
                    $token->last_access  = date('U');
                    $token->save();
                }
                $response = array();
                $response["message"] = "Authentication Success";
                $response["id"] = $apiuser->user_id;
                $response["token"] = $acckey;
                $response["user"] = $user_profile;
                return response()->json($response, 200);
            } else {
                $response = array();
                $response["message"] = "Authentication failed";
                $response["error"] = true;
                return response()->json($response, 401);  //401 Unauthorized
            }
        } else {
            $response = array();
            $response["message"] = "access_key or access_secret can't empty.";
            $response["error"] = true;
            return response()->json($response, 400);  //400 Bad Request 
        }
        return $response;
    }

    // Add user to existing Tenant 
    public static function adduser($data)
    {
        $response = [];
        $tenant_id = $data->query('tenant_id');
        $role = $data->query('role');
        // $tenant_id  = base64_decode($tenant_id );
        // $role  = base64_decode($role );

        if (!empty($data['email']) && !empty($data['first']) && !empty($data['last']) && !empty($data['password'])) {
            $tenant = Tenant::find($tenant_id);
            if (!empty($tenant)) {

                $user = user_profile::where('email', $data['email'])->first();
                if (empty($user)) {

                    $user = user_profile::create(array(
                        'uuid' => Str::uuid(),
                        'first_name' => $data['first'],
                        'middle_name' => $data['middle'],
                        'last_name' => $data['last'],
                        'email' => $data['email'],
                        'c_tenant' => $tenant->id,
                        'timezone' => '',
                        'utype' => 1,
                        'status' => 1
                    ));
                    $userAccess = UserAccess::create(array(
                        'username' => $data['email'],
                        'password' => md5($data['password']),
                        'access_type' => 2,
                        'user_id' => $user->id,
                        'status' => 1,
                        'locked' => 1
                    ));
                    $access_key = md5($data['email'] . '' . date('u'));
                    $access_secret = md5($access_key . '' . date('u'));
                    $access_token = md5($access_secret . '' . date('u'));

                    $primary_role = Role::create(array(
                        'name' => $role,
                        'tenant_id' => $tenant->id,
                        'locked' => 1
                    ));

                    $response = array();
                    $response["access_key"] = $access_key;
                    $response["access_secret"] = $access_secret;
                    $response["acceess_token"] = $access_token;
                    $response["message"] = "User Created Successfully";
                    return response()->json($response, 201); //201 Created
                } else {
                    $response = array();
                    $response["message"] = "User already exist";
                    $response["error"] = true;
                    return response()->json($response, 409);  //409 Conflict
                }
            } else {
                $response = array();
                $response["message"] = "Tenant not found";
                $response["error"] = true;
                return response()->json($response, 404);  //404 Not Found 
            }
        } else {
            $response = array();
            $response["message"] = "Please pass appropriate parameters";
            $response["error"] = true;
            return response()->json($response, 400);  //400 Bad Request 
        }
    }

    public static function registration($data)
    {
        if (!empty($data['email']) && !empty($data['first']) && !empty($data['last']) && !empty($data['password'])) {
            $is_tenant_exist = Tenant::where('email', $data['email'])->first();
            if (empty($is_tenant_exist)) {
                $tenant = Tenant::create(array(
                    'uuid' => Str::uuid(),
                    "name" => $data['tenants'] ?: $data['first'] . " " . $data['middle'] . " " . $data['last'],
                    "email" => $data['email'],
                ));
                $user = user_profile::create(array(
                    'uuid' => Str::uuid(),
                    'first_name' => $data['first'],
                    'middle_name' => $data['middle'],
                    'last_name' => $data['last'],
                    'email' => $data['email'],
                    'c_tenant' => $tenant->id,
                    'timezone' => '',
                    'utype' => 1,
                    'status' => 1
                ));
                $userAccess = UserAccess::create(array(
                    'username' => $data['email'],
                    'password' => md5($data['password']),
                    'access_type' => 2,
                    'user_id' => $user->id,
                    'status' => 1,
                    'locked' => 1
                ));
                $access_key = md5($data['email'] . '' . date('u'));
                $access_secrete = md5($access_key . '' . date('u'));
                $access_token = md5($access_secrete . '' . date('u'));

                $primary_role = Role::create(array(
                    'name' => 'Owner',
                    'tenant_id' => $tenant->id,
                    'locked' => 1
                ));
                Role::create(array(
                    'name' => 'Admin',
                    'tenant_id' => $tenant->id,
                    'locked' => 1
                ));
                Role::create(array(
                    'name' => 'Staff',
                    'tenant_id' => $tenant->id,
                    'locked' => 1
                ));
                $response = array();
                $response["user_id"] = $user->id;
                $response["tenant_id"] = $tenant->id;
                $response["access_key"] = $access_key;
                // $response["access_secrete"] = $access_secrete;
                $response["acceess_token"] = $access_token;
                $response["message"] = "User Created Successfully";
                return response()->json($response, 201); //201 Created
            } else {
                $response = array();
                $response["message"] = "User already exist";
                $response["error"] = true;
                return response()->json($response, 409);  //409 Conflict
            }
        } else {
            $response = array();
            $response["message"] = "Please pass appropriate parameters";
            $response["error"] = true;
            return response()->json($response, 400);  //400 Bad Request 
        }
    }

    public static function delete($request)
    {
        $data = Tenant::where('email', $_GET["email"])->first();
        if (!empty($data)) {
            $datauser = user_profile::where('c_tenant', $data->id)->first();
            $dataaccess = UserAccess::where('user_id', $datauser->id)->first();
            $datarole = Role::where('tenant_id', $data->id)->delete();
            $datatoken = AccessToken::where('user_id', $datauser->id)->delete();

            $data->delete();
            $datauser->delete();
            $dataaccess->delete();
            $response = array();
            $response["message"] = "User Deleted Successfully";
            return response()->json($response);
        } else {
            $response = array();
            $response["message"] = "User not found";
            $response["error"] = true;
            return response()->json($response, 404);  //404 Not Found
        }
    }

    public function firstapi(Request $request) {
        $url = $request->url();
        $url_arr = explode('//', $url);
        return response()->json(["welcome to ".$url_arr[1]]);
    }

    public function GetSshDetails(Request $request) {
        $app_uuid = $request->app_uuid;
        $port_data = PortMap::where('job_uuid',$app_uuid)
                            ->where('task_name', 'ssh')->first();
        if($port_data){
        $app_data = Application::select('node','job_file_payload')
                                ->where('uuid',$app_uuid)->first();
        $host = $app_data->node;
        $job_file_payload = $app_data->job_file_payload;
        $data = json_decode($job_file_payload, true);
        $sshUser = null;
        $sshPassword = null;
        foreach ($data['Job']['TaskGroups'] as $taskGroup) {
            // Iterate through each task within the task group
            foreach ($taskGroup['Tasks'] as $task) {
                // Check if 'Env' exists for this task
                if (isset($task['Env'])) {
                    // Check for 'SSH_USER' and 'SSH_PASSWORD' in the 'Env' array
                    if (isset($task['Env']['SSH_USER'])) {
                        $sshUser = $task['Env']['SSH_USER'];
                    }
                    if (isset($task['Env']['SSH_PASSWORD'])) {
                        $sshPassword = $task['Env']['SSH_PASSWORD'];
                    }
                }
            }
        }
            $response = array();
            $response["message"] = "SSH details fetched successfully";
            $response["host"] = $host;
            $response["port"] = $port_data->port;
            $response["ssh_user"] = $sshUser;
            $response["ssh_password"] = $sshPassword;
            return response()->json($response, 400);  //400 Bad Request
        }else{
            $response = array();
            $response["message"] = "SSH is not available for this app";
            $response["error"] = true;
            return response()->json($response, 400);  //400 Bad Request
        }
    }

}
