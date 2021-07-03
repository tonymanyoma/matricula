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

Route::prefix('auth')->group(function () {
    // Below mention routes are public, user can access those without any restriction.
    // Create New User
    Route::post('register', 'AuthController@register');
    // Login User
    Route::post('login', 'AuthController@login');

    // Refresh the JWT Token
    Route::get('refresh', 'AuthController@refresh');

    // Below mention routes are available only for the authenticated users.
    Route::middleware('auth:api')->group(function () {
        // Get user info
        Route::get('user', 'AuthController@user');
        // Logout user from application
        Route::post('logout', 'AuthController@logout');

       


        //Usuarios
        Route::resource('usuarios', 'UsuarioController');

   
         //wispro
         Route::post('sinc_users_wispro', 'UsuarioController@sinc_users_wispro');
         Route::post('sinc_contratos_wispro', 'ContratoController@sinc_contratos_wispro');

        
        
        //Tipo documento
        Route::resource('documentos', 'TipoDocumentoController');

        //Roles
        Route::resource('roles', 'RoleController');

      
        
    });
});

//Tipo documento
Route::resource('documentos', 'TipoDocumentoController');

//Ciudad
Route::resource('ciudads', 'CiudadController');

Route::get('prueba', 'ReporteController@prueba');


Route::get('/{vue?}', function () {
    return view('index');
})->where('vue', '[\/\w\.-]*');
