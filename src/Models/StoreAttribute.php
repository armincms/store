<?php

namespace Armincms\Store\Models;
      

class StoreAttribute extends Model  
{ 	
	/**
	 * Query the related StoreAttributeValue.
	 * 
	 * @return \Illuminate\Database\Eloqeunt\Builder
	 */
	public function values()
	{
		return $this->hasMany(StoreAttributeValue::class, 'attribute_id');
	}
}
