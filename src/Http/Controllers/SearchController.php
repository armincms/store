<?php

namespace Armincms\Store\Http\Controllers;

use App\Http\Controllers\Controller;
use Armincms\Store\Http\Requests\StoreRequest;

class SearchController extends Controller
{
	/**
	 * Update a cart item.
	 * 
	 * @param  StoreRequest $request
	 * @return \Illuminate\View\View              
	 */
	public function handle(StoreRequest $request)
	{
		return $request->newProduct()->whereHas('translations', function ($query) {
			$searchString = '%' . request('search') . '%';
			$query->where('name', 'like', $searchString);
		})->latest()->limit(15)->map(function ($product) {
			return [
				'name' 	=> $product->name,
				'url'	=> $product->url(),
			];
		});
	}
}
