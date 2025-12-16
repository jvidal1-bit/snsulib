<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

// Student controllers
use App\Http\Controllers\Student\HomeController as StudentHomeController;
use App\Http\Controllers\Student\CatalogController;
use App\Http\Controllers\Student\RequestController;
use App\Http\Controllers\Student\ProfileController;

// Admin controllers
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\BookRequestController as AdminBookRequestController;
use App\Http\Controllers\Admin\BookController as AdminBookController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\SettingsController as AdminSettingsController;
use App\Http\Controllers\Admin\AccountController as AdminAccountController;
/*
|--------------------------------------------------------------------------
| Public / Auth Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])
    ->name('login');

Route::post('/login', [LoginController::class, 'login'])
    ->name('login.attempt');

Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Student Routes (requires login)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {

        Route::get('/home', [StudentHomeController::class, 'index'])
            ->name('home');

        Route::get('/catalog', [CatalogController::class, 'index'])
            ->name('catalog');

        // My Requests
        Route::get('/requests', [RequestController::class, 'index'])
            ->name('requests.index');

        Route::get('/requests/total', [RequestController::class, 'index'])
            ->name('requests.total');

        Route::get('/requests/pending', [RequestController::class, 'pending'])
            ->name('requests.pending');

        Route::get('/requests/processing', [RequestController::class, 'processing'])
            ->name('requests.processing');

        Route::get('/requests/completed', [RequestController::class, 'completed'])
            ->name('requests.completed');

        // Create or Cancel Request
        Route::post('/requests', [RequestController::class, 'store'])
            ->name('requests.store');

        Route::post('/requests/{bookRequest}/cancel', [RequestController::class, 'cancel'])
            ->name('requests.cancel');

        // Profile
        Route::get('/profile', [ProfileController::class, 'edit'])
            ->name('profile');

        Route::post('/profile', [ProfileController::class, 'update'])
            ->name('profile.update');

        Route::post('/profile/password', [ProfileController::class, 'updatePassword'])
            ->name('profile.password');
    });

/*
|--------------------------------------------------------------------------
| Admin Routes (requires login + admin role)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        /*
        |--------------------------------------------------------------------------
        | Admin Request Management
        |--------------------------------------------------------------------------
        */

        // Main request table (Manage Requests)
        Route::get('/requests', [AdminBookRequestController::class, 'index'])
            ->name('requests.index');

        // Total Requests page
        Route::get('/requests/total', [AdminBookRequestController::class, 'total'])
            ->name('requests.total');

        // Status-specific pages
        Route::get('/requests/pending', [AdminBookRequestController::class, 'pending'])
            ->name('requests.pending');

        Route::get('/requests/processing', [AdminBookRequestController::class, 'processing'])
            ->name('requests.processing');

        Route::get('/requests/completed', [AdminBookRequestController::class, 'completed'])
            ->name('requests.completed');

        // View details of a single request
        Route::get('/requests/{bookRequest}', [AdminBookRequestController::class, 'show'])
            ->name('requests.show');

        // Update request status / upload file
        Route::post('/requests/{bookRequest}/update-status', [AdminBookRequestController::class, 'updateStatus'])
            ->name('requests.updateStatus');

        /*
        |--------------------------------------------------------------------------
        | Books & Categories
        |--------------------------------------------------------------------------
        */

        Route::resource('books', AdminBookController::class);
        Route::resource('categories', AdminCategoryController::class);

        /*
        |--------------------------------------------------------------------------
        | Users
        |--------------------------------------------------------------------------
        */

        // List users
        Route::get('/users', [AdminUserController::class, 'index'])
            ->name('users.index');

        // Edit form
        Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])
            ->name('users.edit');

        // Update user
        Route::put('/users/{user}', [AdminUserController::class, 'update'])
            ->name('users.update');

        // Optional: delete user
        Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])
            ->name('users.destroy');

        /*
        |--------------------------------------------------------------------------
        | Settings
        |--------------------------------------------------------------------------
        */

        // Single Settings page (admin/settings)
        Route::get('/settings', [AdminSettingsController::class, 'index'])
            ->name('settings.index');
            Route::post('/settings', [AdminSettingsController::class, 'update'])
    ->name('settings.update');

        Route::post('/account', [AdminAccountController::class, 'update'])
            ->name('account.update');

        Route::post('/account/password', [AdminAccountController::class, 'updatePassword'])
            ->name('account.password');
            Route::get('/student/profile', [\App\Http\Controllers\Student\ProfileController::class, 'edit'])
    ->name('student.profile.edit');

    });
