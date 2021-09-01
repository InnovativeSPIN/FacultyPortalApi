<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post("/register", [App\Http\Controllers\faculty\users\UserAuthController::class, 'newRegister']);
Route::post("/login", [App\Http\Controllers\faculty\users\UserAuthController::class, 'userLogin']);
Route::post("/forgot_password", [App\Http\Controllers\faculty\users\UserAuthController::class, 'forgotPassRequest']);
Route::get("/forgot_password/{faculty_id}/{otp}", [App\Http\Controllers\faculty\users\UserAuthController::class, 'forgotPassOTPcheck']);
Route::post("/change_password", [App\Http\Controllers\faculty\users\UserAuthController::class, 'PasswordChange']);

Route::post("/feedback", [App\Http\Controllers\faculty\users\UserAuthController::class, 'FeedBack']);