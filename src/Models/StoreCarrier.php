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
	 * Get the shippping cost.
	 * 
	 * @return float
	 */
	public function shippingCost(): float
	{
		return $this->free_shipping ? 0 : floatval($this->cost);
	}

    /**
     * Get all of the countries that are assigned this carrier.
     */
    public function countries()
    {
        return $this->morphedByMany(
        	\Armincms\Location\Models\LocationCountry::class, 
        	'location', 
        	'store_carrier_range'
        )->withPivot('ranges');
    }

    /**
     * Get all of the states that are assigned this carrier.
     */
    public function states()
    {
        return $this->morphedByMany(
        	\Armincms\Location\Models\LocationState::class, 
        	'location', 
        	'store_carrier_range'
        )->withPivot('ranges');
    }

    /**
     * Get all of the cities that are assigned this carrier.
     */
    public function cities()
    {
        return $this->morphedByMany(
        	\Armincms\Location\Models\LocationCity::class, 
        	'location', 
        	'store_carrier_range'
        )->withPivot('ranges');
    }

    /**
     * Get all of the zones that are assigned this carrier.
     */
    public function zones()
    {
        return $this->morphedByMany(
        	\Armincms\Location\Models\LocationZone::class, 
        	'location', 
        	'store_carrier_range'
        )->withPivot('ranges');
    }
}
