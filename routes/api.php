<?php

use App\Filament\Pages\Auth\Register;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FollowerController;
use App\Http\Controllers\LikeController;
use Filament\Pages\Auth\Login;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->get('/user', [AuthController::class, 'user']);

Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{id}', [UserController::class, 'show']);
Route::post('/users', [UserController::class, 'store']);
Route::post('/users/{id}', [UserController::class, 'update']);
Route::delete('/users/{id}', [UserController::class, 'destroy']);
Route::get('/users/nick/{username}', [UserController::class, 'showByUsername']);
Route::get('/users/nick/{username}/events', [UserController::class, 'getUserEventsByUsername']);
Route::get('/users/nick/{username}/followers', [UserController::class, 'getUserFollowers']);
Route::get('/users/nick/{username}/followings', [UserController::class, 'getUserFollowings']);
Route::get('/user/search', [UserController::class, 'search']);
Route::get('users/{id}/likes', [UserController::class, 'getUserLikes']);

Route::get('/events', [EventController::class, 'index']);
Route::get('/events/{id}', [EventController::class, 'show']);
Route::post('/events', [EventController::class, 'store']);
Route::put('/events/{id}', [EventController::class, 'update']);
Route::delete('/events/{id}', [EventController::class, 'destroy']);
Route::get('/events/{id}/comments', [EventController::class, 'getEventComments']);
Route::get('/event/search', [EventController::class, 'search']);

Route::post('/events/{event}/like', [LikeController::class, 'like']);
Route::delete('/events/{event}/like', [LikeController::class, 'unlike']);

Route::get('/followers', [FollowerController::class, 'index']);
Route::get('/followers/{id}', [FollowerController::class, 'show']);
Route::post('/followers', [FollowerController::class, 'store']);
Route::put('/followers/{id}', [FollowerController::class, 'update']);
Route::delete('/followers/{id}', [FollowerController::class, 'destroy']);

Route::get('/tags', [TagController::class, 'index']);
Route::get('/tags/{id}', [TagController::class, 'show']);
Route::post('/tags', [TagController::class, 'store']);
Route::put('/tags/{id}', [TagController::class, 'update']);
Route::delete('/tags/{id}', [TagController::class, 'destroy']);

Route::get('/comments', [CommentController::class, 'index']);
Route::get('/comments/{id}', [CommentController::class, 'show']);
Route::post('/comments', [CommentController::class, 'store']);
Route::put('/comments/{id}', [CommentController::class, 'update']);
Route::delete('/comments/{id}', [CommentController::class, 'destroy']);