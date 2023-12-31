<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\PhotoController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\ThumbnailController;
use App\Http\Controllers\Api\IndicatorController;
use App\Http\Controllers\Api\UserPhotosController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\UserProjectsController;
use App\Http\Controllers\Api\ProjectIndicatorsController;
use App\Http\Controllers\Api\ProjectThumbnailsController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

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

Route::post('/login', [AuthController::class, 'login'])->name('api.login');
Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');

Route::middleware('auth:sanctum')
    ->get('/user', function (Request $request) {
        return $request->user();
    })
    ->name('api.user');

Route::name('api.')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::apiResource('roles', RoleController::class);
        Route::apiResource('permissions', PermissionController::class);

        Route::apiResource('projects', ProjectController::class);

        // Project Indicators
        Route::get('/projects/{project}/indicators', [
            ProjectIndicatorsController::class,
            'index',
        ])->name('projects.indicators.index');
        Route::post('/projects/{project}/indicators', [
            ProjectIndicatorsController::class,
            'store',
        ])->name('projects.indicators.store');

        // Project Thumbnails
        Route::get('/projects/{project}/thumbnails', [
            ProjectThumbnailsController::class,
            'index',
        ])->name('projects.thumbnails.index');
        Route::post('/projects/{project}/thumbnails', [
            ProjectThumbnailsController::class,
            'store',
        ])->name('projects.thumbnails.store');

        Route::apiResource('users', UserController::class);

        // User Photos
        Route::get('/users/{user}/photos', [
            UserPhotosController::class,
            'index',
        ])->name('users.photos.index');
        Route::post('/users/{user}/photos', [
            UserPhotosController::class,
            'store',
        ])->name('users.photos.store');

        // User Projects
        Route::get('/users/{user}/projects', [
            UserProjectsController::class,
            'index',
        ])->name('users.projects.index');
        Route::post('/users/{user}/projects', [
            UserProjectsController::class,
            'store',
        ])->name('users.projects.store');
    });
