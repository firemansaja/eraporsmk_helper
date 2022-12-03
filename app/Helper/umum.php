<?php

use Illuminate\Support\Facades\Session;

date_default_timezone_set("Asia/Jakarta");

function repo($url) {
    return "http://60.60.60.58/lte/2.4.10/" . $url;
}
function sesi($id) {
    return Session::get($id);
}
