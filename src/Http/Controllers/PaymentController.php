<?php 

namespace Armincms\Store\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
use Armincms\Store\Models\StoreOrder; 
use Armincms\Arminpay\Models\ArminpayGateway;

class PaymentController extends Controller
{
	public function handle(Request $request)
	{
		$request->validate([ 
			'gateway' => 'required|numeric', 
		]);  

		$order = StoreOrder::viaCode($request->route('order'))->firstOrFail()->asOnHold();
		$gateway = ArminpayGateway::findOrFail($request->gateway);

		if ($gateway->driver === 'sandbox') $order->asOnSpot();

		return $gateway->checkout($request, $order);  
	} 
}