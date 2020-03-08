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

//  ruta de idioma
Route::get('/set_language/{lang}', 'Controller@setLanguage')->name('set_language');
Route::get('login/{driver}', 'Auth\LoginController@redirectToProvider')->name('social_auth');
Route::get('login/{driver}/callback', 'Auth\LoginController@handleProviderCallback');

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

//  definimos un grupo de rutas para courses
Route::group(['prefix' => 'courses'], function() {
    Route::get('/{course}', 'CourseController@show')->name('courses.detail');
});



//  construimos la ruta para acceder a las imagenes utilizando Intervention
Route::get('/images/{path}/{attachment}', function ($path, $attachment) {

    $file = sprintf('storage/%s/%s', $path, $attachment);
    //  comprobamos si la imagen existe
    if ( File::exists( $file ) ) {
        return Image::make($file)->response();
    }
});
