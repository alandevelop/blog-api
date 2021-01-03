<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\CommentsController;
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

Route::group([
    'prefix' => 'posts',
    'where' => ['post_id' => '[0-9]+'],
], function () {
    Route::get('/', [PostController::class, 'index'])->name('posts.index');
    Route::post('/', [PostController::class, 'store'])->name('posts.store');
    Route::get('/{post_id}', [PostController::class, 'show'])->name('posts.show');
    Route::patch('/{post_id}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/{post_id}', [PostController::class, 'destroy'])->name('posts.destroy');

    Route::group([
        'prefix' => '/{post_id}/comments',
        'where' => ['post_id' => '[0-9]+', 'comment_id' => '[0-9]+'],
    ], function () {
        Route::get('/', [CommentsController::class, 'index'])->name('posts.comments.index');
        Route::post('/', [CommentsController::class, 'store'])->name('posts.comments.store');
        Route::get('/{comment_id}', [CommentsController::class, 'show'])->name('posts.comments.show');
        Route::patch('/{comment_id}', [CommentsController::class, 'update'])->name('posts.comments.update');
        Route::delete('/{comment_id}', [CommentsController::class, 'destroy'])->name('posts.comments.destroy');
    });
});



Route::get('/', function () {
    return route('posts.store');
});

