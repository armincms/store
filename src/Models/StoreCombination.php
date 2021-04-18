<?php

namespace Armincms\Store\Models;
      

class StoreCombination extends Model  
{ 	
	/**
	 * Query the related StoreCombinationValue.
	 * 
	 * @return \Illuminate\Database\Eloqeunt\Reltaions\BelongsTo
	 */
	public function product()
	{
		return $this->belongsTo(StoreProduct::class);
	}

	/**
	 * Query the related StoreCombinationValue.
	 * 
	 * @return \Illuminate\Database\Eloqeunt\Reltaions\BelongsToMany
	 */
	public function values()
	{
		return $this->belongsToMany(StoreAttributeValue::class, 'store_attribute_value_combination');
	}
}
