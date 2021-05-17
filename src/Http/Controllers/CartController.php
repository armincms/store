<?php 

namespace Armincms\Store\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Armincms\Store\Models\StoreProduct; 

/**
 * 
 */
class CartController extends Controller
{
	public function push(Request $request)
	{
	 	$request->validate([
	 		'quantity' => 'numeric',
	 		'product' => ['required', function($attribute, $value, $fail) {
	 			return StoreProduct::active()->whereKey($value)->firstOr(function() use ($fail) {
	 				return $fail(__('Requested Product Not Found.'));
	 			});
	 		}],
	 	]); 

	 	app('store.cart')->add($request->get('product'), $request->get('quantity'));  

	 	return $this->response($request);
	}

	public function remove(Request $request)
	{
	 	$request->validate([
	 		'product' => ['required', function($attribute, $value, $fail) {
	 			return StoreProduct::active()->whereKey($value)->firstOr(function() use ($fail) {
	 				return $fail(__('Requested Product Not Found.'));
	 			});
	 		}],
	 	]); 

	 	$quantity = intval($request->get('quantity')) ?: \ShoppingCart::count($request->get('product'));

	 	app('store.cart')->decrement($request->get('product'), $quantity); 

	 	return $this->response($request); 
	}

	public function update(Request $request)
	{
	 	$request->validate([
	 		'quantity' => 'required|min:1',
	 		'product' => ['required', function($attribute, $value, $fail) {
	 			return StoreProduct::active()->whereKey($value)->firstOr(function() use ($fail) {
	 				return $fail(__('Requested Product Not Found.'));
	 			});
	 		}],
	 	]); 

	 	app('store.cart')->update($request->get('product'), $request->get('quantity')); 

	 	return $this->response($request);
	}

	public function response(Request $request)
	{
		return response()->json([ 
			'items' => app('store.cart')->all() 
		]); 
	}
}