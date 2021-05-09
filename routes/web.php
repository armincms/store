<?php 

Route::post('/', 'CartController@push')->name('push');
Route::post('/update', 'CartController@update')->name('update');
Route::post('/remove', 'CartController@remove')->name('remove');