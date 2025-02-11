<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Models\Partners;
use Illuminate\Support\Facades\Validator;

class Settingscontroller extends Controller
{
    public function index(Request $request) {
        $partner_id = userData()->id;
        $partner_details = Partners::where('status', 1)
                                    ->where('id', $partner_id)->first();
        return view('partner.settings.index', ["partner_details"=>$partner_details]);
    }
    public function Updategen(Request $request) {
        $partner_id = userData()->id;
        $validator = Validator::make( $request->all(), [
            'partner_name' => 'required|string|max:255',
            ],
            [
            
            'partner_name.required' => 'Please provide partner name'
            ] );
            
            if ( $validator->fails() ) {
            return response()->json( [ 'errors' => $validator->errors() ] );
            }
            
            $update_array = array(
                'partner_name' => $request->input('partner_name'),
                'phone' => $request->input('phone'),
                'email' => $request->input('email'),
                'adf_name' => $request->input('adf_name'),
                'partner_url' => $request->input('partner_url'),
                'welcome_text' => $request->input('welcome_text')
                );
            
            $update_partner = Partners::where('id', $partner_id)
                                ->update($update_array);
            return response()->json( [ 'success' => 'Settings updated successfully!' ] );
    }
}
