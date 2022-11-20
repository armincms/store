<?php 
namespace Armincms\Store\Components;
 
use Illuminate\Http\Request; 
use Core\HttpSite\Component;
use Core\HttpSite\Concerns\IntractsWithLayout;
use Core\Document\Document;
use Armincms\Store\Models\StoreOrder;

class Invoice extends Cart 
{       
	use IntractsWithLayout;

	/**
	 * Route of Component.
	 * 
	 * @var null
	 */
	protected $route = 'invoice/{token}'; 

	public function toHtml(Request $request, Document $docuemnt) : string
	{        
		$order = StoreOrder::viaToken($request->token)->onHold()->orWhere->onSpot()->firstOrFail(); 
		 
		if (! $order->isOnSpot()) $order->asPaid();
		
		return strval(
			$this->firstLayout($docuemnt, $this->config('layout', 'clean-invoice'))->display()
		);  
	}  

	public function saleables()
	{
		return $this->order()->saleables;
	} 

	public function order()
	{
		if (! isset($order)) {
			$this->order = StoreOrder::viaToken(request()->route('token'))->with([
				'saleables.product', 'transactions'
			])->firstOrFail();
		}

		return $this->order; 
	}  
}
