<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

// ログイン必須ルート
Route::middleware('auth')->group(function () {
    // プロジェクト一覧画面
    Route::get('projects', [ProjectController::class, 'index'])->name('projects.index');

    // プロジェクト作成画面
    Route::get('projects/create', [ProjectController::class, 'create'])->name('projects.create'); // ここを追加

    // プロジェクト作成処理
    Route::post('project/store', [ProjectController::class, 'store'])->name('projects.store'); // ここを追加

    // タスク一覧画面
    Route::get('projects/{id}/tasks', [TaskController::class, 'index'])->name('tasks.index');
});

require __DIR__.'/auth.php';