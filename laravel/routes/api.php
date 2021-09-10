<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1'], function () {
    Route::get('/books/search', 'App\Http\Controllers\BookController@search');
    Route::get('/books', 'App\Http\Controllers\BookController@all');
    Route::post('/book', 'App\Http\Controllers\BookController@create');
    Route::get('/book/{id}', 'App\Http\Controllers\BookController@get');
    Route::put('/book/{id}', 'App\Http\Controllers\BookController@update');
    Route::delete('/book/{id}', 'App\Http\Controllers\BookController@delete');
});
