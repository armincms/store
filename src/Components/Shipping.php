<?php 
namespace Armincms\Store\Components;
 
use Illuminate\Http\Request; 
use Illuminate\Auth\AuthenticationException;
use Core\Document\Document;
use Core\HttpSite\Component;
use Core\HttpSite\Concerns\IntractsWithLayout;
use Armincms\Store\Models\{StoreCarrier, StoreAddress};

class Shipping extends Cart 
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
		if (! \Auth::guard('web')->check()) { 
			throw new AuthenticationException('Unauthenticated.', ['web'], route('login-register.login')); 
		}


		return with($this->firstLayout($docuemnt, $this->config('layout'), 'clean-shipping'), function($layout) {
				return (string) $layout->display();
		});		
	} 

	public function addresses()
	{  
		return StoreAddress::authenticate()->get();
	}

	public function carriers()
	{ 
		$carriers = $this->products()->flatMap->getConfig('shipping.carriers')->filter();

		return StoreCarrier::with('ranges')->when($carriers->isNotEmpty(), function($query) use ($carriers) {
			$query->whereKey($carriers->all());
		})->get();
	}
}
