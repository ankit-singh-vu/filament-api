<?php

namespace App\Http\Controllers;


use App\Models\Admin;
use App\Models\AdminAccessToken;
use App\Models\Cluster_location;
use App\Models\VmPackage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    public static function login($content)
    {
        $email = $content['email'];
        $password = md5($content['password']);
        if(!empty($content['type'])){
            $type = $content['type'];
        }else{
            $type = "API";
        }
        

        $admin = Admin::where('email', $email)
            ->where('password', $password)
            ->first();

        if (!empty($admin)) {
            $acckey = Str::uuid() . "-" . Str::uuid();
            $token = AdminAccessToken::where('user_id', $admin->id)
                ->where('type', $type)
                ->where('is_primary', 'yes')
                ->first();

            if (empty($token)) {
                $newtoken = AdminAccessToken::create(array(
                    'user_id'        => $admin->id,
                    'access_token'   =>  $acckey,
                    'type'           =>  $type,
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
            $response["token"] = $acckey;
            return response()->json($response, 200);
        } else {
            $response = array();
            $response["message"] = "Authentication failed";
            $response["error"] = true;
            return response()->json($response, 401);  //401 Unauthorized
        }
    }

    public static function registration($data)
    {
        $admin = Admin::where('email', $data['email'])->first();
        if (!empty($admin)) {
            $response = array();
            $response["message"] = " Admin email already exists. ";
            $response["error"] = true;
            return response()->json($response, 409);  //409 Conflict
        } else {
            $admin_data = array(
                "fullname"        => $data["fullname"],
                "email"           => $data["email"],
                "password"        => md5($data["password"]),
                "access"          => $data["access"],
                "status"          => $data["status"],
                "timezone"        => $data["timezone"],
                "last_login"      => $data["last_login"],
                "last_ip"         => $data["last_ip"],
                "contact_number"  => $data["contact_number"],
                "role"            => $data["role"],
                "two_fa"          => $data["two_fa"],
                "google_auth_key" => $data["google_auth_key"],
                "last_scan"       => $data["last_scan"]
            );
            $admin = Admin::create($admin_data);

            $result = array();
            if ($admin != '') {
                $response["message"] = " Admin created succesfully. ";
                $response["data"] = $admin;
                return response()->json($response, 201); //201 Created
            } else {
                $response = array();
                $response["message"] = "Something went wrong!";
                $response["error"] = true;
                return response()->json($response, 500); //500 Internal Server Error
            }
        }
    }

    public static function delete($request, $id)
    {
        $data = Admin::find($id);
        if (!empty($data)) {
            $datatoken = AdminAccessToken::where('user_id', $data->id)->delete();
            $data->delete();
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

    public static function  get_cluster_info_data($request,$info_type){
        if($info_type == "packages"){
            $cluster_id=$request->get("provider");
            $all_cluster_packages=VmPackage::all()->where('provider',$cluster_id);
            if($all_cluster_packages){
                $response["data"] = $all_cluster_packages;
                $response["status"] = true;
                return response()->json($response,200);
            }else{
                $response["message"] = "no cluster package found";
                $response["status"] = false;
                $response["error"] = true;
                return response()->json($response,402);                
            }
        }elseif($info_type == "location"){
            $cluster_locations = Cluster_location::all();
            $response = array();
            $response["data"] = $cluster_locations;
            $response["status"] = true;
            return response()->json($response,200);
        }else{
            return response()->json([],404);
        }
    }
}
