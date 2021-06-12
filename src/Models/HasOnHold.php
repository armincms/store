<?php

namespace Armincms\Store\Models;
 
/**
 * When Order pending for payment going to the `onhold` status. 
 * This means stock decreased and wait for payment.
 * When need to validate order for decrease stock, order go to the `pending` status
 */
trait HasOnHold 
{    
    /**
     * Mark the model with the "completed" value.
     *
     * @return $this
     */
    public function asOnHold()
    {
        return $this->markAs($this->getOnHoldValue());
    } 

    /**
     * Determine if the value of the model's "marked as" attribute is equal to the "completed" value.
     * 
     * @param  string $value 
     * @return bool       
     */
    public function isOnHold()
    {
        return $this->markedAs($this->getOnHoldValue());
    }

    /**
     * Query the model's `marked as` attribute with the "completed" value.
     * 
     * @param  \Illuminate\Database\Eloquent\Builder $query  
     * @return \Illuminate\Database\Eloquent\Builder       
     */
    public function scopeOnHold($query)
    {
        return $this->mark($this->getOnHoldValue());
    }

    /**
     * Set the value of the "marked as" attribute as "completed" value.
     * 
     * @return $this
     */
    public function setOnHold()
    {
        return $this->setMarkedAs($this->getOnHoldValue());
    }

    /**
     * Get the value of the "completed" mark.
     *
     * @return string
     */
    public function getOnHoldValue()
    {
        return defined('static::ON_HOLD_VALUE') ? static::ON_HOLD_VALUE : 'onhold';
    }
}
