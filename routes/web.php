<?php

use Illuminate\Support\Facades\Route;

Route::get("/", "AuthController@index");
Route::post("/login", "AuthController@login")->name("login");

Route::get("/home", "WelcomeController@home")->name("home");
Route::prefix('ketidakhadiran')->group(function () {
    Route::get("/", "KetidakhadiranController@index")->name("ketidakhadiran");
    Route::post("/simpan", "KetidakhadiranController@simpan")->name("ketidakhadiran.simpan");
});

Route::prefix('sikap')->group(function () {
    Route::get("/", "SikapController@index")->name("sikap");
    Route::get("/template/{rombongan_belajar_id}", "SikapController@template")->name("sikap.template");
    Route::get("/nilai/{anggota_rombel_id}", "SikapController@nilai")->name("sikap.nilai");
    Route::post("/simpan", "SikapController@simpan")->name("sikap.simpan");
    Route::post("/import", "SikapController@import")->name("sikap.import");
});

Route::prefix('karakter')->group(function () {
    Route::get("/", "KarakterController@index")->name("karakter");
    Route::get("/nilai/{anggota_rombel_id}", "KarakterController@nilai")->name("karakter.nilai");
    // Route::get("/template/{rombongan_belajar_id}", "SikapController@template")->name("sikap.template");
    Route::post("/simpan", "KarakterController@simpan")->name("karakter.simpan");
    // Route::post("/import", "SikapController@import")->name("sikap.import");
});
