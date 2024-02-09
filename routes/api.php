<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('articles', 'App\Http\Controllers\API\ArticlesController');
Route::apiResource('article-categories', 'App\Http\Controllers\API\ArticleCategoriesController');
Route::apiResource('point-categories', 'App\Http\Controllers\API\PointCategoriesController');
Route::apiResource('interest-points', 'App\Http\Controllers\API\InterestPointsController');
Route::apiResource('users', 'App\Http\Controllers\API\UsersController');
Route::apiResource('article-pictures', 'App\Http\Controllers\API\ArticlePicturesController');
Route::apiResource('point-pictures', 'App\Http\Controllers\API\PointPicturesController');
