<?php

namespace Armincms\Store\Models; 

use Armincms\Categorizable\Contracts\Categorizable;
use Armincms\Categorizable\Concerns\InteractsWithCategories;
use Core\Crud\Concerns\SearchEngineOptimizeTrait;
use Core\HttpSite\Concerns\IntractsWithSite; 
use Armincms\Concerns\{HasConfig, InteractsWithLayouts}; 
use Core\HttpSite\Component;  


class StoreProduct extends Model implements Categorizable
{ 	  
	use HasConfig, InteractsWithLayouts, IntractsWithSite, SearchEngineOptimizeTrait;

	protected $medias = [
        'gallery' => [  
            'disk'  => 'armin.image',
            'multiple' => true,
            'conversions' => [
                'common', 'product'
            ]
        ], 
	];  

	protected $translator = 'layeric';

	const TRANSLATION_MODEL = StoreProductTranslation::class;

	/**
	 * Query the realted StoreProduct.
	 * 
	 * @return \Illuminate\Database\Elqoeunt\Relations\BelongsToMany
	 */
	public function products()
	{
		return $this->belongsToMany(static::class, 'store_package', 'product_id', 'related_id')
					->withPivot('count');
	} 

    /**
     * Query the related categories.
     * 
     * @return \Illuminate\Database\Eloqenut\Relations\BelongsToMany
     */
    public function categories() 
    {
        return $this->morphToMany(Category::class, 'categorizable', 'categorizable');
    }

	/**
	 * Query the realted TaxationTax.
	 * 
	 * @return \Illuminate\Database\Elqoeunt\Relations\BelongsTo
	 */
	public function tax()
	{
		return $this->belongsTo(\Armincms\Taxation\Models\TaxationRule::class);
	} 

	/**
	 * Query the realted StoreFeatureValue.
	 * 
	 * @return \Illuminate\Database\Elqoeunt\Relations\BelongsToMany
	 */
	public function values()
	{
		return $this->belongsToMany(StoreFeatureValue::class, 'store_feature_value_product');
	}  

	/**
	 * Query the realted StoreFeatureValue.
	 * 
	 * @return \Illuminate\Database\Elqoeunt\Relations\HasOneOrMany
	 */
	public function combinations()
	{
		return $this->hasMany(StoreCombination::class, 'product_id');
	}  

	/**
	 * Query where "active" attribute is true.
	 * 
	 * @return \Illuminate\Database\Elqoeunt\Builder
	 */
	public function scopeActive($query)
	{
		return $query->whereActive(true);
	}

	/**
	 * Query where "available" attribute is true.
	 * 
	 * @return \Illuminate\Database\Elqoeunt\Builder
	 */
	public function scopeAvailable($query)
	{
		return $query->whereAvailable(true);
	}

	/**
	 * Query where "only_online" attribute is true.
	 * 
	 * @return \Illuminate\Database\Elqoeunt\Builder
	 */
	public function scopeOnlyOnline($query)
	{
		return $query->whereOnlyOnline(true);
	}

	/**
	 * Query where "on_sale" attribute is true.
	 * 
	 * @return \Illuminate\Database\Elqoeunt\Builder
	 */
	public function scopeOnSale($query)
	{
		return $query->whereOnSale(true);
	}

	/**
	 * Query where "display_price" attribute is true.
	 * 
	 * @return \Illuminate\Database\Elqoeunt\Builder
	 */
	public function scopeDisplayPrice($query)
	{
		return $query->whereDisplayPrice(true);
	}

    public function component() : Component
    {
    	return new \Armincms\Store\Components\Product;
    }

    public function galleryImages()
    { 
    	return $this->getMedia('gallery')->map(function($media) {
    		return $this->getConversions($media, config('blog.schemas', [
    			'product-larg', 'product-mid', 'product-thumbnail'
    		]));
    	});
    } 
}
