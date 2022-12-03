<?php

use Illuminate\Support\Facades\Route;

Route::get("/", "AuthController@index");
Route::post("/login", "AuthController@login")->name("login");

Route::get("/home", "WelcomeController@home")->name("home");
