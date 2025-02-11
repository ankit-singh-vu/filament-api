<?php
use Illuminate\Support\Facades\Cookie;
use App\Models\Partners;
use App\Models\user_profile;
use App\Models\Tenant;
use App\Models\UserAccess;

if (! function_exists('curlPost')) {
    function curlPost($url,$data)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => 1,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false, 
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                "content-type: application/json"
            ),
        ));
        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            echo 'Curl error: ' . curl_error($curl);
        }
        curl_close($curl);
        return $response;
    }
}

if (! function_exists('curlGet')) {
    function curlGet($url)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Cookie: PHPSESSID=18upfcngclfap3dm7jlkr1r2gn'
        ),
        ));

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            echo 'Curl error: ' . curl_error($curl);
        }

        curl_close($curl);
        return $response;
    }
}
if (! function_exists('userData')) {
    function userData()
    {
        $access_token = Cookie::get('access_token');
        if($access_token != ''){
            $partner_data = Partners::join('access_tokens', 'access_tokens.user_id', '=', 'partners.user_id')
                            ->select('partners.id','partners.user_id', 'partners.partner_name', 'partners.partner_company_name')
                            ->where('access_tokens.access_token', $access_token)
                            ->first();
            return $partner_data;
        }else{
            $errors = ['Please login to continue..'];
            abort(redirect('/')->withErrors($errors));

        }
    }
}

if (! function_exists('checkPartner')) {
    function checkPartner($user,$url){
        $url_arr = explode('//', $url);
        $main_url = explode('/', $url_arr[1]);
        $new_url = $main_url[0];

        $main_domain = explode("//",getenv("APP_URL"))[1];
        $partner_domain="partner.".$main_domain;

        $tenant = user_profile::join('tenants', 'user_profiles.c_tenant', '=', 'tenants.id')
                            ->select('tenants.partner_id')
                            ->where('user_profiles.email', $user)
                            ->first();
        if(isset($tenant->partner_id) && $tenant->partner_id != NULL){
            $partner = Partners::select('partner_url')
                        ->where('id',$tenant->partner_id)->first();
            $chk_partner = UserAccess::select('access_type')
                        ->where('username',$user)->first();
                        // dd($chk_partner->access_type);
                        
            if($new_url == $partner_domain && $chk_partner->access_type == 'partner'){
                return true;
            }elseif($partner->partner_url == $new_url){
                return true;
            }else{
                return false;
            }

        }else{
            return true;
        }

    }
}