<?php 
namespace Armincms\Store\Components\Dashboard;
 
use Armincms\Store\Models\StoreOrder;
use Illuminate\Http\Request; 
use Illuminate\Auth\AuthenticationException;
use Core\Document\Document;
use Core\HttpSite\Component;
use Core\HttpSite\Concerns\IntractsWithLayout; 

class Dashboard extends Component 
{       
	use IntractsWithLayout;

	/**
	 * Route of Component.
	 * 
	 * @var null
	 */
	protected $route = 'dashboard/{dashboard?}'; 

	protected $orders;

	public function toHtml(Request $request, Document $docuemnt) : string
	{         
		$this->checkAuthentication();   

		return strval($this->firstLayout($docuemnt, $this->layoutName())->display([
			'dashboard' => trim($request->route('dashboard'), '/')
		]));	
	}  

	public function layoutName()
	{
		return $this->config('layout', 'clean-dashbaord');
	}

	public function checkAuthentication()
	{
		throw_unless(\Auth::guard('web')->check(), AuthenticationException::class, [
			['web'], route('store.login')
		]); 
	}

	public function menus()
	{
		return collect([
			[ 
				'uriKey' => '',
				'label' => __('Dashboard'),
			],
			[ 
				'uriKey' => 'profile',
				'label' => __('My Profile'),
			],
			[ 
				'uriKey' => 'orders',
				'label' => __('My Orders'),
			],
		]);
	}

	public function orders()
	{
		if (! isset($this->orders)) {
			$this->orders = StoreOrder::authenticate()->with('saleables')->latest()->get();
		}

		return $this->orders;
	}
}
