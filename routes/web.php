<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\HomeworkController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/hello', function () {
    return view('hello');
});

Route::get('/employees', [EmployeeController::class, 'index']);

Route::get('/posts', function () {
    $posts = DB::table('posts')->get();
    return view('posts.index', compact('posts'));
});

Route::post('/select-role', [HomeworkController::class, 'selectRole']);
Route::get('/homeworks', [HomeworkController::class, 'index']);
Route::get('/homeworks/create', [HomeworkController::class, 'create']);
Route::post('/homeworks', [HomeworkController::class, 'store']);
Route::delete('/homeworks/{id}', [HomeworkController::class, 'destroy']);
Route::get('/homeworks-json', function () {
    return \App\Models\Homework::all();
});