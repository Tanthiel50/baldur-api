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

// Route::prefix('/categories')->group(function () {
//     Route::get('/', [ArticleCategoriesController::class, 'index']);
// });

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
    Route::get('/category/{categoryId}', [ArticlesController::class, 'getArticlesByCategory']);
    Route::post('/', [ArticlesController::class, 'store'])->middleware('auth:sanctum');
    Route::post('/edit/{article}', [ArticlesController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/{article}', [ArticlesController::class, 'destroy'])->middleware('auth:sanctum');
});

Route::prefix('/articlecategories')->group(function(){
    Route::get('/', [ArticleCategoriesController::class, 'index']);
    Route::get('/{category}', [ArticleCategoriesController::class, 'show']);
    Route::post('/', [ArticleCategoriesController::class, 'store'])->middleware('auth:sanctum');
    Route::post('/edit/{category}', [ArticleCategoriesController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/{category}', [ArticleCategoriesController::class, 'destroy'])->middleware('auth:sanctum');
});
Route::prefix('/pointcategories')->group(function(){
    Route::get('/', [PointCategoriesController::class, 'index']);
    Route::get('/{category}', [PointCategoriesController::class, 'show']);
    Route::post('/', [PointCategoriesController::class, 'store'])->middleware('auth:sanctum');
    Route::post('/edit/{category}', [PointCategoriesController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/{category}', [PointCategoriesController::class, 'destroy'])->middleware('auth:sanctum');
});


Route::prefix('/interestpoints')->group(function () {
    Route::get('/', [InterestPointsController::class, 'index']);
    Route::get('/category/{categoryId}', [InterestPointsController::class, 'getInterestPointsByCategory']);
    Route::get('/{interestpoint}', [InterestPointsController::class, 'show']);
    Route::post('/', [InterestPointsController::class, 'store'])->middleware('auth:sanctum');
    Route::post('/edit/{interestpoint}', [InterestPointsController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/{interestpoint}', [InterestPointsController::class, 'destroy'])->middleware('auth:sanctum');
});

Route::prefix('/articlepictures')->group(function () {
    Route::get('/', [ArticlePicturesController::class, 'index'])->middleware('auth:sanctum');
    Route::get('/{articlepicture}', [ArticlePicturesController::class, 'show'])->middleware('auth:sanctum');
    Route::post('/', [ArticlePicturesController::class, 'store'])->middleware('auth:sanctum');
    Route::post('/edit/{articlepicture}', [ArticlePicturesController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/{articlepicture}', [ArticlePicturesController::class, 'destroy'])->middleware('auth:sanctum');
});

Route::prefix('/pointpictures')->group(function () {
    Route::get('/', [PointPicturesController::class, 'index'])->middleware('auth:sanctum');
    Route::get('/{pointpicture}', [PointPicturesController::class, 'show'])->middleware('auth:sanctum');
    Route::post('/', [PointPicturesController::class, 'store'])->middleware('auth:sanctum');
    Route::post('/edit/{pointpicture}', [PointPicturesController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/{pointpicture}', [PointPicturesController::class, 'destroy'])->middleware('auth:sanctum');
});


Route::prefix('/security')->group(function () {
    Route::post('/register', [SecurityController::class, 'register'])->middleware('guest')->name('security.register');
    Route::post('/login', [SecurityController::class, 'login'])->middleware(['guest'])->name('security.login');
});
