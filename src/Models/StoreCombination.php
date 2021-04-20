<?php

namespace Armincms\Store\Models;
      

class StoreCombination extends Model  
{ 	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'price', 'width', 'height', 'weight', 'default', 'quantity'
    ];

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
	 * Query the related StoreAttribute.
	 * 
	 * @return \Illuminate\Database\Eloqeunt\Reltaions\BelongsToMany
	 */
	public function attributes()
	{
		return $this->belongsToMany(StoreAttribute::class, 'store_attribute_combination');
	}

	/**
	 * Set the price attribute to ensure is float.
	 * 
	 * @param float|null $price 
	 */
	public function setPriceAttribute($price = null)
	{
		$this->attributes['price'] = floatval($price);
	}

	/**
	 * Set the quantity attribute to ensure is numeric.
	 * 
	 * @param integer|null $quantity 
	 */
	public function setQuantityAttribute($quantity = null)
	{
		$this->attributes['quantity'] = intval($quantity);
	} 

	/**
	 * Set the width attribute to ensure is numeric.
	 * 
	 * @param integer|null $width 
	 */
	public function setWidthAttribute($width = null)
	{
		$this->attributes['width'] = intval($width);
	} 

	/**
	 * Set the height attribute to ensure is numeric.
	 * 
	 * @param integer|null $height 
	 */
	public function setHeightAttribute($height = null)
	{
		$this->attributes['height'] = intval($height);
	} 

	/**
	 * Set the weight attribute to ensure is numeric.
	 * 
	 * @param integer|null $weight 
	 */
	public function setWeightAttribute($weight = null)
	{
		$this->attributes['weight'] = intval($weight);
	} 
}
