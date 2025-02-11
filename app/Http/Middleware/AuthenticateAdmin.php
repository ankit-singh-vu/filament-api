<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Models\Admin;
use App\Models\AdminAccessToken;

class AuthenticateAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */



    public function handle(Request $request, Closure $next): Response
    {
        $access_token = $this->getAccessToken($request);
        if ($access_token === false) {
            $response = [];
            $response["message"] = "Access Token is missing";
            $response["error"] = true;
            return response()->json($response, 401); // 401: Unauthorized
        }
        $access = AdminAccessToken::where('access_token', $access_token)->first();
        if (!empty($access)) {
            $user = Admin::find($access->user_id);
            if (!$user) {
                $response = [];
                $response["message"] = "Invalid Access Token";
                $response["error"] = true;
                return response()->json($response, 401); // 401: Unauthorized
            } else {
                return $next($request); // go to next middleware if any
            }
        } else {
            $response = [];
            $response["message"] = "Invalid Access Token";
            $response["error"] = true;
            return response()->json($response, 401); // 401: Unauthorized
        }
    }

    function getAccessToken(Request $request)
    {
        if ($request->query('access_token')) { // query
            return $request->query('access_token');
        }

        if ($request->cookie('access_token')) {
            return $request->cookie('access_token');
        }
        if ($_COOKIE['admin_access_token']) {
            return $_COOKIE['admin_access_token'];
        }

        if ($request->header('access-token')) { // header
            return $request->header('access-token');
        }

        if ($request->input('access_token')) { // body
            return $request->input('access_token');
        }
        return false;
    }
}
