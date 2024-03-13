<?php

use App\Http\Controllers\api\TagController;
use App\Http\Controllers\api\ToolController;
use App\Http\Controllers\api\AuthController;
use Illuminate\Support\Facades\Route;


Route::prefix("auth")->group(function () {
    Route::post("login", [AuthController::class, "login"]);
    Route::post("logout", [AuthController::class, "logout"]);
});

Route::get("tags", [TagController::class, "getAllTags"]);
Route::get("tools", [ToolController::class, "getTools"]);

Route::group(["middleware" => ["protected"]], function () {
    Route::prefix("tags")->group(function () {
        Route::post("/", [TagController::class, "store"]);
        Route::delete("/{id}", [TagController::class, "delete"]);
    });
    Route::prefix("tools")->group(function () {
        Route::post("/", [ToolController::class, "store"]);
        Route::delete("/{id}", [ToolController::class, "delete"]);
    });
});
