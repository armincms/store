<?php

namespace Armincms\Store\Models;
 

trait HasPayment 
{    
    /**
     * Mark the model with the "completed" value.
     *
     * @return $this
     */
    public function asPaid()
    {
        return $this->markAs($this->getPaidValue());
    } 

    /**
     * Determine if the value of the model's "marked as" attribute is equal to the "completed" value.
     * 
     * @param  string $value 
     * @return bool       
     */
    public function isPaid()
    {
        return $this->markedAs($this->getPaidValue());
    }

    /**
     * Query the model's `marked as` attribute with the "completed" value.
     * 
     * @param  \Illuminate\Database\Eloquent\Builder $query  
     * @return \Illuminate\Database\Eloquent\Builder       
     */
    public function scopePaid($query)
    {
        return $this->mark($this->getPaidValue());
    }

    /**
     * Set the value of the "marked as" attribute as "completed" value.
     * 
     * @return $this
     */
    public function setPaid()
    {
        return $this->setMarkedAs($this->getPaidValue());
    }

    /**
     * Get the value of the "completed" mark.
     *
     * @return string
     */
    public function getPaidValue()
    {
        return defined('static::PAID_VALUE') ? static::PAID_VALUE : 'paid';
    }
}
