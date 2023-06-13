<?php

use App\Http\Controllers\ScriptController;
use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\WebsiteImportController;
use App\Http\Controllers\WebsiteCheckScriptController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/{scriptName}.js', [ScriptController::class, 'show']);

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // Websites
    Route::get('/websites/create', [WebsiteController::class, 'create'])
        ->name('websites.create');
    Route::post('/websites', [WebsiteController::class, 'store'])
        ->name('websites.store');
    Route::get('/websites/{website}', [WebsiteController::class, 'show'])
        ->name('websites.show');
    Route::get('/websites/{website}/edit', [WebsiteController::class, 'edit'])
        ->name('websites.edit');
    Route::put('/websites/{website}', [WebsiteController::class, 'update'])
        ->name('websites.update');

    // Checks
    Route::post('/websites/{website}/checks/script', WebsiteCheckScriptController::class)
        ->name('websites.checks.script');

    // Imports
    Route::get('/websites/{website}/imports', [WebsiteImportController::class, 'index'])
        ->name('websites.imports.index');
});
