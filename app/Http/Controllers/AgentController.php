<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AgentController extends Controller
{
    /**
     * Retrieves details about the Agent job from a specified Nomad cluster.
     *
     * Endpoint URL:
     * - HTTP GET http://<cluster_url>:4646/v1/job/agent-laravel
     *
     * Parameters:
     * - Request $request: An instance of the Laravel Request object containing client data.
     *   - cluster_url (string): The base URL of the Nomad cluster.
     * 
     * @param Request $request An instance of the Laravel Request object containing client data.
     * @return array An associative array with the key "response" holding the decoded JSON object from the Nomad API response.
     */
    public function Agentdetails(Request $request) {
        // Extract all data from the incoming request
        $content = $request->all();

        // Retrieve 'cluster_url' from the request data
        $cluster_url = $content['cluster_url'];

        // Construct the URL to access the job details from the Nomad cluster's API
        $url = 'http://'.$cluster_url.':4646/v1/job/agent-laravel';

         // Perform an HTTP GET request to the constructed URL using a helper function
        $response = curlGet($url);

        // Decode the JSON response from the Nomad API
        $response = json_decode($response);

        // Return an associative array containing the decoded response
        return array("response" => $response);
    }

    /**
     * Deploys the "agent-laravel" job to a specified Nomad cluster.
     *
     * Endpoint URL:
     * - HTTP POST http://<cluster_url>:4646/v1/jobs
     *
     * Parameters:
     * - Request $request: An instance of the Laravel Request object containing client data.
     *   - cluster_url (string): The base URL of the Nomad cluster.
     *   - job_json (string, optional): A JSON string representing the job configuration. If not provided, a default job configuration is loaded from a file.
     *
     * @param Request $request An instance of the Laravel Request object containing client data.
     * @return array An associative array with the key "response" holding the response from the Nomad API.
     */
    public function Deployagent(Request $request) {
        // Extract all data from the incoming request
        $content = $request->all();

        // Retrieve 'cluster_url' from the request data
        $cluster_url = $content['cluster_url'];

        // Check if 'job_json' is provided in the request, otherwise load default job configuration from a file
        if(isset($content['job_json']) && $content['job_json'] != ""){
            $job_json = $content['job_json'];
        }else{
            $job_json = file_get_contents("/var/www/html/bin/systemjobs/agent_job.json");
        }

        // Construct the URL to submit the job to the Nomad cluster's API
        $url = 'http://'.$cluster_url.':4646/v1/jobs';

        // Perform an HTTP POST request to the constructed URL with the job JSON using a helper function
        $response = curlPost($url, $job_json);

        // Return an associative array containing the response from the Nomad API
        return array("response" => $response);
    }

    /**
     * Updates the "agent" job on a specified Nomad cluster.
     *
     * Endpoint URL:
     * - HTTP POST http://<cluster_url>:4646/v1/job/agent
     *
     * Parameters:
     * - Request $request: An instance of the Laravel Request object containing client data.
     *   - cluster_url (string): The base URL of the Nomad cluster.
     *   - job_json (string, optional): A JSON string representing the job configuration. If not provided, a default job configuration is loaded from a file.
     *
     * @param Request $request An instance of the Laravel Request object containing client data.
     * @return array An associative array with the key "response" holding the response from the Nomad API.
     */
    public function Updateagent(Request $request) {
        // Extract all data from the incoming request
        $content = $request->all();

        // Retrieve 'cluster_url' from the request data
        $cluster_url = $content['cluster_url'];

        // Check if 'job_json' is provided in the request, otherwise load default job configuration from a file
        if(isset($content['job_json']) && $content['job_json'] != ""){
            $job_json = $content['job_json'];
        }else{
            $job_json = file_get_contents("/var/www/html/bin/agent_job.json");
        }

        // Construct the URL to update the job on the Nomad cluster's API
        $url = 'http://'.$cluster_url.':4646/v1/job/agent';

        // Perform an HTTP POST request to the constructed URL with the job JSON using a helper function
        $response = curlPost($url, $job_json);

        // Return an associative array containing the response from the Nomad API
        return array("response" => $response);
    }
}
