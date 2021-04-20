<?php

namespace Armincms\Store\Nova;

use Illuminate\Http\Request; 
use Laravel\Nova\Panel;
use Laravel\Nova\Http\Requests\{NovaRequest, ResourceIndexRequest};
use Laravel\Nova\Fields\{ID, Text, Number, Select, BooleanGroup, BelongsTo, HasMany};
use Epartment\NovaDependencyContainer\NovaDependencyContainer;
use Whitecube\NovaFlexibleContent\Flexible as FlexibleField;
use OptimistDigital\MultiselectField\Multiselect;
use Armincms\Fields\{Targomaan, BelongsToMany};
use Zareismail\Fields\BelongsTo as CascadeTo;
use Davidpiesse\NovaToggle\Toggle;
use Armincms\Taxation\Nova\Rule;
use Armincms\Store\Helper;
use Inspheric\Fields\Url;

class Product extends Resource
{
    use \Epartment\NovaDependencyContainer\HasDependencies;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \Armincms\Store\Models\StoreProduct::class;  

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'isbn', 'ean', 'upc'
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
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    { 
        return [
            ID::make(__('ID'), 'id')->sortable(), 

            $this->when(! $request->isMethod('get'), function() {
                return Text::make(__('Url'), 'name')->fillUsing(function($request, $model) {
                    $model->saved(function($model) {
                        $model->translations()->get()->each(function($model) {
                            $model->update([
                                'url' => urlencode($model->buildUrl(static::newModel()->component()->route())),
                            ]);
                        });
                    });
                });
            }), 

            Url::make(__('Product Name'), 'url')
                ->exceptOnForms()
                ->alwaysClickable() 
                ->resolveUsing(function($value, $resource, $attribute) use ($request) {
                    return app('site')->findByComponent($request->model()->component())->url(urldecode($value));
                }) 
                ->labelUsing(function() {
                    return $this->name;
                }), 

            Toggle::make(__('Activate Product'), 'active')
                ->required()
                ->rules('required'),

            Select::make(__('Type Of Product'), 'product_type')
                ->options(Helper::productTypes())
                ->displayUsingLabels()
                ->required()
                ->rules('required'),

            Select::make(__('Status Of Product'), 'product_status')
                ->options(Helper::productStatuses())
                ->displayUsingLabels()
                ->required()
                ->rules('required'),

            NovaDependencyContainer::make([
                FlexibleField::make(__('Package Products'), 'products')
                    ->resolver(Flexible\Resolvers\Package::class)
                    ->addLayout(__('Product'), 'product', [
                        Select::make(__('Product'), 'product')
                            ->options(static::newModel()->whereKeyNot($this->id)->get()->keyBy->getKey()->mapInto(static::class)->map->title())
                            ->displayUsingLabels()
                            ->required()
                            ->rules('required'),

                        Number::make(__('Number Of Product'), 'count')
                            ->required()
                            ->rules('required')
                            ->min(1),
                    ])
                    ->collapsed()
                    ->required()
                    ->rules('required')
                    ->button(__('Append Product To Package')), 
            ])->dependsOn('product_type', 'package')->hideFromIndex(),

            Targomaan::make([
                Text::make(__('Product Name'), 'name')
                    ->required()
                    ->rules('required')
                    ->onlyOnForms(),

                $this->slugField(),
            ]),  

            BelongsTo::make(__('Include Tax'), 'tax', Rule::class)
                ->showCreateRelationButton()
                ->withoutTrashed()
                ->hideFromIndex()
                ->nullable(),

            BelongsToMany::make(__('Product Categories'), 'categories', Category::class)
                ->required()
                ->rules('required')
                ->hideFromIndex(),
                
            $this->priceField()->required()->rules('required')->min(0),

            NovaDependencyContainer::make([
                Text::make(__('ISBN'), 'isbn')
                    ->nullable()
                    ->hideFromIndex(),

                Text::make(__('EAN-13 or JAN'), 'ean')
                    ->nullable()
                    ->hideFromIndex(),

                Text::make(__('UPC'), 'upc')
                    ->nullable()
                    ->hideFromIndex(), 
            ])->dependsOnNot('product_type', 'virtual'),

            Targomaan::make([  
                $this->abstractField(__('Summary')),

                $this->tiny(),
            ]),  

            $this->when(! $request instanceof  ResourceIndexRequest, function() { 
                return new Fields\Features(__('Product Features'));
            }),

            new Panel(__('Combinations'), [
                FlexibleField::make(__('Product Combinations'), 'combinations')
                    ->resolver(Flexible\Resolvers\Combination::class)
                    ->addLayout(__('Combination'), 'combination', array_merge([ 
                        Multiselect::make(__('Attributes'), 'attributes')
                            ->belongsToMany(Attribute::class)
                            ->fillUsing(function($request, $model, $requestAttribute, $attribute) {
                                $model->{$attribute} = $request->get($attribute);
                            }),

                    ], Fields\Combination::fields($request)))
                    ->collapsed()
                    ->required()
                    ->rules('required')
                    ->button(__('New Combination'))
                    ->onlyOnForms(), 
            ]),

            new Panel(__('Details'), [ 
                Toggle::make(__('Available For sale'), 'available')
                    ->required()
                    ->rules('required')
                    ->default(true),

                Toggle::make(__('Display Product Price'), 'display_price')
                    ->hideFromIndex()
                    ->required()
                    ->rules('required')
                    ->default(true), 

                Toggle::make(__('Only Online Sale'), 'only_online')
                    ->hideFromIndex()
                    ->required()
                    ->rules('required')
                    ->default(false), 

                Toggle::make(__('On Sale'), 'on_sale')
                    ->required()
                    ->rules('required')
                    ->default(false), 

                $this->imageField(__('Product Gallery'), 'gallery')
                    ->conversionOnPreview('product-thumbnail') 
                    ->conversionOnDetailView('product-thumbnail') 
                    ->conversionOnIndexView('product-thumbnail')
                    ->hideFromIndex(),
            ]),

            new Panel(__('Product Shipping'), [
                Number::make(__('Package Width'), 'config->shipping->width')
                    ->min(0)
                    ->nullable()
                    ->hideFromIndex(),

                Number::make(__('Package Height'), 'config->shipping->height')
                    ->min(0)
                    ->nullable()
                    ->hideFromIndex(),

                Number::make(__('Package Depth'), 'config->shipping->depth')
                    ->min(0)
                    ->nullable()
                    ->hideFromIndex(),

                Number::make(__('Package Weight'), 'config->shipping->weight')
                    ->min(0)
                    ->nullable()
                    ->hideFromIndex(),

                $this->priceField(__('Additional shipping cost'), 'config->shipping->cost')
                    ->hideFromIndex()
                    ->resolveUsing(function($value) { 
                        return floatval($value);
                    }),

                BooleanGroup::make(__('Dedicated Carriers'), 'config->shipping->carriers')
                    ->options(Carrier::newModel()->get()->keyBy->getKey()->map->name)
                    ->hideFromIndex()
                    ->help(__('If selected any carrier, default carriers will be disabled for the product.'))
            ]),

            HasMany::make(__('Combinations'), 'combinations', Combination::class),
        ];
    } 

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [
            new Filters\ProductDetails,
        ];
    }

    /**
     * Return the location to redirect the user after creation.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Laravel\Nova\Resource  $resource
     * @return string
     */
    public static function redirectAfterCreate(NovaRequest $request, $resource)
    {
        return '/resources/'.static::uriKey();
    }

    /**
     * Return the location to redirect the user after update.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Laravel\Nova\Resource  $resource
     * @return string
     */
    public static function redirectAfterUpdate(NovaRequest $request, $resource)
    {
        return '/resources/'.static::uriKey();
    }
}
