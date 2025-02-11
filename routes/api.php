<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Whoops\Run;



Route::get('/test', function (Request $request) {
    return "hello  ";
});
