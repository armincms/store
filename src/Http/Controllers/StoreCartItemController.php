<?php 

namespace Armincms\Store\Http\Controllers;

use App\Http\Controllers\Controller; 
use Armincms\Store\Http\Requests\StoreRequest;
use Cart;

class StoreCartItemController extends Controller
{     
	/**
	 * Update a cart item.
	 * 
	 * @param  StoreRequest $request
	 * @return \Illuminate\View\View              
	 */
	public function store(StoreRequest $request)
	{  
		$product = $request->findProductOrFail($request->product)->load('combinations.attributes.group');
		$combination = $this->getCombination($product, (array) $request->get('attributes')); 
		$item = $combination ?? $product;
		$attributes = !isset($combination) ? [] : $combination->attributes->map(function($attribute) {
			return [
				'color' => $attribute->color,  
				'value' => $attribute->value, 
				'name' 	=> $attribute->group->name,
				'type' 	=> $attribute->group->type,
				'texture' => $attribute->texture,
			];
		});

		Cart::add([
			'id' => $item->storageKey(),
			'name' => $product->name,
			'quantity' => $request->quantity ?: 1,
			'price' => $item->salePrice(),
			'attributes' => [
				'image' => $product->featuredImage(),
				'product' => $product->getKey(),
				'oldPrice' => $item->oldPrice(),
				'combination' => optional($combination)->getKey(), 
				'attributes' => $attributes,
			],
		]);    

		return redirect()->route('store.cart');
	}

	/**
	 * Get the combination for the given attributes.
	 * 
	 * @param  [type] $product    
	 * @param  array  $attributes 
	 * @return null|StoreCombination             
	 */
	public function getCombination($product, array $attributes = [])
	{
		if (! empty($attributes)) { 
			return $product->combinations->first(function($combination) use ($attributes) {
				return $combination->attributes->reject(function($attribute) use ($attributes) {
					return in_array($attribute->getKey(), $attributes);
				})->isEmpty();
			});			
		}

		return $product->combinations->first(function($combination) {
			return $combination->isDefault();
		});
	}

	/**
	 * Update a cart item.
	 * 
	 * @param  StoreRequest $request
	 * @return \Illuminate\View\View              
	 */
	public function update(StoreRequest $request)
	{  
		Cart::update($request->route('cart_item'), [
			'quantity' => $request->quantity,
		]); 

		return redirect()->route('store.cart');
	}

	/**
	 * Remove an item from the cart.
	 * 
	 * @param  StoreRequest $request
	 * @return \Illuminate\View\View              
	 */
	public function destroy(StoreRequest $request)
	{  
		Cart::remove($request->route('cart_item'));

		return redirect()->route('store.cart');
	}
}
