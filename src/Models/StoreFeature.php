<?php

namespace Armincms\Store\Models;
      

class StoreFeature extends Model  
{ 	
	/**
	 * Query the related StoreFeatureValue.
	 * 
	 * @return \Illuminate\Database\Eloqeunt\Builder
	 */
	public function values()
	{
		return $this->hasMany(StoreFeatureValue::class, 'feature_id');
	}
}
