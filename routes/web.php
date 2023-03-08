<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeAjaxController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/index', function () {
    return view('employee.index');
});
Route::get('/employees', [EmployeeAjaxController::class, 'index']);
Route::post('/employee', [EmployeeAjaxController::class, 'store']);
Route::get('/employee/{id}', [EmployeeAjaxController::class, 'edit']);
Route::put('/employee/{id}/edit', [EmployeeAjaxController::class, 'update']);
Route::delete('/employee/{id}', [EmployeeAjaxController::class, 'destroy']);
