<?php

namespace App\Http\Middleware;

use App\Models\AccessToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Models\Admin;
use App\Models\AdminAccessToken;

class AuthenticatePartnerWeb
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    public function handle(Request $request, Closure $next): Response
    {
        if(isset($_COOKIE['access_token'])){
            $cookie_data = $_COOKIE['access_token'];
        }else{
            $cookie_data=false;
        }
       
        if($cookie_data){
            
            $token = AccessToken::where('access_token', $cookie_data)->first();
            if($token){
                return $next($request); 
            }else{
                $errors = ['Please login to continue..'];
                return redirect('/')->withErrors($errors);
            }
        }else{
            $errors = ['Please login to continue..'];
            return redirect('/')->withErrors($errors);
        }
        
    }
}
