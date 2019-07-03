<?php
/**
 * Created by PhpStorm.
 * User: ugo
 * Date: 16/11/18
 * Time: 09:19
 */

Route::group(['middleware' => ['web']], function () {
    Route::get('/widget/SSTemp/{id}', 'Tchoblond59\SSTemp\Controllers\SSTempController@index');
    Route::post('/update/sstemp/{id}', 'Tchoblond59\SSTemp\Controllers\SSTempController@update');
    Route::post('/delete/sstemp/mail/{id}', 'Tchoblond59\SSTemp\Controllers\SSTempController@delete');
});

