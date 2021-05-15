<?php 

Route::post('/', 'CartController@push')->name('push');
Route::put('/', 'CartController@update')->name('update');
Route::delete('/', 'CartController@remove')->name('remove');