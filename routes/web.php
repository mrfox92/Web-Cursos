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

//  definimos un grupo de rutas con prefijo para courses
Route::group(['prefix' => 'courses'], function() {

    Route::group( ['middleware' => ['auth'] ], function() {
        Route::get('/subscribed', 'CourseController@subscribed')->name('courses.subscribed');
        Route::get('/{course}/inscribe', 'CourseController@inscribe')
            ->name('courses.inscribe');
        Route::post('/add_review', 'CourseController@addReview')->name('courses.add_review');

        //  rutas con middleware para comprobar si el usuario que intenta crear o almacenar tiene el role teacher
        Route::group( ['middleware' => [ sprintf('role:%s', \App\Role::TEACHER) ] ], function() {

            // Route::resource('courses', 'CourseController'); //  refactorizamos nuestras rutas

            Route::get('/create', 'CourseController@create')->name('courses.create');
            Route::post('/store', 'CourseController@store')->name('courses.store');
            Route::put('/{course}/update', 'CourseController@update')->name('courses.update');
            Route::get('/{slug}/edit', 'CourseController@edit')->name('courses.edit');
            Route::delete('/{course}/destroy', 'CourseController@destroy')->name('courses.destroy');

        });
    });
    
    Route::get('/{course}', 'CourseController@show')->name('courses.detail');
});

//  utilizamos el middleware auth para nuestras rutas
Route::group( ['middleware' =>  ['auth'] ], function () {

    //  definomos un grupo de rutas con prefijo para subscriptions
    Route::group(['prefix' => 'subscriptions'], function() {
        Route::get('/plans', 'SubscriptionController@plans')->name('subscriptions.plans');
        Route::get('/admin', 'SubscriptionController@admin')->name('subscriptions.admin');
        Route::post('/process_subscription', 'SubscriptionController@processSubscription')->name('subscriptions.process_subscription');
        Route::post('/resume', 'SubscriptionController@resume')->name('subscriptions.resume');
        Route::post('/cancel', 'SubscriptionController@cancel')->name('subscriptions.cancel');
    });

    //  generamos las rutas para nuestras facturas
    Route::group(['prefix' => "invoices"], function() {
        Route::get('/admin', 'InvoiceController@admin')->name('invoices.admin');
        Route::get('/{id}/download', 'InvoiceController@download')->name('invoices.download');
    });
});

//  rutas perfil usuario
Route::group(['prefix' => 'profile', 'middleware' => ['auth'] ], function() {
    Route::get('/', 'ProfileController@index')->name('profile.index');
    Route::put('/', 'ProfileController@update')->name('profile.update');
});

//  rutas solicitudes

Route::group(['prefix' => 'solicitude'], function () {
    Route::post('/teacher', 'SolicitudeController@teacher')->name('solicitude.teacher');
});

//  rutas profesor

Route::group(['prefix' => 'teacher', 'middleware' => ['auth'] ], function () {

    Route::get('/courses', 'TeacherController@courses')->name('teacher.courses');
    Route::get('/students', 'TeacherController@students')->name('teacher.students');
    Route::post('/send_message_to_student', 'TeacherController@sendMessageToStudent')->name('teacher.send_message_to_student');
});


//  construimos la ruta para acceder a las imagenes utilizando Intervention
Route::get('/images/{path}/{attachment}', function ($path, $attachment) {

    $file = sprintf('storage/%s/%s', $path, $attachment);
    //  comprobamos si la imagen existe
    if ( File::exists( $file ) ) {
        return Image::make($file)->response();
    }
});
