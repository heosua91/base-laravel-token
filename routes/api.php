<?php

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

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

require __DIR__.'/auth.php';

Route::middleware(['auth:sanctum', 'role'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    })->name('user');

    Route::post('/user', function (Request $request) {
        return parse_json(['message' => 'Success'], Response::HTTP_OK);
    });
});