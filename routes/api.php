<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Whoops\Run;



Route::get('/test', function (Request $request) {
    return "hello  ";
});

Route::get('/redirect', function (Request $request) {
    $request->session()->put('state', $state = Str::random(40));

    $query = http_build_query([
        'client_id' => 'client-id',
        'redirect_uri' => 'http://third-party-app.com/callback',
        'response_type' => 'code',
        'scope' => '',
        'state' => $state,
        // 'prompt' => '', // "none", "consent", or "login"
    ]);

    return redirect('http://passport-app.test/oauth/authorize?'.$query);
});


Route::get('/y', function (Request $request) {
    return response()->json(['message' => 'Hello from endpoint Y!']);
});


Route::get('/x', function () {
    // Call endpoint Y internally without an actual HTTP request
    // $response = Http::withoutVerifying()->get('http://convesio.local/api/y');


        // $url = 'https://convesio.local/api/y';
        $url = 'https://192.168.62.101/api/y';

         // Perform an HTTP GET request to the constructed URL using a helper function
        $response = curlGet($url);

        // Decode the JSON response from the Nomad API
        // $response = json_decode($response);


    return response()->json([
        'message' => 'Hello from endpoint X!'
        // 'y_response' => json_decode($response->getContent(), true),
    ]);
});
