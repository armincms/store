<?php

namespace Armincms\Store\Nova;

use Illuminate\Http\Request; 
use Laravel\Nova\Panel;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\{ID, Text, Number, Select, BooleanGroup, BelongsTo};
use Epartment\NovaDependencyContainer\NovaDependencyContainer;
use Whitecube\NovaFlexibleContent\Flexible as FlexibleField;
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

            new Panel(__('Product Features'), [
                tap(FlexibleField::make(__('Product Features'), 'features'), function($flexible) {
                    $flexible->resolver(Flexible\Resolvers\Feature::class)
                             ->button(__('Append Feature To Product'))
                             ->collapsed();

                    Feature::newModel()->with('values')->get()->map(function($feature) use ($flexible) { 
                        $flexible->addLayout($feature->name, "__{$feature->getKey()}__", [
                            Select::make(__('Select Feature Value'), 'value')
                                ->options($feature->values->pluck('value', 'id')->all()),

                            Text::make(__('Or Create Custom Value'), 'custom'),
                        ]);
                    });
                })
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

            new Panel(__('Product Shiiping'), [
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
