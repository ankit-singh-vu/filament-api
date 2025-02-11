<?php

namespace App\Http\Controllers\Compiler;

use App\Http\Controllers\Controller;
use App\Models\AccessToken;
use App\Models\Application;
use App\Models\SshKey;
use App\Models\Cluster;
use App\Models\TempApp;
use App\Models\User;
use App\Models\user_profile;
use CreateTempAppsTable;
use Hamcrest\Arrays\IsArray;
use Illuminate\Http\Request;
use Laravel\Pulse\Users;
use Symfony\Component\Yaml\Yaml;

class CompileController extends Controller
{

    protected static array $defination_array = [];
    protected static array $template_array = [];
    protected static array $auther = [];
    protected static array $storage_array = [];
    protected static array $deployemts = [];
    protected static $temp_location = '';
    protected static $app_origin = [];
    protected static $global_err = [];
    protected static $all_app = [];
    protected static $is_upstream = false;


    /***
     * @desc this kernel function is used to marge multidimensional array
     *
     * <code>
     * self::merge_arrays($arr1, $arr2)
     * </code>
     * @param $arr1
     * @param $arr2
     * @return array
     */
    // private static function merge_arrays($arr1, $arr2): array
    // {
    //     return Kernel()->arrayMergeRecursiveReplace($arr1, $arr2);
    // }

    /***
     * @desc this function entrypoint function to run import compiler job take file path as argument
     *
     * <code>
     *  $data= Import::run($filepath);
     * </code>
     * @param $args
     * @return array
     */



