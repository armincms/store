<?php 
namespace Armincms\Store\Components;
 
use Illuminate\Http\Request; 
use Core\HttpSite\Component;
use Core\HttpSite\Concerns\IntractsWithLayout;
use Core\Document\Document;
use Armincms\Store\Models\StoreOrder;

class Checkout extends Cart 
{       
	use IntractsWithLayout;

	/**
	 * Route of Component.
	 * 
	 * @var null
	 */
	protected $route = 'shopping-checkout/{order}'; 

	public function toHtml(Request $request, Document $docuemnt) : string
	{            
		if (! \Auth::guard('web')->check()) { 
			throw new AuthenticationException('Unauthenticated.', ['web'], route('login-register.login')); 
		} 
		
		return (string) $this->firstLayout($docuemnt, $this->config('layout', 'clean-store-checkout'))->display(); 
	}  

	public function saleables()
	{
		return $this->order()->saleables;
	} 

	public function order()
	{
		if (! isset($order)) {
			$this->order = StoreOrder::viaCode(request()->route('order'))->with('saleables.product')->firstOrFail();
		}

		return $this->order; 
	}  
}
