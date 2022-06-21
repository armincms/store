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
			'carrier.*' => 'required|numeric',
			'address' => 'required|numeric',
		]);

		$order = \DB::transaction(function() use ($request) {
			return tap(new StoreOrder, function($order) use ($request) {
				$address = StoreAddress::with('city.state.country')->findOrFail(
					$request->address
				);

				$order->forceFill([
					'address' => $address->address,
					'currency_code' => config('nova.currency'),
				]);

				// $order->carrier()->associate($carrier);
				$order->user()->associate($request->user());

				$order->save();

				$order->forceFill([
					'finish_callback' => route('store.invoice', $order->token),
				])->asPending();

				$order->products()->sync([]);
				$carriers = StoreCarrier::with([
						'countries', 'states', 'cities', 'zones'
					])->find(request('carrier'))
					->keyBy->getKey()
					->map(function($carrier) use ($address) {
						$city = data_get($address, 'city.id');
						$state = data_get($address, 'city.state.id');
						$country = data_get($address, 'city.state.country.id');
						$cities = $carrier->cities->pluck('pivot.ranges', 'id')->map('json_decode');
						$states = $carrier->states->pluck('pivot.ranges', 'id')->map('json_decode');
						$countries = $carrier->countries->pluck('pivot.ranges', 'id')->map('json_decode');
						$data = [
							'name' => $carrier->name,
							'id' => $carrier->getKey(),
						];

						if (isset($cities[$city])) {
							$data['cost'] =  $cities[$city][0];
						}

						if (isset($states[$state])) {
							$data['cost'] =  $states[$state][0];
						}

						if (isset($countries[$country])) {
							$data['cost'] = $countries[$country][0];
						}

						return $data;
					});
				Cart::getContent()->each(function($item) use ($order, $carriers) {
					if ($product = StoreProduct::find($item->attributes->get('product'))) {
						$product->load('combinations.attributes.group');
						$group = md5(
							collect($product->getConfig('shipping.carriers'))
			        			->filter()
			        			->sort()
			        			->keys()
			        			->toJson()
		        		);
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
							'details' => [
								'image' => $product->featuredImage(),
								'carrier' => $carriers->get(request("carrier.{$group}")),
								'attributes' => $item->attributes->get('attributes'),
							],
						]);
					}
				});

				Cart::clear();
			});
		});

		return redirect()->route('store.checkout', $order->trackingCode());
	}
}