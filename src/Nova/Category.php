<?php

namespace Armincms\Store\Nova;
 
use Illuminate\Http\Request;
use Armincms\Categorizable\Nova\Category as Resource;


class Category extends Resource
{   
    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = 'Store';

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \Armincms\Store\Models\Category::class; 
}
