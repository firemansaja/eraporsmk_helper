<?php

use Illuminate\Support\Facades\DB;

function erapor5($table) {
    return DB::connection("erapor5")
    ->table($table);
}
function erapor6($table) {
    return DB::connection("erapor6")
    ->table($table);
}
function semester() {
    return erapor6("ref.semester")->where("periode_aktif", 1)->first();
}
