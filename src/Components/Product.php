<?php 
namespace Armincms\Store\Components;
 
use Illuminate\Http\Request; 
use Core\HttpSite\Component;
use Core\HttpSite\Contracts\Resourceable;
use Core\HttpSite\Concerns\IntractsWithResource;
use Core\HttpSite\Concerns\IntractsWithLayout;
use Core\Document\Document;
use Armincms\Store\Models\StoreProduct;

class Product extends Component implements Resourceable
{       
	use IntractsWithResource, IntractsWithLayout;

	/**
	 * Route Conditions of section
	 * 
	 * @var null
	 */
	protected $wheres = [ 
		'id'	=> '[0-9]+'
	];  

	private $type = null;

	public function toHtml(Request $request, Document $docuemnt) : string
	{       
		$product = StoreProduct::active()->whereHas('translations', function($query) use ($request) {
			$query->whereUrl($request->relativeUrl());
		})->firstOrFail(); 
		
		$this->resource($product);   
		$docuemnt->title($product->metaTitle()?: $product->title); 
		
		$docuemnt->description($product->metaDescription()?: $product->description);   
		$docuemnt->keywords($product->getMeta('keywords')?: ''/*$product->tags->map->tag->implode(',')*/);  
		
		return (string) $this->firstLayout($docuemnt, $this->config('layout'), 'clean-product')
					->display($product->toArray(), $docuemnt->component->config('layout', [])); 
	}    

	public function categories()
	{
		return $this->resource->categories;
	}

	public function author()
	{
		return $this->resource->owner;
	}

	public function featuredImage($schema = 'main')
	{
		return $this->resource->featuredImage($schema);
	}
}
