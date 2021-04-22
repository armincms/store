<?php

namespace Armincms\Store\Nova;

use Illuminate\Http\Request; 
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\{ID, Text, Number, Select, HasMany};
use Armincms\Fields\Targomaan;

class AttributeGroup extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \Armincms\Store\Models\StoreAttributeGroup::class;   

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id'
    ];  

    /**
     * The columns that should be searched as json.
     *
     * @var array
     */
    public static $searchJson = [ 
    ]; 

    /**
     * The columns that should be searched as json.
     *
     * @var array
     */
    public static $searchTranslations = [ 
        'name'
    ]; 

    /**
     * The relationships that should be eager loaded when performing delete query.
     *
     * @var array
     */ 
    public static $preventDelete = ['attributes']; 

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    { 
        return [
            ID::make(__('ID'), 'id')->sortable(),

            Select::make(__('Attribute Type'), 'type')->options([
                    'dropdown' => __('Dropdown list'),
                    'radio' => __('Radio Buttons'),
                    'color' => __('Color or Texture'),
                ])
                ->displayUsingLabels()
                ->required()
                ->rules('required'),

            Targomaan::make([
                Text::make(__('Attribute name'), 'name')
                    ->required()
                    ->rules('required'),

                $this->slugField(),
            ]), 

            HasMany::make(__('Attributes'), 'attributes', Attribute::class),
        ];
    }

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return Attribute::label();
    }
}
