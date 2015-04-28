<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'WelcomeController@index');

Route::get('home', 'HomeController@index');

// Just a simple way of logging in without requiring a form.
Auth::loginUsingId(1);

// Apply the middleware to a group of routes.
Route::group(['middleware' => 'permissions.required'], function() {

    Route::get('/test',[
        'permissons' => ['access'],
        'permissions_require_all' => true,
        function() {
            return 'Displaying page';
        }
    ]);

});

// Apply the middleware to a single route.
Route::get('/test-number2',[
    'middleware' => 'permissions.required',
    'permissons' => ['access'],
    'permissions_require_all' => true,
    function() {
        return 'Displaying page';
    }
]);
