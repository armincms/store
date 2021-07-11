<?php

namespace Armincms\Store\Models;
 

trait Carryable 
{    
    /**
     * Mark the model with the "shipped" value.
     *
     * @return $this
     */
    public function asShipped()
    {
        return $this->markAs($this->getShippedValue());
    } 

    /**
     * Determine if the value of the model's "marked as" attribute is equal to the "shipped" value.
     * 
     * @param  string $value 
     * @return bool       
     */
    public function isShipped()
    {
        return $this->markedAs($this->getShippedValue());
    }

    /**
     * Query the model's `marked as` attribute with the "shipped" value.
     * 
     * @param  \Illuminate\Database\Eloquent\Builder $query  
     * @return \Illuminate\Database\Eloquent\Builder       
     */
    public function scopeShipped($query)
    {
        return $this->mark($this->getShippedValue());
    }

    /**
     * Set the value of the "marked as" attribute as "shipped" value.
     * 
     * @return $this
     */
    public function setShipped()
    {
        return $this->setMarkedAs($this->getShippedValue());
    }

    /**
     * Get the value of the "shipped" mark.
     *
     * @return string
     */
    public function getShippedValue()
    {
        return defined('static::SHIPPED_VALUE') ? static::SHIPPED_VALUE : 'shipped';
    }
}
