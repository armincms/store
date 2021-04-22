<?php

namespace Armincms\Store\Models;
      

class StoreBrand extends Model  
{ 	  
	protected $medias = [ 
        'logo' => [  
            'disk'  => 'armin.image',
            'conversions' => [
                'logo'
            ]
        ], 
	]; 

	/**
	 * Query the related StoreProduct.
	 * 
	 * @return \Illuminate\Database\Eloqeunt\Reltaions\HasOneOrMany
	 */
	public function products()
	{
		return $this->hasMany(StoreProduct::class, 'brand_id');
	} 
}
