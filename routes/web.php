<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GovernorateController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/main', function () {
    return view('pages.main');
});

//dashboard
Route::get("/tools", function () {
    return view("pages.tools");
})->name("tools.main");

Route::get(("/categories/subs/{id}"), [CategoryController::class, "getSubs"])->name("categories.getSubs");
Route::resource("categories", CategoryController::class);
Route::resource("governorates", GovernorateController::class);
Route::resource("products", ProductController::class);
Route::resource("orders", OrderController::class);
