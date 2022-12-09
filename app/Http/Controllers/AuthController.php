<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
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
            if ($cek1->count() == 1 || $cek2->count() == 1) :
                $erapor5 = $cek1->first();
                $erapor6 = $cek2->first();
                if (password_verify($req->password, $erapor5->password) || password_verify($req->password, $erapor6->password)) :
                    $data = [
                        "erapor5_user_id" => $erapor5->user_id,
                        "erapor5_peserta_didik_id" => $erapor5->peserta_didik_id,
                        "erapor5_guru_id" => $erapor5->guru_id,
                        "erapor6_user_id" => $erapor6->user_id,
                        "erapor6_peserta_didik_id" => $erapor6->peserta_didik_id,
                        "erapor6_guru_id" => $erapor6->guru_id,
                        "sekolah_id" => $erapor5->sekolah_id,
                        "name" => $erapor5->name,
                        "email" => $erapor5->email,
                        "semester_id" => $req->semester,
                    ];
                    Session::put($data);
                    return redirect()->route("home")->with("success", "Selamat datang! ". ucwords(strtolower($erapor6->name)));
                else:
                    return back()->with("error", "Password Salah!");
                endif;
            else :
                return back()->with("error", "Pengguna tidak ditemukan!");
            endif;
        endif;
    }
    function logout() {
        Session::flush();
        return redirect("/");
    }
}
