<?php 


Route::post('store/login', [
    'uses'  => Auth\LoginController::class.'@login',
    'as'    => 'store.attempt',
]);

Route::post('store/logout', [
    'uses'  => Auth\LoginController::class.'@logout',
    'as'    => 'store.logout',
]);

Route::post('register-order', 'OrderController@store')->name('store.register-order');
Route::post('payment/{order}', 'PaymentController@handle')->name('store.payment');
Route::post('adress', 'AddressController@store')->name('store.address');
Route::delete('adress/{id}', 'AddressController@delete')->name('store.address.delete');

Route::resource('store/cart-item', 'StoreCartItemController', [
    'only' => ['store', 'destroy', 'update'],
    'names' => 'cart.item',
]); 