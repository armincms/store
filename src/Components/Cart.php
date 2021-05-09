<?php 
namespace Armincms\Store\Components;
 
use Illuminate\Http\Request; 
use Core\HttpSite\Component;
use Core\HttpSite\Contracts\Resourceable;
use Core\HttpSite\Concerns\IntractsWithResource;
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

	private $type = null;

	public function toHtml(Request $request, Document $docuemnt) : string
	{        
		return (string) $this->firstLayout($docuemnt, $this->config('layout'), 'clean-cart')->display(); 
	}  

	public function products()
	{
		return StoreProduct::find(array_keys(session(\ShoppingCart::storeKey())));
	}  

	public function cartQuantityOf($product)
	{
		return session(\ShoppingCart::storeKey())[$product->id] ?? 0;
	}  

	public function middlewares()
	{
		return ['web'];
	}
}
