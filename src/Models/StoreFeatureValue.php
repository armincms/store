<?php

namespace Armincms\Store\Models;
      

class StoreFeatureValue extends Model  
{  
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'value' => 'json', 
    ];

    /**
     * Query the related StoreFeature.
     * 
     * @return \Illuminate\Database\Eloqeunt\Builde
     */
    public function feature()
    {
    	return $this->belongsTo(StoreFeature::class);
    }

    /**
     * Query the related StoreFeature.
     * 
     * @return \Illuminate\Database\Eloqeunt\Builde
     */
    public function products()
    {
        return $this->belongsToMany(StoreProduct::class, 'store_feature_value_product');
    }
}
