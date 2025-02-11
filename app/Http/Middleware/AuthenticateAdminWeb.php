<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Models\Admin;
use App\Models\AdminAccessToken;

class AuthenticateAdminWeb
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    public function handle(Request $request, Closure $next): Response
    {
        if(isset($_COOKIE['admin_access_token'])){
            $cookie_data = $_COOKIE['admin_access_token'];
        }else{
            $cookie_data=false;
        }
       
        if($cookie_data){
            
            $token = AdminAccessToken::where('access_token', $cookie_data)->first();
            if($token){
                return $next($request); 
            }else{
                return redirect()->route('admin.signin');
            }
        }else{
            return redirect()->route('admin.signin');
        }
        
    }
}
