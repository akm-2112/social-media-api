<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserInterestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Phiki\Phast\Root;

//

//Auth Routes
Route::post('/register',[AuthController::class,'register'])->name('register');
Route::post('/login',[AuthController::class,'login'])->name('login');
Route::post('/logout',[AuthController::class,'logout'])->middleware('auth:sanctum')->name('logout');

//User Profile Routes
Route::middleware('auth:sanctum')->group(function(){
    Route::get('/user', [UserController::class, 'show'])->name('user.show');
    Route::put('/user',[UserController::class,'update'])->name('user.update');
    Route::delete('/user',[UserController::class,'destroy'])->name('user.delete');
});

//Post Routes
Route::get('/posts',[PostController::class,'index'])->name('post.index');
Route::get('/posts/{post}',[PostController::class,'show'])->name('post.show');
Route::middleware('auth:sanctum')->group(function(){
    Route::post('/posts',[PostController::class,'store'])->name('post.store');
    Route::put('/posts/{post}',[PostController::class,'update'])->name('post.update');
    Route::delete('/posts/{post}',[PostController::class,'destroy'])->name('post.delete');
    Route::get('/my-posts',[PostController::class,'myPosts'])->name('post.myPosts');
});

//Like Routes
Route::middleware('auth:sanctum')->group(function(){
    Route::post('/posts/{post}/like', [LikeController::class,'store'])->name('post.like');
    Route::delete('/posts/{post}/like',[LikeController::class,'destroy'])->name('post.unlike');
});

//Comment Routes
Route::middleware('auth:sanctum')->group(function(){
    Route::get('/posts/{post}/comment',[CommentController::class,'index'])->name('comment.index');
    Route::post('/posts/{post}/comment', [CommentController::class,'store'])->name('comment.store');
    Route::put('/posts/{post}/comment', [CommentController::class,'update'])->name('comment.update');
    Route::delete('/posts/{post}/comment',[CommentController::class,'destroy'])->name('comment.delete');
});

//Follow Routes
Route::get('/users/{user}/following',[FollowController::class,'getFollowing'])->name('user.following');
Route::get('/users/{user}/followers',[FollowController::class,'getFollowers'])->name('user.followers');
Route::middleware('auth:sanctum')->group(function(){
    Route::post('/users/{user}/follow', [FollowController::class,'follow'])->name('user.follow');
    Route::delete('/users/{user}/unfollow', [FollowController::class,'unfollow'])->name('user.unfollow');
});

//Categories Routes
Route::get('/categories', [CategoryController::class,'getAllCategories'])->name('categories.show');
Route::get('/categories/{$category}/posts', [CategoryController::class,'getPostByCategory'])->name('categories.posts');
Route::middleware('auth:sanctum')->group(function(){
    Route::post('/categories', [CategoryController::class,'store'])->name('category.store');
    Route::delete('/categories/{category}', [CategoryController::class,'destroy'])->name('category.delete');
});

//UserInterests Routes
Route::middleware('auth:sanctum')->group(function(){
    Route::get('/user/interests', [UserInterestController::class, 'showInterests'])->name('interests.show');
    Route::post('/user/interests', [UserInterestController::class,'setInterests'])->name('interests.set');
});