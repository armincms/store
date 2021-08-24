<?php

namespace Armincms\Store\Models;
 

trait HasCancelation 
{    
    /**
     * Mark the model with the "canceled" value.
     *
     * @return $this
     */
    public function asCanceled()
    {
        return $this->markAs($this->getCanceledValue());
    } 

    /**
     * Determine if the value of the model's "marked as" attribute is equal to the "canceled" value.
     * 
     * @param  string $value 
     * @return bool       
     */
    public function isCanceled()
    {
        return $this->markedAs($this->getCanceledValue());
    }

    /**
     * Query the model's `marked as` attribute with the "canceled" value.
     * 
     * @param  \Illuminate\Database\Eloquent\Builder $query  
     * @return \Illuminate\Database\Eloquent\Builder       
     */
    public function scopeCanceled($query)
    {
        return $this->mark($this->getCanceledValue());
    }

    /**
     * Set the value of the "marked as" attribute as "canceled" value.
     * 
     * @return $this
     */
    public function setCanceled()
    {
        return $this->setMarkedAs($this->getCanceledValue());
    }

    /**
     * Get the value of the "canceled" mark.
     *
     * @return string
     */
    public function getCanceledValue()
    {
        return defined('static::CANCELED_VALUE') ? static::CANCELED_VALUE : 'canceled';
    }
}
