<?php

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

Route::redirect('/', '/chat');

Route::middleware(['auth:sanctum', 'verified'])->get('/chat', [App\Http\Controllers\ChatController::class, 'index'])->name('chat');
Route::middleware(['auth:sanctum', 'verified'])->post('/chat', [App\Http\Controllers\ChatController::class, 'createMessage']);
Route::middleware(['auth:sanctum', 'verified'])->patch('/user/updateStatus', [App\Http\Controllers\ChatController::class, 'updateUserStatus']);
Route::middleware(['auth:sanctum', 'verified'])->get('/chat/users/list', [App\Http\Controllers\ChatController::class, 'getUserChatList']);
Route::middleware(['auth:sanctum', 'verified'])->post('/chat/privateMessages', [App\Http\Controllers\ChatController::class, 'postPrivateMessages']);


