<?php

namespace Armincms\Store\Models;     

class StoreAttribute extends Model  
{   
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'name'
    ]; 

    /**
     * Get name of the tree.
     * 
     * @return [type] [description]
     */
    public function getNameAttribute()
    {
        return $this->value;
    }

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
     * Query the related StoreAttributeGroup.
     * 
     * @return \Illuminate\Database\Eloqeunt\Builde
     */
    public function group()
    {
    	return $this->belongsTo(StoreAttributeGroup::class);
    }

    /**
     * Query the related StoreCombination.
     * 
     * @return \Illuminate\Database\Eloqeunt\Reltaions\BelongsToMany
     */
    public function combinations()
    {
        return $this->belongsToMany(StoreAttribute::class, 'store_attribute_combination');
    } 
}
