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

Route::group(['prefix' => 'v1'], function () {
    Route::group(['prefix' => 'book'], function () {
        Route::post('/', 'App\Http\Controllers\BookController@create');
        Route::get('/{id}', 'App\Http\Controllers\BookController@get');
        Route::put('/{id}', 'App\Http\Controllers\BookController@update');
        Route::delete('/{id}', 'App\Http\Controllers\BookController@delete');
    });

    Route::group(['prefix' => 'books'], function () {
        Route::get('/search', 'App\Http\Controllers\BookController@search');
        Route::get('/', 'App\Http\Controllers\BookController@all');
    });

    Route::group(['prefix' => 'export'], function () {
        Route::get('/{id}', 'App\Http\Controllers\ExportController@retrieveExport');
        Route::post('/', 'App\Http\Controllers\ExportController@initializeExport');
    });
});
