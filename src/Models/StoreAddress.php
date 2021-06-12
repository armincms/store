<?php

namespace Armincms\Store\Models; 
 
use Armincms\Concerns\Authorization;  


class StoreAddress extends Model  
{ 	  
	use Authorization;

	/**
	 * Query the realted product.
	 * 
	 * @return \Illuminate\Database\Elqoeunt\Relations\BelongsToMany
	 */
	public function product()
	{
		return $this->morphTo();
	}  

	/**
	 * Query the realted StoreOrder.
	 * 
	 * @return \Illuminate\Database\Elqoeunt\Relations\BelongsToMany
	 */
	public function order()
	{
		return $this->belongsTo(StoreOrder::class);
	}  

	/**
	 * Query the realted StoreOrder.
	 * 
	 * @return \Illuminate\Database\Elqoeunt\Relations\BelongsToMany
	 */
	public function city()
	{
		return $this->belongsTo(\Armincms\Location\Models\LocationCity::class);
	} 

	public function fullAddress()
	{
		$this->loadMissing('city.state');

		return  data_get($this, 'city.state.name').' - '.
				data_get($this, 'city.name').' - '.
				$this->address.' - '.
				trans('Zipcode').': '.
				$this->zipcode;
	} 

	public function fullname()
	{
		return $this->user->fullname();
	} 
}
