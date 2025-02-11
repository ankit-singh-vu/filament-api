<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use App\Models\Partners;
use App\Models\Tenant;
use App\Models\UserAccess;

class PartnerController extends Controller
{
    public function index(Request $request) {
        
        if(isset($_COOKIE['admin_access_token'])){
            $cookie_data = $_COOKIE['admin_access_token'];
        }else{
            $cookie_data=false;
        }
        if($cookie_data){

        $partners = Partners::where('status', 1)->get();
        
        return view('admin.partnerlist', ['partners' => $partners]);

    }else{
        return redirect()->route('admin.signin');
    }
    }
    public function Add_partner(Request $request) {
        if(isset($_COOKIE['admin_access_token'])){
            $cookie_data = $_COOKIE['admin_access_token'];
        }else{
            $cookie_data=false;
        }
        if($cookie_data){
            $fullname=$request->fullname;
            $explodfullname=explode(" ",$fullname);
            if(count($explodfullname) == 3){
                $fullname = $explodfullname[0];
                $middle = $explodfullname[1];
                $lastname = $explodfullname[2];
            }elseif(count($explodfullname) == 2){
                $fullname = $explodfullname[0];
                $middle="";
                $last=$explodfullname[1];
            }elseif(count($explodfullname) == 1){
                $fullname = $explodfullname[0];
                $middle="";
                $last="";
            }
            $first=$explodfullname[0];

            $userdata = array("email"=>$request->email,
                        "first"=>$fullname,
                        "middle"=>$middle,
                        "last"=>$last,
                        "password"=>$request->password,
                        "tenants"=>$request->company
            );
        $usercontroller = new UserController;
        $create_user = $usercontroller->registration($userdata);
        $responseContent = $create_user->getContent();
        $responseData = json_decode($responseContent, true);

        if($responseData['message'] == 'User Created Successfully'){
            $update_access_type = UserAccess::where('id', $responseData['user_id'])
                                        ->update(["access_type"=>'partner']);

            $partner = Partners::create(array(
                "partner_name" => $request->company,
                "partner_company_name" => $request->company,
                "tenant_id" => $responseData['tenant_id'],
                "user_id" => $responseData['user_id'],
                "email" => $request->email,
            ));
                                    
            $update_partner_id = Tenant::where('id', $responseData['tenant_id'])
                                        ->update(["partner_id"=>$partner->id]);
            
            $partners = Partners::where('status', 1)->get();

            return view('admin.partnerlist', ['partners' => $partners]);
        }else{
            $errors = [$responseData['message']];
            return redirect()->back()->withErrors($errors);
        }

    }else{
        return redirect()->route('admin.signin');
    }
    }
}
