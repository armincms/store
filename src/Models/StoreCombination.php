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
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
    	parent::boot();

    	static::deleting(function($model) {
    		$model->attributes()->sync([]);
    	});
    }

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

	/**
	 * Determine if the value of the default attribute is equal to true.
	 * 
	 * @return boolean
	 */
	public function isDefault(): bool
	{
		return boolval($this->default);
	}

	/**
	 * Get the sub name of the item.
	 * 
	 * @return decimal
	 */
	public function name()
	{
		if ($attributes = $this->getRelationValue('attributes')) {
			return $attributes->loadMissing('translations', 'group.translations')->map(function($attribute) {
				return $attribute->group->getTranslation('name') . ':' . $attribute->getTranslation('value');
			})->implode(' - '); 
		} 
	}

	/**
	 * Get the fullname of the item.
	 * 
	 * @return decimal
	 */
	public function fullname()
	{
		return $this->product->name .' - '. $this->name();
	}

	/**
	 * Get the sale price of the item.
	 * 
	 * @return decimal
	 */
	public function salePrice(): float
	{
		return $this->price + $this->product->salePrice();
	}

	/**
	 * Get the real price of the item.
	 * 
	 * @return decimal
	 */
	public function oldPrice(): float
	{ 
		return $this->price + $this->product->oldPrice();
	}

	/**
	 * The cart storage key.
	 * 
	 * @return string
	 */
	public function storageKey()
	{
		return md5($this->product->storageKey() . "combination:{$this->getKey()}");
	}
}
