<?php

namespace Armincms\Store\Models;

use Kalnoy\Nestedset\NodeTrait;          

class StoreAttributeGroup extends Model  
{ 	 
    use NodeTrait;

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

    /**
     * Relation to children.
     *
     * @return HasMany
     */
    public function children()
    {
        return $this->attributes();
    }
}
