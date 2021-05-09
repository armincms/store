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
	 		'quantity' => 'required|min:1',
	 		'product' => ['required', function($attribute, $value, $fail) {
	 			return StoreProduct::active()->whereKey($value)->firstOr(function() use ($fail) {
	 				return $fail(__('Requested Product Not Found.'));
	 			});
	 		}],
	 	]); 

	 	app('store.cart')->add($request->get('product'), $request->get('quantity')); 

	 	return redirect()->route('store.cart');
	}

	public function remove(Request $request)
	{
	 	$request->validate([
	 		'quantity' => 'required|min:1',
	 		'product' => ['required', function($attribute, $value, $fail) {
	 			return StoreProduct::active()->whereKey($value)->firstOr(function() use ($fail) {
	 				return $fail(__('Requested Product Not Found.'));
	 			});
	 		}],
	 	]); 

	 	app('store.cart')->decrement($request->get('product'), $request->get('quantity')); 

	 	return redirect()->route('store.cart');
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

	 	return redirect()->route('store.cart');
	}
}