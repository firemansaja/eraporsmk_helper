<?php

use Illuminate\Support\Facades\Route;

Route::get("/", "AuthController@index");
Route::post("/login", "AuthController@login")->name("login");

Route::get("/home", "WelcomeController@home")->name("home");
Route::prefix('ketidakhadiran')->group(function () {
    Route::get("/", "KetidakhadiranController@index")->name("ketidakhadiran");
    Route::post("/simpan", "KetidakhadiranController@simpan")->name("ketidakhadiran.simpan");
});
