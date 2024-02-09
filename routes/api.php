<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UsersController;
use App\Http\Controllers\API\ArticlesController;
use App\Http\Controllers\Api\SecurityController;
use App\Http\Controllers\API\PointPicturesController;
use App\Http\Controllers\API\InterestPointsController;
use App\Http\Controllers\API\ArticlePicturesController;
use App\Http\Controllers\API\PointCategoriesController;
use App\Http\Controllers\API\ArticleCategoriesController;
use Illuminate\Support\Facades\Auth;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('/categories')->group(function () {
    Route::get('/', [ArticleCategoriesController::class, 'index']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', function(){
        return Auth::user();
    });
});

Route::post('/logout', [SecurityController::class, 'logout'])->middleware('auth:sanctum');;

Route::prefix('/users')->group(function(){
    Route::get('/', [UsersController::class, 'index']);
    Route::get('/{user}', [UsersController::class, 'show']);
    Route::post('/', [UsersController::class, 'store'])->middleware('auth:sanctum');
    Route::post('/edit/{user}', [UsersController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/{user}', [UsersController::class, 'destroy'])->middleware('auth:sanctum');
});

Route::prefix('/articles')->group(function(){
    Route::get('/', [ArticlesController::class, 'index']);
    Route::get('/{article}', [ArticlesController::class, 'show']);
    Route::post('/', [ArticlesController::class, 'store'])->middleware('auth:sanctum');
    Route::post('/edit/{article}', [ArticlesController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/{article}', [ArticlesController::class, 'destroy'])->middleware('auth:sanctum');
});

Route::prefix('/article-categories')->group(function(){
    Route::get('/', [ArticleCategoriesController::class, 'index']);
    Route::get('/{category}', [ArticleCategoriesController::class, 'show']);
    Route::post('/', [ArticleCategoriesController::class, 'store'])->middleware('auth:sanctum');
    Route::post('/edit/{category}', [ArticleCategoriesController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/{category}', [ArticleCategoriesController::class, 'destroy'])->middleware('auth:sanctum');
});
Route::prefix('/point-categories')->group(function(){
    Route::get('/', [PointCategoriesController::class, 'index']);
    Route::get('/{category}', [PointCategoriesController::class, 'show']);
    Route::post('/', [PointCategoriesController::class, 'store'])->middleware('auth:sanctum');
    Route::post('/edit/{category}', [PointCategoriesController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/{category}', [PointCategoriesController::class, 'destroy'])->middleware('auth:sanctum');
});


Route::prefix('/interest-points')->group(function () {
    Route::get('/', [InterestPointsController::class, 'index']);
    Route::get('/{point}', [InterestPointsController::class, 'show']);
    Route::post('/interest-points', [InterestPointsController::class, 'store'])->middleware('auth:sanctum');
    Route::post('/edit/{interest-point}', [InterestPointsController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/{interest-point}', [InterestPointsController::class, 'destroy'])->middleware('auth:sanctum');
});

Route::prefix('/security')->group(function () {
    Route::post('/register', [SecurityController::class, 'register'])->middleware('guest')->name('security.register');
    Route::post('/login', [SecurityController::class, 'login'])->middleware(['guest'])->name('security.login');
});
