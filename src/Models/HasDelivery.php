<?php

namespace Armincms\Store\Models;
 

trait HasDelivery 
{    
    /**
     * Mark the model with the "delivered" value.
     *
     * @return $this
     */
    public function asDelivered()
    {
        return $this->markAs($this->getDeliveredValue());
    } 

    /**
     * Determine if the value of the model's "marked as" attribute is equal to the "delivered" value.
     * 
     * @param  string $value 
     * @return bool       
     */
    public function isDelivered()
    {
        return $this->markedAs($this->getDeliveredValue());
    }

    /**
     * Query the model's `marked as` attribute with the "delivered" value.
     * 
     * @param  \Illuminate\Database\Eloquent\Builder $query  
     * @return \Illuminate\Database\Eloquent\Builder       
     */
    public function scopeDelivered($query)
    {
        return $this->mark($this->getDeliveredValue());
    }

    /**
     * Set the value of the "marked as" attribute as "delivered" value.
     * 
     * @return $this
     */
    public function setDelivered()
    {
        return $this->setMarkedAs($this->getDeliveredValue());
    }

    /**
     * Get the value of the "delivered" mark.
     *
     * @return string
     */
    public function getDeliveredValue()
    {
        return defined('static::DELIVERED_VALUE') ? static::DELIVERED_VALUE : 'delivered';
    }
}
