<?php 

namespace Armincms\Store\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
use Armincms\Store\Models\StoreAddress;  
 
class AddressController extends Controller
{
	public function store(Request $request)
	{
		$request->validate([  
			'name' 	=> 'required|string', 
			'city' 	=> 'required|numeric', 
			'address' => 'required|string',
			'phone' => 'required|numeric',
			'mobile' => 'required|numeric',
			'zipcode' => 'required|string',
			'default' => 'integer',
		]); 

		$address = tap((new StoreAddress)->forceFill([
			'city_id' => $request->city,
			'address' => $request->address,
			'name' => $request->name,
			'phone' => $request->phone,
			'mobile' => $request->mobile,
			'zipcode' => $request->zipcode,
		]), function($model) {
			\DB::transaction(function() use ($model) {
				$model->save();
			}); 
		});

		$address->user()->associate($request->user())->save();

		return back()->with(['message' => __('Address successfully stored.')]);
	} 

	public function delete(Request $request)
	{
		StoreAddress::findOrFail($request->id)->delete();

		return back()->with(['message' => __('Address successfully deleted.')]);
	}
}