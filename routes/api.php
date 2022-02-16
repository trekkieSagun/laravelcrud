<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);



Route::middleware('auth:api')->group(function () {
    // return $request->user();
    Route::post("addData", [BlogController::class, 'addData']);
    Route::put("update/{id}", [BlogController::class, 'update']);
    Route::delete("delete/{id}", [BlogController::class, 'delete']);
    Route::get("getData/{id?}", [BlogController::class, 'getData']);
    Route::post("/like", [BlogController::class, 'like']);
    Route::get("/getUsers", [UserController::class, 'getUsers']);
});







Route::get("/get-published-blog", [BlogController::class, 'getPublishedBlog']);

// blog publish

Route::put("publishBlog/{id}", [BlogController::class, "publishBlog"]);

//total likes

Route::post("/totalLikes", [BlogController::class, 'totalLikes']);
