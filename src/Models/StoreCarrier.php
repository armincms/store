<?php

namespace Armincms\Store\Models;
      
use Armincms\Concerns\HasConfig;


class StoreCarrier extends Model  
{ 	   
	use HasConfig;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
    	'name' => 'json',
    ];

	protected $medias = [
        'logo' => [  
            'disk'  => 'armin.image',
            'conversions' => [
                'logo'
            ]
        ], 
	];  

	/**
	 * Query related TaxationTax.
	 * 
	 * @return \Illuminate\Database\Elqoeunt\Relations\BelongsToMany
	 */
	public function tax()
	{
		return $this->belongsTo(\Armincms\Taxation\Models\TaxationTax::class);
	}

	/**
	 * Query related LocationCity.
	 * 
	 * @return \Illuminate\Database\Elqoeunt\Relations\BelongsToMany
	 */
	public function ranges()
	{
		return $this->belongsToMany(\Armincms\Location\Models\LocationCity::class, 'store_carrier_city')
					->withPivot('id', 'min', 'max', 'cost');
	}

	/**
	 * Get the shippping cost.
	 * 
	 * @return float
	 */
	public function shippingCost(): float
	{
		return $this->free_shipping ? 0 : floatval($this->cost);
	}
}
