<?php 

Route::prefix('api/cart')->name('cart.api')->group(function() {
	Route::post('/', 'CartController@push')->name('push');
	Route::put('/', 'CartController@update')->name('update');
	Route::delete('/', 'CartController@remove')->name('remove');
});

Route::post('register-order', 'OrderController@store')->name('store.register-order');
Route::post('payment/{order}', 'PaymentController@handle')->name('store.payment');
Route::post('adress', 'AddressController@store')->name('store.address');
Route::delete('adress/{id}', 'AddressController@delete')->name('store.address.delete');