<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\GovernorateController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\SubOrderController;
use App\Http\Middleware\cors;
use App\Models\SubOrder;
use Illuminate\Support\Facades\Route;



Route::get("/optimize", function () {
    \Artisan::call("optimize");
    dd("optimized");
});
Route::get('/', function () {
    return view('pages.main');
})->name("main");
Route::get('/csrf', function () {
    return csrf_token();
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
Route::resource("products", ProductController::class)->middleware("cors");
Route::resource("orders", OrderController::class);

Route::delete("/suborder/delete/{id}", [SubOrderController::class, "delete"])->name("sub.delete");
Route::resource("suborders", SubOrderController::class);

Route::resource("status", StatusController::class);

Route::resource("deliveries", DeliveryController::class);
