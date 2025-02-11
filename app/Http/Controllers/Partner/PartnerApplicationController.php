<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Application;

class PartnerApplicationController extends Controller
{
    public function Applications(Request $request) {
        $user_id = userData()->user_id;
        $applications_data = Application::where('user_id', $user_id)
                                    ->where('status',1)
                                    ->get();
        return view('partner.applist',["applications_data"=>$applications_data]);
    }
}
