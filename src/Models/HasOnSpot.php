<?php

namespace Armincms\Store\Models;
 
/**
 * When Order pending for payment going to the `onspot` status. 
 * This means stock decreased and wait for payment.
 * When need to validate order for decrease stock, order go to the `pending` status
 */
trait HasOnSpot 
{    
    /**
     * Mark the model with the "completed" value.
     *
     * @return $this
     */
    public function asOnSpot()
    {
        return $this->markAs($this->getOnSpotValue());
    } 

    /**
     * Determine if the value of the model's "marked as" attribute is equal to the "completed" value.
     * 
     * @param  string $value 
     * @return bool       
     */
    public function isOnSpot()
    {
        return $this->markedAs($this->getOnSpotValue());
    }

    /**
     * Query the model's `marked as` attribute with the "completed" value.
     * 
     * @param  \Illuminate\Database\Eloquent\Builder $query  
     * @return \Illuminate\Database\Eloquent\Builder       
     */
    public function scopeOnSpot($query)
    {
        return $this->mark($this->getOnSpotValue());
    }

    /**
     * Set the value of the "marked as" attribute as "completed" value.
     * 
     * @return $this
     */
    public function setOnSpot()
    {
        return $this->setMarkedAs($this->getOnSpotValue());
    }

    /**
     * Get the value of the "completed" mark.
     *
     * @return string
     */
    public function getOnSpotValue()
    {
        return defined('static::ON_SPOT_VALUE') ? static::ON_SPOT_VALUE : 'onspot';
    }
}
