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
    Route::post('/logout', [SecurityController::class, 'logout']);
    Route::get('/', [UsersController::class, 'index']);
    Route::post('/users', [UsersController::class, 'store']);
    Route::get('/{user}', [UsersController::class, 'show']);
    Route::post('/edit/{user}', [UsersController::class, 'update']);
    Route::delete('/{user}', [UsersController::class, 'destroy']);
    Route::post('/articles', [ArticlesController::class, 'store']);
    Route::post('/edit/{article}', [ArticlesController::class, 'update']);
    Route::delete('/{article}', [ArticlesController::class, 'destroy']);
    Route::post('/article-categories', [ArticleCategoriesController::class, 'store']);
    Route::post('/edit/{article-categorie}', [ArticleCategoriesController::class, 'update']);
    Route::delete('/{article-categorie}', [ArticleCategoriesController::class, 'destroy']);
    Route::post('/point-categories', [PointCategoriesController::class, 'store']);
    Route::post('/edit/{point-categorie}', [PointCategoriesController::class, 'update']);
    Route::post('/{point-categorie}', [PointCategoriesController::class, 'destroy']);
    Route::post('/interest-points', [InterestPointsController::class, 'store']);
    Route::post('/edit/{interest-point}', [InterestPointsController::class, 'update']);
    Route::delete('/{interest-point}', [InterestPointsController::class, 'destroy']);
    Route::get('/', [ArticlePicturesController::class, 'index']);
    Route::post('/article-pictures', [ArticlePicturesController::class, 'store']);
    Route::get('/{article-picture}', [ArticlePicturesController::class, 'show']);
    Route::get('/edit/{article-picture}', [ArticlePicturesController::class, 'update']);
    Route::delete('/{article-picture}', [ArticlePicturesController::class, 'destroy']);
    Route::get('/', [PointPicturesController::class, 'index']);
    Route::post('/point-pictures', [PointPicturesController::class, 'store']);
    Route::get('/{point-picture}', [PointPicturesController::class, 'show']);
    Route::post('/edit/{point-picture}', [PointPicturesController::class, 'update']);
    Route::delete('/{point-picture}', [PointPicturesController::class, 'destroy']);
    Route::get('/', [UsersController::class, 'index']);
    Route::post('/edit/{user}', [UsersController::class, 'update']);
});

// Routes pour les utilisateurs
Route::prefix('/articles')->group(function () {
    Route::get('/', [ArticlesController::class, 'index']);
    Route::get('/{article}', [ArticlesController::class, 'show']);
});

Route::prefix('/article-categories')->group(function () {
    Route::get('/', [ArticleCategoriesController::class, 'index']);
    Route::get('/{categorie}', [ArticleCategoriesController::class, 'show']);
});

Route::prefix('/point-categories')->group(function () {
    Route::get('/', [PointCategoriesController::class, 'index']);
    Route::get('/{categorie}', [PointCategoriesController::class, 'show']);
});

Route::prefix('/interest-points')->group(function () {
    Route::get('/', [InterestPointsController::class, 'index']);
    Route::get('/{point}', [InterestPointsController::class, 'show']);
});

Route::prefix('/security')->group(function () {
    Route::post('/register', [SecurityController::class, 'register'])->middleware('guest')->name('security.register');
    Route::post('/login', [SecurityController::class, 'login'])->middleware(['guest'])->name('security.login');
});
