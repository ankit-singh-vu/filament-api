<?php

namespace App\Http\Controllers;

use App\Models\AccessToken;
use App\Models\SshKey;
use App\Models\User;
use App\Models\user_profile;
use Illuminate\Http\Request;

class SshController extends Controller
{


    /**
     * Show the form for creating a new resource.
     */
    public static function generate_ssh($content, $path_id = 0)
    {

        $access_token = $_GET['access_token'];
        // return $access_token;
        if (!$access_token) {
            $respons = [];
            $respons["resposns"] = "Your Access Token not Found... ";
            $respons["status"] = false;
            http_response_code(403);
            return $respons;
        }
        $access = AccessToken::where('access_token', $access_token)->first();
        if (!empty($access)) {
            $user = user_profile::where('id', $access->user_id)->first();
            if (!$user) {
                $respons = [];
                $respons["resposns"] = "Your Access Token not assosiated with a user please use valid one.... ";
                $respons["status"] = false;
                http_response_code(403);
            } else {
                if ($content) {
                    if (isset($content['key_name'])) {
                        $key_name = $content['key_name'];
                        $check_keyname = SshKey::where([
                            ['user', '=', $user->uuid],
                            ['name', '=', $key_name],
                            ['status', '=', 1]
                        ])->first();
                        if (!$check_keyname) {
                            $folder = '/tmp/sshkeys/' . $user->uuid;
                            $ssh_keygen_command = 'mkdir -p ' . $folder . ' && ';
                            $key_name_new = str_replace(" ", "_", $key_name);
                            $pvt_key_path = $folder . '/' . $key_name_new;
                            $pub_key_path = $folder . '/' . $key_name_new . '.pub';

                            $ssh_keygen_command = $ssh_keygen_command . 'ssh-keygen -t rsa -b 2048 -f ' . $folder . '/' . $key_name_new . ' -N "" -q';
                            exec($ssh_keygen_command);
                            $public_key = file_get_contents($pub_key_path);
                            $prvate_key = file_get_contents($pvt_key_path);

                            $data = [
                                "name" => $key_name,
                                "public_key" => $public_key,
                                "private_key" => $prvate_key,
                                "user" => $user->uuid
                            ];
                            $addsshkey = SshKey::create($data);
                            if ($addsshkey) {
                                return ['status' => true, "response" => "successfully added the key ", "public_key" => $public_key];
                            } else {
                                return ['status' => false, "response" => "something went wrong. please try again"];
                            }
                        } else {
                            return ['status' => false, "response" => "Please change the key name. This is already exists."];
                        }
                    } else {
                        $respons = array();
                        $respons["response"] = "key_name must be given";
                        $respons["status"] = false;
                        http_response_code(401);
                        return $respons;
                    }
                }
            }
        } else {
            $respons = [];
            $respons["resposns"] = "Your Access Token not assosiated with a user please use valid one.... ";
            $respons["status"] = false;
            http_response_code(403);
            return $respons;
        }
    }
    public static function get_ssh()
    {
        $access_token = $_GET['access_token'];

        if (empty($access_token)) {
            $respons = [];
            $respons["resposns"] = "Your Access Token not assosiated with a user please use valid one.... ";
            $respons["status"] = false;
            http_response_code(403);
            return $respons;
        }
        $access = AccessToken::where('access_token', $access_token)->first();
        if (!empty($access)) {
            $user =  user_profile::where('id', $access->user_id)->first();
            if (empty($user)) {
                $respons = [];
                $respons["resposns"] = "Your Access Token not assosiated with a user please use valid one.... ";
                $respons["status"] = false;
                http_response_code(403);
                return $respons;
            } else {

                $getallsshkey = SshKey::where([
                    ['user', '=', $user->uuid],
                    ['status', '=', 1]
                ])->get();
                if (!empty($getallsshkey[0])) {
                    $all_keys_list = [];
                    $count = 0;
                    foreach ($getallsshkey as $keys) {
                        $all_keys_list[$count]['public_key'] = $keys->public_key;
                        $all_keys_list[$count]['name'] = $keys->name;
                        $all_keys_list[$count]['id'] = $keys->id;
                        $count++;
                    }
                    return ["status" => true, "response" => $all_keys_list,];
                } else {
                    $respons = [];
                    $respons["resposns"] = "no ssh key found for the user";
                    $respons["status"] = false;
                    return $respons;
                }
            }
        } else {
            $respons = [];
            $respons["resposns"] = "Your Access Token not assosiated with a user please use valid one.... ";
            $respons["status"] = false;
            http_response_code(403);
            return $respons;
        }
    }

    public static function delete_ssh($data)
    {
        // return "hhh";
        $sshkey_id = $data['id'];
        $sshkey = SshKey::where('id', $sshkey_id)->first();
        if ($sshkey) {
            $sshkey->status = 0;
            $sshkey->save();
            return ['status' => true, "response" => "ssh key deleted successfully"];
        } else {
            return ['status' => false, "response" => "ssh key not found"];
        }
    }
}
