<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Partners;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Cookie;

class PartnerAuthController extends Controller
{
    public function signin(Request $request) {
        $content = $request->all();
        $email = $content['access_key'];
        $password = $content['access_secret'];
        $chk_record = Partners::where('email', $email)
                ->where('status', 1);
        
        if ($chk_record->exists()){
            
            $usercontroller = new UserController;
            $userlogin = $usercontroller->login($request);
            $responseContent = $userlogin->getContent();
            $responseData = json_decode($responseContent, true);
            // Check if the response data was decoded successfully
            if ($responseData === null) {
                throw new \Exception('Failed to decode JSON response');
            }
            if($responseData['message'] == 'Authentication Success'){
                $token = $responseData['token'];
                Cookie::queue('access_token', $token, 60);
                return redirect()->route('partner.dashboard');
            }else{
                $errors = [$responseData['message']];
            return redirect()->back()->withErrors($errors);
            }
            
        }else{
            $errors = ['Invalid email'];
            return redirect()->back()->withErrors($errors);
        }
        
    }
    public function dashboard(Request $request) {
        $access_token = Cookie::get('access_token');
        if($access_token != ''){
            return view("partner.dashboard");
        }else{
            $errors = ['Please login to continue..'];
            return redirect('/')->withErrors($errors);
        }
    }
    public function signout() {
        $access_token = Cookie::get('access_token');
        if($access_token != ''){
            Cookie::queue(Cookie::forget('access_token'));
        }
        $errors = ['Please login to continue..'];
        return redirect('/')->withErrors($errors);
    }
}
