<?php

use App\Events\SendNotification;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\DeliveryStatusController;
use App\Http\Controllers\FournisseurController;
use App\Http\Controllers\GovernorateController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OrderCategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SourceController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\SubOrderController;
use App\Http\Middleware\cors;
use App\Models\Delivery_status;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Product;
use App\Models\SubOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;



Route::get("/optimize", function () {
    \Artisan::call("optimize");
    dd("optimized");
});

Route::get('/', function () {
    return redirect()->to("/main");
});
Route::get('/login', function () {
    if (!Auth::check())
        return view("pages.login");
    else return  redirect()->to('/main');
})->name("login");
Route::get('/logout', function () {
    Auth::logout();
    return redirect()->to("/login");
})->name("logout");
Route::post('/user/auth', [AuthController::class, "Auth"])->name("users.auth");
Route::get('/csrf', function () {
    return csrf_token();
});


Route::group(["middleware" => ["auth"]], function () {
    Route::get('/main', function () {
        return view('pages.main');
    })->name("main");

    Route::get('/chats/content/{user}/{chat_id}', function ($user, $chat_id) {
        return view('chats.content', compact("user", "chat_id"));
    })->name("chats.content");
    Route::get('/chats/empty', function () {
        return view('chats.empty');
    })->name("chats.empty");
    //dashboard
    Route::resource("chats", ChatController::class);
    Route::resource("messages", MessageController::class);
    Route::get("/tools", function () {
        return view("pages.tools");
    })->name("tools.main");

    Route::get(("/categories/subs/{id}"), [CategoryController::class, "getSubs"])->name("categories.getSubs");
    Route::resource("categories", CategoryController::class);
    Route::resource("governorates", GovernorateController::class);


    //products
    Route::get("products/table", [ProductController::class, "table"])->name("products.table");
    Route::resource("products", ProductController::class);

    //orders
    Route::get("/orders/table/{cat}/{stat}/{reg}/{search}", [OrderController::class, "table"])->name("orders.table");
    Route::resource("orders", OrderController::class);

    Route::delete("/suborder/delete/{id}", [SubOrderController::class, "delete"])->name("sub.delete");
    Route::resource("suborders", SubOrderController::class);

    Route::resource("status", StatusController::class);
    Route::resource("sources", SourceController::class);

    Route::post("deliveries/update/{id}/{status}", [DeliveryController::class, "statusUpdate"])->name("deliveries.upd");
    Route::resource("deliveries", DeliveryController::class);
    Route::resource("order_cats", OrderCategoryController::class);


    Route::get("accounts/table", [AccountController::class, "table"])->name("accounts.table");
    Route::resource("accounts", AccountController::class);

    Route::resource("delivery_status", DeliveryStatusController::class);
    Route::put("notifications/empty", [NotificationController::class, "empty"])->name("notif.empty");

    Route::resource("notifications", NotificationController::class);

    Route::get("prestations", [FournisseurController::class, "index"])->name("prestations");
    Route::get("event", function () {
        $title = "Nouvelle tache";
        $content = "Vous avez une nouvelle tâche";
        $user_id = 8;
        $notif = Notification::create(["title" => $title, "content" => $content, "user_id" => $user_id]);
        event(new SendNotification($notif, $user_id));
    });
    Route::post("prestations/{id}", [FournisseurController::class, "statusUpdate"])->name("prestations.statusUpdate");
});
