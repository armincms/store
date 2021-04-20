<?php

namespace Armincms\Store\Models;
      

class StoreAttributeGroup extends Model  
{ 	 
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get the targomaan driver.
     * 
     * @return string
     */
    public function translator() : string
    {
    	return 'layeric';
    }

	/**
	 * Query the related StoreAttribute.
	 * 
	 * @return \Illuminate\Database\Eloqeunt\Builder
	 */
	public function attributes()
	{
		return $this->hasMany(StoreAttribute::class, 'group_id');
	}
}
