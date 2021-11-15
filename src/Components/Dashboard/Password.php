<?php 
namespace Armincms\Store\Components\Dashboard;
 
use Armincms\Store\Models\StoreOrder; 
use Core\Document\Document;
use Core\HttpSite\Component;
use Core\HttpSite\Concerns\IntractsWithLayout; 
use Illuminate\Http\Request; 
use Illuminate\Auth\AuthenticationException;

class Password extends Component 
{       
	use IntractsWithLayout;

	/**
	 * Route of Component.
	 * 
	 * @var null
	 */
	protected $route = 'password-reset'; 

	public function toHtml(Request $request, Document $docuemnt) : string
	{         
		$layout = $this->firstLayout($docuemnt, $this->config('layout', 'myriad-login'));

		return strval($layout->display(['layout' => 'password-reset']));
	}   
}
