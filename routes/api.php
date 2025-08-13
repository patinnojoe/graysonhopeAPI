<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\Pagecontroller;
use App\Http\Controllers\PostContoller;
use App\Http\Controllers\RegistrationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::post('/register', [RegistrationController::class, 'register']);
Route::post('/login', [RegistrationController::class, 'login']);
Route::get('/verify-user', [RegistrationController::class, 'verifyUser'])->middleware('auth:sanctum');
Route::put('/users/{id}', [RegistrationController::class, 'updateUser'])->middleware('auth:sanctum');


Route::get('/volunteers', [Pagecontroller::class, 'volunteer'])->middleware('auth:sanctum');

Route::post('/create-post', [PostContoller::class, 'createPost'])->middleware('auth:sanctum');
Route::post('/update-post/{id}', [PostContoller::class, 'updatePost'])->middleware('auth:sanctum');
Route::delete('/delete-post/{id}', [PostContoller::class, 'deletePost'])->middleware('auth:sanctum');
Route::get('/all-posts', [PostContoller::class, 'getPosts']);
Route::get('/all-paginated-posts', [PostContoller::class, 'getPaginatedPosts']);
Route::get('/post/{id}', [PostContoller::class, 'getPostById']);
Route::post('/contact', [ContactController::class, 'send']);


Route::delete('/users/{id}', [RegistrationController::class, 'deleteUser'])
    ->middleware('auth:sanctum')
    ->name('users.delete');