    public static function run($path, $path_id = 0)
    {
        $access_token = $_GET['access_token'];
        $authors_det = [];
        $access = AccessToken::where('access_token', $access_token)->first();
        if (($access)) {
            $authors = user_profile::where('id', $access->user_id)->first();
            if (empty($authors)) {
                $respons = [];
                $respons["resposns"] = "Your Access Token not assosiated with a user please use valid one.... ";
                $respons["status"] = false;
                return $respons;
            } else {
                $path['user_uuid'] = $authors->uuid;
                $path['user_id'] = $authors->id;
                $path['access_token'] = $access_token;
                $authors_det['user_uuid'] = $authors->uuid;
                $authors_det['user_id'] = $authors->id;
                $authors_det['access_token'] = $access_token;
            }
        } else {
            $respons = [];
            $respons["resposns"] = "Unauthorize Access Token";
            $respons["status"] = false;
            return $respons;
        }

        $arg = $path["path"];
        $uuid = null;
        $is_update = false;
        $ssh_id = "public";
        $ssh_details = "public";
        $active_branch = "main";
        $action = "create";

        if ($path['action']) {
            if (!empty($path['action'])) {
                $action == $path['action'];
            } else {
                $uuid = self::gen_uuid();
            }
        } else {
            $uuid = self::gen_uuid();
        }


        if (!$path['uuid'] || $action == "jobupdate") {
            if ($action == "jobupdate") {
                self::delete_temp("/tmp/work/" . $path['app_uuid'] . "/");
                // $ssh = \Model\Application::find_by_uuid($path['app_uuid']);
                $ssh = Application::where('uuid', $path['app_uuid'])->first();
                if ($ssh->repo_type != null || $ssh->repo_type != "public") {
                    $ssh_id = $ssh->repo_type;
                    $ssh_details = $ssh->repo_type;
                }
            }

            if (isset($path["ssh_id"])) {
                $ssh_id = $path["ssh_id"];
                $ssh_details = $path["ssh_id"];
            }

            if ($ssh_id != "public") {
                $key = SshKey::where('id', $ssh_id)->first();
                if (!empty($key)) {
                    $ssh_id = "/tmp/sshkeys/" . $key->user . "/" . $key->name;
                    if (file_exists($ssh_id) && file_exists($ssh_id . ".pub")) {
                    } else {
                        if (!self::createKeyFiles("/tmp/sshkeys/" . $key->user, $key->name, $key->public_key, $key->private_key)) {
                            $err = [];
                            $err['err'] = "There is some technical issue with this please generate a new key";
                            return  $err;
                            exit();
                        }
                    }
                }
            }




            if (str_contains($arg, 'https://') || str_contains($arg, '.git') || str_contains($arg, '.com')) {
            } else {
                $REGISTRY_DOMAIN = explode(":", getenv("REGISTRY_DOMAIN"));
                self::$is_upstream = true;
                $tm = explode(":", $arg);
                $tag = '';
                if (array_key_exists(1, $tm)) {
                    $tag = ":" . $tm[1];
                }
                $arg = "https://" . $REGISTRY_DOMAIN[0] . "/" . $tm[0] . ".git" . $tag;
            }

            if (!$path["action"]) {
                $uuid = self::gen_uuid();
            }

            if ($path["app_uuid"]) {
                $uuid = $path['app_uuid'];
                $is_update = true;
                self::delete_temp("/tmp/work/" . $uuid . "/");
            }

            self::$temp_location = "/tmp/work/" . $uuid . "/";
            $satus = self::cloneGitRepo($arg, self::$temp_location, $ssh_id);

            if ($satus["status"] != "DONE") {
                $err = [];
                if (self::$is_upstream == true) {
                    $err['err'] = "This " . $path['path'] . " Is Not A Valid  Path..";
                } else {
                    $err['err'] = $satus["error"];
                }
                self::delete_temp(self::$temp_location);
                return  $err;
                exit();
            }

            if ($action == "jobupdate") {
                // $app = \Model\Application::find_by_uuid($path['app_uuid']);
                // if ($app != null || !empty($app)) {
                //     $git_command = "sh /var/www/html/bin/sheel/checkout.sh " . self::$temp_location . " " . $app->deploy_branch;
                //     $k = exec($git_command);
                // }
            }
            $active_branch = str_replace("\n", "", shell_exec("sh /var/www/html/bin/sheel/checkbranch.sh " . self::$temp_location));
        } else {
            self::$temp_location = "/tmp/work/" . $path["uuid"] . "/";
            $uuid = $path["uuid"];
            $is_update = true;
            if (file_exists(self::$temp_location . "convesio.yml")) {
                $args = self::$temp_location . "/convesio.yml";
            } else {
                $err = [];
                $err['err'] = "Somthing Went wrong please Try to deploy your app from the begening...";
                self::delete_temp(self::$temp_location);
                return  $err;
                exit();
            }
            $git_command = "sh /var/www/html/bin/checkout.sh " . self::$temp_location . " " . $path["branch"];
            $active_branch = $path["branch"];
            $k = exec($git_command);
        }

        if (file_exists(self::$temp_location . "convesio.yml")) {
            $args = self::$temp_location . "/convesio.yml";
        } else {
            $err = [];
            $err['err'] = "There is No Convesio.yml file on your project.. please mention the correct file path...";
            self::delete_temp(self::$temp_location);
            return  $err;
            exit();
        }


        $content = file_get_contents($args);
        if (empty($content)) {
            $err = [];
            $err['err'] = "your application defination file cant be empty";
            return  $err;
            exit();
        }

        $yml_fileContent = Yaml::parse($content);
        if (!is_array($yml_fileContent)) {
            $err = [];
            $err['err'] = "your defination file is not a valid one.. please check before submit..";
            return  $err;
            exit();
        }
        if (array_key_exists('services', $yml_fileContent)) {
            foreach ($yml_fileContent['services'] as $key => $elements) {
                self::$all_app[$key] = true;
            }
        }
        if (array_key_exists('import', $yml_fileContent)) {
            foreach ($yml_fileContent['import'] as $element) {
                if (is_array($element)) {
                    if (array_key_exists("source", $element)) {
                        self::process_deligates($element);
                    } else {
                        $err = [];
                        $err['err'] = "plesae mension some valid 'source' file...";
                        return  $err;
                        exit();
                    }
                } else {
                    self::non_array_template($element);
                    array_push(self::$app_origin, $element);
                }
            }
        } else {
            foreach ($yml_fileContent['services'] as $key => $elements) {
                array_push(self::$app_origin, $key);
                //self::$all_app[$key]=true;
                self::$all_app[$key] = true;
            }
        }
        if (array_key_exists("ui", $yml_fileContent)) {
            foreach ($yml_fileContent['ui']['inputs'] as $elements) {
                self::add_deployemts($elements);
            }
        }
        if (array_key_exists("storage", $yml_fileContent)) {
            foreach ($yml_fileContent["storage"] as $key => $value) {
                $temp = [];
                $temp[$key] = $value;
                array_push(self::$storage_array, $temp);
            }
        }
        $final_array = [];
        if (!empty(self::$defination_array)) {
            for ($i = 0; $i < count(self::$defination_array); $i++) {
                foreach (self::$defination_array[$i] as $x => $val) {
                    $final_array['to_be_changed'][$x] = $val;
                }
            }
        }
        for ($j = 0; $j < count(self::$template_array); $j++) {
            foreach (self::$template_array[$j] as $xs => $vals) {
                $final_array['template'][$xs] = $vals;
            }
        }
        if (empty(self::$global_err)) {
            $git_command = "sh /var/www/html/bin/sheel/get_all_branch.sh " . self::$temp_location;
            $k = exec($git_command);
            $string = substr_replace($k, '', strlen($k) - 3, 1);
            if (empty(self::$deployemts)) {
                $content = [];
                $content['path'] = $path['path'];
                $content['repotype_id'] = $ssh_details;
                $content['uuid'] = $uuid;
                $content['action'] = $action;
                if ($action === "jobupdate") {
                    Application::where('uuid', $uuid)->first();
                    // $cluster = \Model\Cluster::find_by_id($app->cluster);
                    // $content['in_cluster'] = $cluster->cluster_name;
                }
                $content['author'] = $authors_det;
                $content['author']['origin'] = $path['path'];
                $content['deployemts'] = "NO DEPLOYEMNTS QUESTION";
                $content['storage'] = self::$storage_array;
                $content['branches'] = $string;
                $content['active_branch'] = $active_branch;
                $content['all_app'] = self::$all_app;
                // $update = \Model\Tempapp::find_by_job_uuid($uuid);
                $update = TempApp::where('job_uuid', $uuid)->first();
                if ($is_update && !empty($update)) {
                    $update->data = json_encode($content);
                    $update->deploy_branch = $active_branch;
                    $update->save();
                } else {
                    $data = [
                        "data" => json_encode($content),
                        "job_uuid" => $uuid,
                        "repo_type" => $ssh_details,
                        "deploy_branch" => $active_branch
                    ];
                    TempApp::create($data);
                }
                return $content;
            } else {
                $content = [];
                $content['path'] = self::$temp_location;;
                $content['uuid'] = $uuid;
                $content['repotype_id'] = $ssh_details;
                $content['action'] = $action;
                if ($action === "jobupdate") {
                    $app = Application::where('uuid', $uuid)->first();
                    // $cluster = Cluster::where('uuid',$app->cluster)->first();
                    // $content['in_cluster'] = $cluster->cluster_name;
                }
                $content['author'] = $authors_det;
                $content['author']['origin'] = $path['path'];
                $content['deployemts'] = self::$deployemts;
                $content['storage'] = self::$storage_array;
                $content['branches'] = $string;
                $content['active_branch'] = $active_branch;
                $content['all_app'] = self::$all_app;
                $update = TempApp::where('job_uuid', $uuid)->first();
                if ($is_update && $update) {
                    $update->data = json_encode($content);
                    $update->deploy_branch = $active_branch;
                    $update->save();
                } else {
                    $data = [
                        "data" => json_encode($content),
                        "job_uuid" => $uuid,
                        "repo_type" => $ssh_details,
                        "deploy_branch" => $active_branch
                    ];
                    TempApp::create($data);
                }

                return $content;
            }
        } else {
            self::delete_temp(self::$temp_location);
            return self::$global_err;
        }
    }




















