<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

});

require __DIR__.'/auth.php';


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */

        Route::get('/dashboard', DashboardController::class)
            ->name('dashboard');


        /*
        |--------------------------------------------------------------------------
        | Posts
        |--------------------------------------------------------------------------
        */

        Route::get('/posts', [PostController::class, 'index'])
            ->name('posts.index');

        Route::get('/posts/create', [PostController::class, 'create'])
            ->name('posts.create');

        Route::post('/posts', [PostController::class, 'store'])
            ->name('posts.store');

        Route::put('/posts/{post}', [PostController::class, 'update'])
            ->name('posts.update');

        Route::delete('/posts/{post}', [PostController::class, 'destroy'])
            ->name('posts.destroy');

        Route::get('/posts/{post}', [PostController::class, 'show'])
            ->name('posts.show');

        Route::get('/posts/{post}/edit', [PostController::class, 'edit'])
            ->name('posts.edit');


        /*
        |--------------------------------------------------------------------------
        | Categories
        |--------------------------------------------------------------------------
        */

        Route::get('/categories', [CategoryController::class, 'index'])
            ->name('categories.index');

        Route::get('/categories/create', [CategoryController::class, 'create'])
            ->name('categories.create');

        Route::post('/categories', [CategoryController::class, 'store'])
            ->name('categories.store');

        Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])
            ->name('categories.edit');

        Route::put('/categories/{category}', [CategoryController::class, 'update'])
            ->name('categories.update');

        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])
            ->name('categories.destroy');

        /*
        |--------------------------------------------------------------------------
        | Tags
        |--------------------------------------------------------------------------
        */

        Route::get('/tags', [TagController::class, 'index'])
            ->name('tags.index');

        Route::get('/tags/create', [TagController::class, 'create'])
            ->name('tags.create');

        Route::post('/tags', [TagController::class, 'store'])
            ->name('tags.store');

        Route::get('/tags/{tag}/edit', [TagController::class, 'edit'])
            ->name('tags.edit');

        Route::put('/tags/{tag}', [TagController::class, 'update'])
            ->name('tags.update');

        Route::delete('/tags/{tag}', [TagController::class, 'destroy'])
            ->name('tags.destroy');


        /*
        |--------------------------------------------------------------------------
        | Users
        |--------------------------------------------------------------------------
        */


        Route::get('/users', [UserController::class, 'index'])
            ->name('users.index');

        Route::get('/users/create', [UserController::class, 'create'])
            ->name('users.create');

        Route::post('/users', [UserController::class, 'store'])
            ->name('users.store');

        Route::get('/users/{user}', [UserController::class, 'show'])
            ->name('users.show');

        Route::get('/users/{user}/edit', [UserController::class, 'edit'])
            ->name('users.edit');

        Route::put('/users/{user}', [UserController::class, 'update'])
            ->name('users.update');

        Route::delete('/users/{user}', [UserController::class, 'destroy'])
            ->name('users.destroy');


        /*
        |--------------------------------------------------------------------------
        | Settings
        |--------------------------------------------------------------------------
        */

        Route::get('/settings', [SettingController::class, 'index'])
            ->name('settings.index');

        Route::put('/settings', [SettingController::class, 'update'])
            ->name('settings.update');
    });