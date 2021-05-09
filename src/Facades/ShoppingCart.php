<?php

namespace Armincms\Store\Facades;

use Illuminate\Support\Facades\Facade;


/**
 * 
 */
class ShoppingCart extends Facade
{ 
    /**
     * Get the registered name of the component.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    protected static function getFacadeAccessor()
    {
    	return 'store.cart';
    }
}