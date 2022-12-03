<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller {
    function index(Request $req) {
        $pass["daftar_semester"] = erapor6("ref.tahun_ajaran")
        ->join("ref.semester", "ref.semester.tahun_ajaran_id", "=", "ref.tahun_ajaran.tahun_ajaran_id")
        ->where("ref.tahun_ajaran.periode_aktif", 1)
        ->orderBy("semester_id", "desc")
        ->get();
        return view("login", $pass);
    }
    function login(Request $req) {
        if ($req->has("submit")) :
            $validator = Validator::make($req->all(), [
                "username" => "required|email",
                "password" => "required",
                "semester" => "required"
            ]);
            if ($validator->fails()) return Redirect::back()->withErrors($validator->errors());
            $cek1 = erapor5("users")->where("email", $req->username);
            $cek2 = erapor6("users")->where("email", $req->username);
            if ($cek1->count() == 1 || $cek2->count() == 1) {
                $erapor5 = $cek1->first();
                $erapor6 = $cek2->first();
                if (password_verify($req->password, $erapor5->password) || password_verify($req->password, $erapor6->password)) :
                    echo "<pre>";
                    print_r($erapor5);
                    print_r($erapor6);
                    echo "</pre>";
                endif;
            }
        endif;
    }
}
