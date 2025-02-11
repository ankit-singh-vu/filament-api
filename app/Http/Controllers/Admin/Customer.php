<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\UserController;
use App\Models\Tenant;
use Illuminate\Http\Request;                  

class Customer extends Controller
{
    //
    public static function customers() {
        $customers = Tenant::all();
        return view('admin.customerlist', ['customers' => $customers]);
    }

    public function Add_customer(Request $request) {
        // $partnerdata = userData();
        $content = $request->first;
        $userdata = array("email"=>$request->email,
                        "first"=>$request->first,
                        "middle"=>$request->middle,
                        "last"=>$request->last,
                        "password"=>$request->password,
                        "tenants"=>$request->tenants
    );
        $usercontroller = new UserController;
        $create_user = $usercontroller->registration($userdata);
        $responseContent = $create_user->getContent();
        $responseData = json_decode($responseContent, true);
        if($responseData['message'] == 'User Created Successfully'){
            // $update_partner_id = Tenant::where('id', $responseData['tenant_id'])
                                        // ->update(["partner_id"=>$partnerdata->id]);
            $customers = Tenant::get();
            // return view('admin.customerlist', ['customers' => $customers]);
            return redirect("/customers");
        }else{
            $errors = [$responseData['message']];
            return redirect()->back()->withErrors($errors);
        }
        
    }
}
