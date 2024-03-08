<?php

use App\Http\Controllers\api\TagController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get("tags",[TagController::class,"getAllTags"]);
Route::post("tags",[TagController::class,"store"]);
Route::delete("tags/{id}",[TagController::class,"delete"]);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
