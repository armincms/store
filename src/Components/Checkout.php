<?php 
namespace Armincms\Store\Components;
 
use Illuminate\Http\Request; 
use Core\HttpSite\Component;
use Core\HttpSite\Concerns\IntractsWithLayout;
use Core\Document\Document;

class Checkout extends Cart 
{       
	use IntractsWithLayout;

	/**
	 * Route of Component.
	 * 
	 * @var null
	 */
	protected $route = 'shopping-checkout'; 

	public function toHtml(Request $request, Document $docuemnt) : string
	{         
		return (string) $this->firstLayout($docuemnt, $this->config('layout'), 'clean-checkout')->display(); 
	}    
}
