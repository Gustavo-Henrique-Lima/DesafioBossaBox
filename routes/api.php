<?php

use App\Http\Controllers\api\TagController;
use App\Http\Controllers\api\ToolController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get("tags", [TagController::class, "getAllTags"]);
Route::post("tags", [TagController::class, "store"]);
Route::delete("tags/{id}", [TagController::class, "delete"]);

Route::get("tools", [ToolController::class, "getTools"]);
Route::post("tools", [ToolController::class, "store"]);
Route::delete("tools/{id}", [ToolController::class, "delete"]);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
