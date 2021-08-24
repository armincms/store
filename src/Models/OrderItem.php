<?php

namespace Armincms\Store\Models; 

use Illuminate\Database\Eloquent\Relations\Pivot;  


class OrderItem extends Pivot  
{ 	   
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table= 'store_saleables';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
    	'details' => 'collection',
    ];

	/**
	 * Query the realted product.
	 * 
	 * @return \Illuminate\Database\Elqoeunt\Relations\BelongsToMany
	 */
	public function product()
	{
		return $this->belongsTo(StoreProduct::class);
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
	 * Get total price of the item.
	 * 
	 * @return float
	 */
	public function total(): float
	{
		return floatval($this->sale_price * $this->count);
	}
}
