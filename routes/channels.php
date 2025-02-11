<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('cluster-job-channel', function () {
    // return (int) $user->id === (int) $id;
    return true;
});
