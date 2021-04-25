<?php

namespace Armincms\Store\Nova\Fields;

use Laravel\Nova\Panel;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest; 

class Scripts extends Field
{  
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'armincms-store'; 

    /**
     * Indicates if the element should be shown on the index view.
     *
     * @var \Closure|bool
     */
    public $showOnIndex = false;

    /**
     * Indicates if the element should be shown on the detail view.
     *
     * @var \Closure|bool
     */
    public $showOnDetail = false;

    /**
     * Create a new field.
     *
     * @param  string  $name
     * @param  string|callable|null  $attribute
     * @param  callable|null  $resolveCallback
     * @return void
     */
    public function __construct($name = null, $attribute = null, callable $resolveCallback = null)
    { 
    }

    /**
     * Resolve the field's value.
     *
     * @param  mixed  $resource
     * @param  string|null  $attribute
     * @return void
     */
    public function resolve($resource, $attribute = null)
    {
    } 

    /**
     * Hydrate the given attribute on the model based on the incoming request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  object  $model
     * @return mixed
     */
    public function fill(NovaRequest $request, $model)
    {
    }
}