<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['register' => false]);
Route::group(['middleware' => ['auth','block-user','log-activity']], function() {
    Route::get('/home', 'HomeController@index')->name('home');
    // user
	Route::group(['prefix' => 'user', 'as' => 'user.'], function() {
        Route::get('/profile', ['as' => 'profile', 'uses' => 'UserController@profile']);
 		Route::post('/updateProfile', ['as' => 'updateProfile', 'uses' => 'UserController@updateProfile']);
        Route::get('/create', ['as' => 'index', 'uses' => 'UserController@index'])->middleware(['permission:administrator']);
        Route::post('/create', ['as' => 'create', 'uses' => 'UserController@create'])->middleware(['permission:administrator']);
        Route::get('/list/{role}', ['as' => 'list', 'uses' => 'UserController@list'])->middleware(['permission:administrator']);
        Route::get('/edit/{id}', ['as' => 'edit', 'uses' => 'UserController@edit'])->middleware(['permission:administrator']);
        Route::post('/updateData/{id}', ['as' => 'updateData', 'uses' => 'UserController@updateData'])->middleware(['permission:administrator']);
        Route::get('/get_user', ['as' => 'get_user', 'uses' => 'UserController@getUsername']);
        Route::get('/searchUser', ['as' => 'searchUser', 'uses' => 'UserController@searchUser']);
        Route::get('/block_unclock/{id}', ['as' => 'block_unclock', 'uses' => 'UserController@block_unclock'])->middleware(['permission:administrator']);
        Route::get('/priority/{id}', ['as' => 'priority', 'uses' => 'UserController@priority'])->middleware(['permission:administrator']);
        Route::get('/team', ['as' => 'list_sponsor', 'uses' => 'UserController@list_sponsor'])->middleware(['permission:administrator']);
        Route::get('/list/donwline', ['as' => 'list_donwline', 'uses' => 'UserController@list_donwline']);
        Route::get('/donwline/{id}', ['as' => 'list_donwline_user', 'uses' => 'UserController@list_donwline_user']);
        Route::get('/bank', ['as' => 'bank', 'uses' => 'UserController@viewBank']);
        Route::post('/bank/store', ['as' => 'bank.save', 'uses' => 'UserController@saveBank']);
        Route::post('/upload_foto', ['as' => 'upload_foto', 'uses' => 'UserController@upload_foto']);
        Route::post('/updateLevel', ['as' => 'updateLevel', 'uses' => 'UserController@updateLevel']);
    });

    // balance
    Route::group(['prefix' => 'balance', 'as' => 'balance.'], function() {
        Route::get('/', ['as' => 'index', 'uses' => 'BalanceController@index'])->middleware(['permission:administrator']);
        Route::get('/{wallet}', ['as' => 'wallet', 'uses' => 'BalanceController@wallet']);
        Route::get('/{wallet}/{id}', ['as' => 'wallet_member', 'uses' => 'BalanceController@wallet_member'])->middleware(['permission:administrator']);
        Route::post('/change', ['as' => 'change', 'uses' => 'BalanceController@change_balance'])->middleware(['permission:administrator']);
    });

    // Koin
    Route::group(['prefix' => 'koin', 'as' => 'koin.'], function() {
        Route::get('/', ['as' => 'index', 'uses' => 'KoinController@index']);
        Route::post('/beli', ['as' => 'buy', 'uses' => 'KoinController@buy']);
        Route::get('history/beli', ['as' => 'history.buy', 'uses' => 'KoinController@history_buy']);
        Route::get('list/beli', ['as' => 'list.buy', 'uses' => 'KoinController@list_buy'])->middleware(['permission:administrator']);
        Route::get('accept/{type}/{id}', ['as' => 'confirm', 'uses' => 'KoinController@confirm'])->middleware(['permission:administrator']);
        Route::post('/jual', ['as' => 'sell', 'uses' => 'KoinController@sell']);
        Route::get('history/jual', ['as' => 'history.sell', 'uses' => 'KoinController@history_sell']);
        Route::get('list/jual_beli/member', ['as' => 'list.buy_sell', 'uses' => 'KoinController@list_buy_sell'])->middleware(['permission:administrator']);
    });

    // Transaksi
    Route::group(['prefix' => 'transaksi', 'as' => 'transaksi.'], function() {
        Route::get('/', ['as' => 'index', 'uses' => 'TransaksiController@index']);
        Route::get('/donasi', ['as' => 'donasi', 'uses' => 'TransaksiController@donasi']);
        Route::post('/donasi/{id}', ['as' => 'sendDonation', 'uses' => 'TransaksiController@sendDonation']);
        Route::get('/donasi/confirm', ['as' => 'confirm', 'uses' => 'TransaksiController@confirm']);
        Route::get('/donasi/history', ['as' => 'history', 'uses' => 'TransaksiController@history']);
        Route::get('/donasi/list', ['as' => 'donasi.list', 'uses' => 'TransaksiController@list'])->middleware(['permission:administrator']);
        Route::get('accept/{type}/{id}', ['as' => 'confirm_donasi', 'uses' => 'TransaksiController@confirm_donasi']);
    });

    // Notifikasi
    Route::group(['prefix' => 'notifikasi', 'as' => 'notifikasi.'], function() {
        Route::get('/', ['as' => 'index', 'uses' => 'NotificationController@index']);
    });

    // Team
    Route::group(['prefix' => 'team', 'as' => 'team.'], function() {
        Route::get('/members', ['as' => 'index', 'uses' => 'TeamController@index']);
        Route::get('/add_member', ['as' => 'add_member', 'uses' => 'TeamController@add_member']);
        Route::post('/save_member', ['as' => 'save_member', 'uses' => 'TeamController@save_member']);
        Route::get('/term_of_condition', ['as' => 'term_of_condition', 'uses' => 'TeamController@term_of_condition']);
    });

    // setting
    Route::group(['prefix' => 'setting', 'as' => 'setting.'], function() {
        Route::get('/', ['as' => 'index', 'uses' => 'SettingController@index'])->middleware(['permission:administrator']);
        Route::post('/update', ['as' => 'update', 'uses' => 'SettingController@update'])->middleware(['permission:administrator']);
        Route::get('/contact', ['as' => 'contact', 'uses' => 'SettingController@contact'])->middleware(['permission:administrator']);
        Route::post('/update/contact', ['as' => 'update.contact', 'uses' => 'SettingController@updateContact'])->middleware(['permission:administrator']);
        Route::get('/level', ['as' => 'level', 'uses' => 'SettingController@level'])->middleware(['permission:administrator']);
        Route::post('/update/level', ['as' => 'update.level', 'uses' => 'SettingController@updateLevel'])->middleware(['permission:administrator']);
        Route::post('/update/level/term', ['as' => 'update.level.term', 'uses' => 'SettingController@updateLevelTerm'])->middleware(['permission:administrator']);
        Route::get('/account', ['as' => 'account', 'uses' => 'SettingController@paymentAccount'])->middleware(['permission:administrator']);
        Route::post('/update/account', ['as' => 'update.account', 'uses' => 'SettingController@updatePaymentAccount'])->middleware(['permission:administrator']);
        Route::get('/method', ['as' => 'method', 'uses' => 'SettingController@paymentMethod'])->middleware(['permission:administrator']);
        Route::get('/method/delete/{id}', ['as' => 'method.delete', 'uses' => 'SettingController@deletePaymentMethod'])->middleware(['permission:administrator']);
        Route::post('/update/method', ['as' => 'update.method', 'uses' => 'SettingController@updatePaymentMethod'])->middleware(['permission:administrator']);
        Route::get('/term', ['as' => 'term', 'uses' => 'SettingController@termOfCondition'])->middleware(['permission:administrator']);
        Route::post('/term/update', ['as' => 'update.term', 'uses' => 'SettingController@updateTermOfCondition'])->middleware(['permission:administrator']);
    });
});
