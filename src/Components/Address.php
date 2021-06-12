<?php 
namespace Armincms\Store\Components;
 
use Illuminate\Http\Request;  
use Core\Document\Document;
use Armincms\Dashboard\Components\Dashboard as Component;
use Armincms\Store\Models\StoreAddress;

class Address extends Component 
{        
	/**
	 * Route of Component.
	 * 
	 * @var null
	 */
	protected $route = 'address'; 

	public function toHtml(Request $request, Document $docuemnt) : string
	{        
		return (string) $this->firstLayout($docuemnt, $this->config('layout'), 'clean-address')->display(); 
	}    

	public function addresses()
	{
		return StoreAddress::authenticate()->with('city')->get();
	}
}
