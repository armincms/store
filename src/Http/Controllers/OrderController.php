<?php 

namespace Armincms\Store\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
use Armincms\Store\Models\{StoreProduct, StoreAddress, StoreCarrier, StoreOrder}; 
use Cart;
 
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

				$order->products()->sync([]);

				Cart::getContent()->each(function($item) use ($order) {
					if ($product = StoreProduct::find($item->attributes->get('product'))) {
						$product->load('combinations.attributes.group');
						$combination = $product->combinations->find($item->attributes->get(
							'combination', $item->id
						));
	  
						$order->products()->attach($product, [ 
							'count' => $item->quantity,
							'old_price'		=> $product->oldPrice(),
							'sale_price'	=> $product->salePrice(),
							'product_id' 	=> $product->getKey(),
							'description' 	=> $product->summary,
							'name' 	=> optional($combination)->fullname() ?: $product->name,
						]); 
					}
				}); 

				Cart::clear();
			}); 
		});

		return redirect()->route('store.checkout', $order->trackingCode());
	} 
}