    /***
     * @desc this static function use to collect all the service details from the import template
     *
     * <code>
     * self::non_array_template($params);
     * </code>
     * @param $element
     * @return void
     */
    private static function add_deployemts($arr)
    {
        array_push(self::$deployemts, $arr);
    }
    private static function  cloneGitRepo($repoUrl, $destinationPath, $ssh_path)
    {
        $branch = "latest";
        $repoUrl = explode(":", $repoUrl);
        if (array_key_exists(2, $repoUrl)) {
            $branch = $repoUrl[2];
        }
        $repoUrl = $repoUrl[0] . ":" . $repoUrl[1];

        $git_command = "sh /var/www/html/bin/sheel/clone.sh " . $repoUrl . " " . $branch . " " . $destinationPath;
        if ($ssh_path != "public") {
            $git_command = "sh /var/www/html/bin/sheel/clone.sh " . $repoUrl . " " . $branch . " " . $destinationPath . " " . $ssh_path;
        }

        $f = exec($git_command);

        if ($f === "Tag or branch '" . $branch . "' not found") {
            $resp = [];
            $resp["status"] = "ERROR";
            $resp['error'] = $f;
            return  $resp;
        } else if ($f === "Checked out to branch " . $branch) {
            $resp = [];
            $resp["status"] = "DONE";
            $resp['error'] = $f;
            return  $resp;
        } else if ($f === "Checked out to main branch") {
            $resp = [];
            $resp["status"] = "DONE";
            $resp['error'] = $f;
            return  $resp;
        } else if ($f === "Checked out to tag " . $branch) {
            $resp = [];
            $resp["status"] = "DONE";
            $resp['error'] = $f;
            return  $resp;
        } else {
            $resp = [];
            $resp["status"] = "ERROR";
            $resp['error'] = $f;
            return  $resp;
        }
    }
    private static function createKeyFiles($path, $name, $publicKey, $privateKey)
    {
        // Create the directory if it doesn't exist
        if (!is_dir($path)) {
            if (!mkdir($path, 0700, true)) {
                return false; // Unable to create directory
            }
        }

        // Paths for public and private key files
        $publicKeyPath = $path . '/' . $name . '.pub';
        $privateKeyPath = $path . '/' . $name;

        // Write public and private key content to files
        if (
            file_put_contents($publicKeyPath, $publicKey) === false ||
            file_put_contents($privateKeyPath, $privateKey) === false
        ) {
            return false; // Unable to write to files
        }

        // Set appropriate permissions for the key files
        if (chmod($publicKeyPath, 0644) === false || chmod($privateKeyPath, 0600) === false) {
            return false; // Unable to set permissions
        }

        return true; // Creation and permissions set successfully
    }

