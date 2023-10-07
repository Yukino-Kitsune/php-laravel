<?php

use App\Http\Controllers\NotesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/v1/notebook', function (Request $request){
    return NotesController::index($request);
});
Route::post('v1/notebook', function (Request $request){
    return NotesController::store($request);
});
Route::get('/v1/notebook/{id}', function (int $id){
    return NotesController::getNote($id);
});
Route::post('/v1/notebook/{id}', function (Request $request, int $id){
    return NotesController::update($request, $id);
});
Route::delete('/v1/notebook/{id}', function (int $id){
    return NotesController::delete($id);
});
