<?php 

Route::post('register-order', 'OrderController@store')->name('store.register-order');
Route::post('payment/{order}', 'PaymentController@handle')->name('store.payment');
Route::post('adress', 'AddressController@store')->name('store.address');
Route::delete('adress/{id}', 'AddressController@delete')->name('store.address.delete');

Route::resource('store/cart-item', 'StoreCartItemController', [
    'only' => ['store', 'destroy', 'update'],
    'names' => 'cart.item',
]); 