    private static function  cloneimpots($repoUrl, $destinationPath)
    {
        $REGISTRY_DOMAIN = explode(":", getenv("REGISTRY_DOMAIN"));
        $REGISTRY_USER = getenv("REGISTRY_USER");
        $REGISTRY_PASS = getenv("REGISTRY_PASS");
        $repoUrl = escapeshellarg($repoUrl);
        $repo = $repoUrl;
        $destinationPath = escapeshellarg($destinationPath);
        $tag = "latest";
        $repoUrl = explode(":", $repoUrl);
        if (array_key_exists(1, $repoUrl)) {
            $tag = $repoUrl[1];
        }
        $drive = $destinationPath . "dependency/" . str_replace("'", "", $repoUrl[0]);
        shell_exec("mkdir -p " . str_replace("'", "", $drive));
        $f = exec("sh /var/www/html/bin/sheel/clone_import.sh " . $REGISTRY_DOMAIN[0] . "/" . str_replace("'", "", $repoUrl[0]) . ".git " . " " . str_replace("'", "", $tag) . " " . $REGISTRY_USER . " " . $REGISTRY_PASS . " " .  str_replace("'", "", $drive));
        if ($f === "Tag or branch not found") {
            $res = " Tag and Branch ***  " . $tag . "  *** Not found in our registry For...  " . $repoUrl[0];
            return $res;
        } else if ($f === "Checked out to branch " . $tag) {
            return  true;
        } else if ($f === "Checked out to tag main") {
            return  true;
        } else if ($f === "Checked out to tag " . $tag) {
            return  true;
        } else if ($f === "Checked out to main branch") {
            return  true;
        } else if ($f === "Clone unsuccessful. your Git URL Maynot Right Or Not Public.") {
            $res = $repoUrl[0] . " not found in our registry...";
            return $res;
        } else {
            $res = $f;
            return $res;
        }
    }


