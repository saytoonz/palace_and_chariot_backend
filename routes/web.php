<?php

use Illuminate\Support\Facades\Route;
use Intervention\Image\Laravel\Facades\Image;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    $image = Image::read(storage_path().'/app/public/user_data/avatar/avatar_165d109555c491.png');
    return $image;
});
