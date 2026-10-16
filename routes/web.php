<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PoolController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::view('/maintenance', 'maintenance')->name('maintenance');

Route::middleware('admin')->group(function () {
  Route::get('/', [PoolController::class, 'dashboard'])->name('dashboard');
  Route::get('/members', [PoolController::class, 'allMembersPage'])->name('members.all');
  Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
  Route::post('/password', [AuthController::class, 'changePassword'])->name('password.change');
  Route::post('/admins', [AuthController::class, 'createAdmin'])->name('admins.create');
  Route::post('/api/members', [PoolController::class, 'storeMember']);
  Route::put('/api/members/{member}', [PoolController::class, 'updateMember']);
  Route::post('/api/members/{member}/extend', [PoolController::class, 'extendMember']);
  Route::delete('/api/members/{member}', [PoolController::class, 'destroyMember']);
  Route::post('/api/scan', [PoolController::class, 'scan']);
  Route::get('/api/members', [PoolController::class, 'members']);
  Route::get('/api/members/all', [PoolController::class, 'activeMembers']);
  Route::get('/api/members/all-records', [PoolController::class, 'allMembers']);
  Route::get('/api/logs', [PoolController::class, 'logs']);
  Route::get('/api/logs/export', [PoolController::class, 'exportLogs']);
  Route::delete('/api/logs', [PoolController::class, 'destroyLogs']);
});