    private static function non_array_template($element)
    {
        if (file_exists(self::$temp_location . "" . self::trim_tag($element) .
            '/convesio.yml')) {
            $file_content = Yaml::parse(file_get_contents(self::$temp_location . "" . self::trim_tag($element) . '/convesio.yml'));
            if (array_key_exists('services', $file_content)) {
                foreach ($file_content['services'] as $key => $elements) {
                    self::$all_app[$key] = true;
                }
            }
            if (array_key_exists("ui", $file_content)) {
                foreach ($file_content['ui']['inputs'] as $elements) {
                    self::add_deployemts($elements);
                }
            }
            if (array_key_exists("storage", $file_content)) {
                foreach ($file_content["storage"] as $key => $value) {
                    $temp = [];
                    $temp[$key] = $value;
                    array_push(self::$storage_array, $temp);
                }
            }
            if (array_key_exists("import", $file_content)) {
                foreach ($file_content['import'] as $elements) {
                    if (is_array($elements)) {
                        self::process_deligates($elements);
                    } else {
                        self::non_array_template($elements);
                    }
                }
            }
        } elseif (file_exists(self::$temp_location . "dependency/" . self::trim_tag($element) . '/convesio.yml')) {
            $file_content = Yaml::parse(file_get_contents(self::$temp_location . "dependency/" . self::trim_tag($element) . '/convesio.yml'));
            if (array_key_exists("ui", $file_content)) {
                foreach ($file_content['ui']['inputs'] as $elements) {
                    self::add_deployemts($elements);
                }
            }
            if (array_key_exists('services', $file_content)) {
                foreach ($file_content['services'] as $key => $elements) {
                    self::$all_app[$key] = true;
                }
            }
            if (array_key_exists("storage", $file_content)) {
                foreach ($file_content["storage"] as $key => $value) {
                    $temp = [];
                    $temp[$key] = $value;
                    array_push(self::$storage_array, $temp);
                }
            }
            if (array_key_exists("import", $file_content)) {
                foreach ($file_content['import'] as $elements) {
                    if (is_array($elements)) {
                        self::process_deligates($elements);
                    } else {
                        self::non_array_template($elements);
                    }
                }
            }
        } else {
            try {
                $clone = self::cloneimpots($element, self::$temp_location);
                if ($clone === true) {
                    self::non_array_template($element);
                } else {
                    self::$global_err["err"] = $clone;
                    self::delete_temp(self::$temp_location);
                    return $clone;
                }
            } catch (Exception $e) {
                print_r($e->getMessage());
                return false;
            }
        }
    }

    // /***
    //  * @desc  this static function take services with all key values of application yml which need to be changed
    //  *
    //  * <code>
    //  *  self::add_service_to_be_changed($params);
    //  * </code
    //  * @param $elements
    //  * @return void
    //  */
    // private static function add_service_to_be_changed($elements)
    // {
    //     self::$defination_array[] = $elements;
    // }

