<?php

namespace Armincms\Store\Models;
      

class StoreAttributeValue extends Model  
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
     * Query the related StoreAttribute.
     * 
     * @return \Illuminate\Database\Eloqeunt\Builde
     */
    public function attribute()
    {
    	return $this->belongsTo(StoreAttribute::class);
    }
}
