<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use App\Models\Partners;
use App\Models\Tenant;

class CustomerController extends Controller
{
    public function customers(Request $request) {
        $data = userData();
        $customers = Tenant::select('id','uuid','name','email')
                            ->where('partner_id', $data->id)->get();
        if(empty($customers)){
            return view('partner.emptycustomer');
        }else{
            return view('partner.customerlist', ['customers' => $customers]);
        }
    }

    public function Add_customer(Request $request) {
        $partnerdata = userData();
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
            $update_partner_id = Tenant::where('id', $responseData['tenant_id'])
                                        ->update(["partner_id"=>$partnerdata->id]);
            $customers = Tenant::select('uuid','name','email')
                                        ->where('partner_id', $partnerdata->id)->get();
            // return view('partner.customerlist', ['customers' => $customers]);
            return redirect("/customers");
        }else{
            $errors = [$responseData['message']];
            return redirect()->back()->withErrors($errors);
        }
        
    }

    public function demopage(Request $request) {
        $partner_details = Partners::where('status', 1)->first();
        return view('partner.demo', ["partner_details"=>$partner_details]);
    }
}
