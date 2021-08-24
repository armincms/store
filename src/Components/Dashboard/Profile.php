<?php 
namespace Armincms\Store\Components\Dashboard;
 
use Illuminate\Http\Request; 
use Illuminate\Auth\AuthenticationException;
use Core\Document\Document;
use Core\HttpSite\Component;
use Core\HttpSite\Concerns\IntractsWithLayout; 

class Profile extends Dashboard 
{        
	/**
	 * Route of Component.
	 * 
	 * @var null
	 */
	protected $route = 'profile'; 

	public function layoutName()
	{
		return $this->config('layout', 'clean-profile');
	}
}
