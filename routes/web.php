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

// Route par défaut qui ouvre la page de connexion à l'application, le fichier de login se trouve dans le fichier welcome.
// 

Route::get('/', function () {
    return view('welcome');
});

// On a utilisé le module d'authentification par défaut de laravel l'instruction Auth::routes() permet de charger les routes d'autentification de Laravel
Auth::routes();

// 
Route::group(['middleware' => ['auth']], function() {

    // Ressource routes
    Route::resource('roles','RoleController');
    Route::resource('users','UserController');

    // Get Route
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/profile', 'ProfileController@index')->name('profile.index');
    Route::get('/crypto', 'CryptoController@index')->name('crypto.index');
    Route::get('/crypto/show/{id}', 'CryptoController@detailCrypto')->name('crypto.show');
    Route::get('/portefeuille', 'PaymentController@index')->name('portefeuille.show');
    Route::get('/portefeuille', 'PaymentController@index')->name('portefeuille.show');
    Route::get('/payment/detail/{id}', 'PaymentController@showDetail')->name('payment.detail');
    Route::get('/sale_payment/{id}', 'PaymentController@salePayment')->name('payment.sale');

    // POST route
    Route::post('/profile', 'ProfileController@update')->name('profile.update');
    Route::post('/balance', 'ProfileController@updateBalance')->name('profile.balance');
    Route::post('/save_payment', 'PaymentController@savePayment')->name('payment.save');

});

