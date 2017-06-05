<?php

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

// Route::get('/', function () {
//     return view('app');
// });

Route::get('/{vue_capture?}', function () {
  return view('app');
})->where('vue_capture', '[\/\w\.-]*');

// Server response to url image files
Route::get('/img/{path}', function (Illuminate\Http\Request $request, $path) {
    return app('glide')->getImageResponse($path, $request->all());
})->where('path', '.*');

Auth::routes();

Route::group(['namespace' => 'Auth', 'prefix' => '/auth', 'middleware' => 'guest'], function () {
    Route::get('/login', 'LoginController@getLogin');

    Route::post('/register', 'RegisterController@register');
});

Route::group(['namespace' => 'Frontend'], function () {

    Route::get('/active/{token}', 'UserController@active');

    Route::get('/user', 'UserController@index');
});
