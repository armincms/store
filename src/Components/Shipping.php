<?php 
namespace Armincms\Store\Components;
 
use Illuminate\Http\Request; 
use Illuminate\Auth\AuthenticationException;
use Core\Document\Document;
use Core\HttpSite\Component;
use Core\HttpSite\Concerns\IntractsWithLayout;
use Armincms\Store\Models\{StoreProduct, StoreCarrier, StoreAddress};

class Shipping extends Dashboard\Dashboard 
{       
	use IntractsWithLayout;

	/**
	 * Route of Component.
	 * 
	 * @var null
	 */
	protected $route = 'shipping'; 

	public function toHtml(Request $request, Document $docuemnt) : string
	{    
		$this->checkAuthentication(); 

		$layout = $this->firstLayout($docuemnt, $this->config('layout', 'clean-shipping'));

		return strval($layout->display());		
	} 

	public function addresses()
	{  
		return StoreAddress::authenticate()->get();
	}

	public function carriers()
	{  
		return StoreCarrier::with('countries', 'cities', 'states', 'zones')->get();
	}

	public function groupedProducts()
	{ 
		return $this->products()->groupBy(function($product) {
	        return collect($product->getConfig('shipping.carriers'))
	        			->filter()
	        			->sort()
	        			->keys()
	        			->toJson();
	    });
	}

	public function products()
	{ 
		return StoreProduct::find(\Cart::getContent()->pluck('attributes.product'));
	}
}
