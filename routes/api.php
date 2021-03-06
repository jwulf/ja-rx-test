<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('test', 'TestController@index');

Route::resource('actors', 'ActorsController', ['only' => ['index', 'store', 'show', 'update', 'destroy']]);

Route::resource('genres', 'GenresController', ['only' => ['index', 'store', 'show', 'update', 'destroy']]);

Route::resource('movies', 'MoviesController', ['only' => ['index', 'store', 'show', 'update', 'destroy']]);
Route::resource('movies.actors', 'MovieActorsController', ['only' => ['index', 'store', 'destroy']]);
