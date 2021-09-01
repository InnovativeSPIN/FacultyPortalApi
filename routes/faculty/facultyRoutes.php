<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix("/user")->group(__DIR__.'/userRoutes.php');