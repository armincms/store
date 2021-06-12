<?php 

namespace Armincms\Store\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Armincms\Store\Models\StoreProduct; 
use Cart;

/**
 * 
 */
class CartController extends Controller
{
	public function push(Request $request)
	{
	 	$request->validate([
	 		'quantity' => 'numeric', 
	 	]); 

	 	$product = StoreProduct::active()->findOrFail($request->get('product'));

	 	Cart::add([
		    'id' => $product->id, // inique row ID
		    'name' => $product->name, // inique row ID
		    'price' => $product->salePrice(), // inique row ID 
		    'quantity' => $request->get('quantity'), 
		    'attributes' => [
		    	'oldPrice' 	=> $product->oldPrice(),
		    	'image' 	=> $product->featuredImage(),
		    	'url' 		=> $product->url(),
		    ],
		    'associatedModel' => StoreProduct::class,
		]);

	 	return $this->response($request);
	}

	public function remove(Request $request)
	{
	 	$request->validate([
	 		'product' => ['required', function($attribute, $value, $fail) {
	 			Cart::has($value) || $fail(__('Requested Product Not Found.'));
	 		}],
	 	]); 

	 	$item = Cart::get($request->get('product'));
	 	$quantity = intval($request->get('quantity')) ?: $item->quantity;

	 	if ($item->quantity > $quantity) {
	 		Cart::update($request->get('product'), [
	 			$item->quantity - $request->get('quantity')
	 		]);
	 	} else {
	 		Cart::remove($request->get('product'));
	 	} 

	 	return $this->response($request); 
	}

	public function update(Request $request)
	{
	 	$request->validate([
	 		'quantity' => 'required|min:1',
	 		'product' => ['required', function($attribute, $value, $fail) {
	 			Cart::has($value) || $fail(__('Requested Product Not Found.'));
	 		}],
	 	]); 

	 	Cart::update($request->get('product'), [
	 		'quantity' => $request->get('quantity'),
	 	]); 

	 	return $this->response($request);
	}

	public function response(Request $request)
	{
		return response()->json([ 
			'items' => Cart::getContent() 
		]); 
	}
}