    // /***
    //  * @desc this static function is handles those import services which requires to change a predefine service name
    //  *        or disable any predefine service
    //  *
    //  * <code>
    //  * self::process_deligates($args);
    //  * </code>
    //  * @param $args
    //  * @return void
    //  */
    private static function process_deligates($args)
    {

        if (
            file_exists(self::$temp_location . self::trim_tag($args['source'])
                . '/convesio.yml')
        ) {
            echo self::$temp_location . self::trim_tag($args['source']) . '/convesio.yml';
            $file_content = Yaml::parse(file_get_contents(self::$temp_location . self::trim_tag($args['source']))
                . '/convesio.yml');
            if (array_key_exists('services', $file_content)) {
                foreach ($file_content['services'] as $key => $elements) {
                    self::$all_app[$key] = true;
                }
            }
            if (array_key_exists("ui", $file_content)) {
                foreach ($file_content['ui']['inputs'] as $elements) {
                    self::add_deployemts($elements);
                }
            }
            if (array_key_exists("storage", $file_content)) {
                foreach ($file_content["storage"] as $key => $value) {
                    $temp = [];
                    $temp[$key] = $value;
                    array_push(self::$storage_array, $temp);
                }
            }
            if (array_key_exists("import", $file_content)) {
                foreach ($file_content['import'] as $element) {
                    if (is_array($element)) {
                        self::process_deligates($element);
                    } else {
                        self::non_array_template($element);
                    }
                }
            }
        } elseif (file_exists(self::$temp_location . "dependency/" . self::trim_tag($args['source']) . '/convesio.yml')) {
            $file_content = Yaml::parse(file_get_contents(self::$temp_location . "dependency/" . self::trim_tag($args['source']) . '/convesio.yml'));
            if (array_key_exists("ui", $file_content)) {
                foreach ($file_content['ui']['inputs'] as $elements) {
                    self::add_deployemts($elements);
                }
            }
            if (array_key_exists('services', $file_content)) {
                foreach ($file_content['services'] as $key => $elements) {
                    self::$all_app[$key] = true;
                }
            }
            if (array_key_exists("storage", $file_content)) {
                foreach ($file_content["storage"] as $key => $value) {
                    $temp = [];
                    $temp[$key] = $value;
                    array_push(self::$storage_array, $temp);
                }
            }
            if (array_key_exists("import", $file_content)) {
                foreach ($file_content['import'] as $element) {
                    if (is_array($element)) {
                        self::process_deligates($element);
                    } else {
                        self::non_array_template($element);
                    }
                }
            }
        } else {
            try {
                $clone = self::cloneimpots($args['source'], self::$temp_location);
                if ($clone === true) {
                    self::process_deligates($args);
                    return "clone successfull..";
                } else {
                    self::$global_err["err"] = $clone;
                    self::delete_temp(self::$temp_location);
                    return $clone;
                }
            } catch (Exception $e) {
                print_r($e->getMessage());
                return false;
            }
        }
    }

    // /***
    //  * @desc this static function is process the import service string for local ex: import: convesio/wordpress:latest
    //  *               if its fids locally then it will trim as convesio/wordpress else convesio/wordpress:latest
    //  *
    //  * <code>
    //  * self::trimAfterColon($args);
    //  * </code>
    //  * @param $inputString
    //  * @return false|string
    //  */

    private static function trimAfterColon($inputString)
    {
        $colonPos = strpos($inputString, ':');
        if ($colonPos !== false) {
            return substr($inputString, 0, $colonPos);
        }
        return $inputString;
    }

    // /***
    //  * @desc this static function is process the import service string for register ex: import: convesio/wordpress
    //  *               if there is no version mention then it become latest convesio/wordpress:latest else
    //  *               convesio/wordpress:@param $inputString
    //  * @return string
    //  * @version
    //  *
    //  * <code>
    //  * self::addLatestTag($args);
    //  * </code>
    //  */
    private static function addLatestTag($inputString)
    {
        $lastColonPos = strrpos($inputString, ':');

        if ($lastColonPos !== false) {
            $tag = substr($inputString, $lastColonPos + 1);
            if (trim($tag) === '') {
                return $inputString . 'latest';
            } else {
                return $inputString;
            }
        } else {
            return $inputString . ':latest';
        }
    }

    // /***
    //  * @desc this function check is the import service is available or not it takes service name as a param
    //  *
    //  * <code>
    //  * self::getStatusCode($args);
    //  * </code>
    //  * @param $url
    //  * @return int
    //  */
    // private static function getStatusCode($url)
    // {
    //     $headers = get_headers($url);
    //     if ($headers && is_array($headers)) {
    //         foreach ($headers as $header) {
    //             if (strpos($header, 'HTTP/') === 0) {
    //                 $status_parts = explode(' ', $header);
    //                 if (isset($status_parts[1])) {
    //                     return intval($status_parts[1]);
    //                 }
    //             }
    //         }
    //     }
    //     return 0;
    // }

    // /***
    //  * @desc this function is to Delete the temp service folders
    //  *
    //  *<code>
    //  * self::getStatusCode($args);
    //  *</code>
    //  * @param $args
    //  * @return void
    //  */
    private static function delete_temp($args)
    {
        shell_exec("rm -r " . $args);
    }

    private static function trim_tag($args)
    {
        $repoUrl = explode(":", $args);
        return $repoUrl[0];
    }

    static function gen_uuid($model = false)
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),

            // 16 bits for "time_mid"
            mt_rand(0, 0xffff),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand(0, 0x0fff) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,

            // 48 bits for "node"
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }
}
