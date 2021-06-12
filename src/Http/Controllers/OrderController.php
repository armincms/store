<?php 

namespace Armincms\Store\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
use Armincms\Store\Models\{StoreProduct, StoreAddress, StoreCarrier, StoreOrder}; 
use ShoppingCart;
 
class OrderController extends Controller
{
	public function store(Request $request)
	{
		$request->validate([ 
			'carrier' => 'required|numeric',
			'address' => 'required|numeric',
		]);

		$order = \DB::transaction(function() use ($request) { 
			return tap(new StoreOrder, function($order) use ($request) {
				$address = StoreAddress::findOrFail($request->address);
				$carrier = StoreCarrier::findOrFail($request->carrier);

				$order->forceFill([
					'address' => $address->address,
					'currency_code' => config('nova.currency'),
				]);

				$order->carrier()->associate($carrier);
				$order->user()->associate($request->user());

				$order->save();  

				$order->forceFill([ 
					'finish_callback' => route('store.invoice', $order->token),
				])->asPending();

				$products = StoreProduct::find(array_keys(ShoppingCart::all()));

				$order->products()->sync($products->keyBy->getKey()->map(function($product) {
					return [
						'name' 	=> $product->name,
						'count' => ShoppingCart::count($product->getKey()),
						'old_price'		=> $product->oldPrice(),
						'sale_price'	=> $product->salePrice(),
						'product_id' 	=> $product->getKey(),
						'description' 	=> $product->summary,
					];
				})->all());

				ShoppingCart::delete();
			}); 
		});

		return redirect()->route('store.checkout', $order->trackingCode());
	} 
}