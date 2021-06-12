<?php 
namespace Armincms\Store\Components;
 
use Illuminate\Http\Request; 
use Core\HttpSite\Component; 
use Core\HttpSite\Concerns\IntractsWithLayout;
use Core\Document\Document;
use Armincms\Store\Models\StoreProduct;

class Cart extends Component 
{       
	use IntractsWithLayout;

	/**
	 * Route of Component.
	 * 
	 * @var null
	 */
	protected $route = 'shopping-cart';

	private $cachedProducts;

	public function toHtml(Request $request, Document $docuemnt) : string
	{        
		return (string) $this->firstLayout($docuemnt, $this->config('layout'), 'clean-cart')->display(); 
	}  

	public function products()
	{
		if (! isset($this->cachedProducts)) {
			$this->cachedProducts = StoreProduct::find(array_keys(\ShoppingCart::all()));
		}

		return $this->cachedProducts; 
	}   
}
