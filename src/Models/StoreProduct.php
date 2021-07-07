<?php

namespace Armincms\Store\Models; 

use Illuminate\Database\Eloquent\SoftDeletes; 
use Armincms\Categorizable\Contracts\Categorizable;
use Armincms\Categorizable\Concerns\InteractsWithCategories;
use Armincms\Taggable\Concerns\InteractsWithTags;
use Core\Crud\Concerns\SearchEngineOptimizeTrait;
use Core\HttpSite\Concerns\IntractsWithSite; 
use Armincms\Contracts\HasLayout; 
use Armincms\Concerns\{HasConfig, InteractsWithLayouts}; 
use Core\HttpSite\Component;  


class StoreProduct extends Model implements Categorizable, HasLayout
{ 	  
	use HasConfig, InteractsWithLayouts, InteractsWithTags, IntractsWithSite;
	use SearchEngineOptimizeTrait, SoftDeletes;

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
     * Get the layouts group name.
     * 
     * @return string
     */
    public function layoutGroupName() {
        return 'store.product'; 
    }

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
	 * Query the realted StoreBrand.
	 * 
	 * @return \Illuminate\Database\Elqoeunt\Relations\BelongsTo
	 */
	public function brand()
	{
		return $this->belongsTo(StoreBrand::class);
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

	/**
	 * Query where "product_type" attribute is 'physical'.
	 * 
	 * @return \Illuminate\Database\Elqoeunt\Builder
	 */
	public function scopePhysicalProduct($query)
	{
		return $query->where($query->qualifyColumn('product_type'), 'physical');
	} 

	/**
	 * Query where "product_type" attribute is 'virtual'.
	 * 
	 * @return \Illuminate\Database\Elqoeunt\Builder
	 */
	public function scopeVirtualProduct($query)
	{
		return $query->where($query->qualifyColumn('product_type'), 'virtual');
	} 

	/**
	 * Query where "product_type" attribute is 'package'.
	 * 
	 * @return \Illuminate\Database\Elqoeunt\Builder
	 */
	public function scopePackageProduct($query)
	{
		return $query->where($query->qualifyColumn('product_type'), 'package');
	}

	/**
	 * Query where "product_status" attribute is 'old'.
	 * 
	 * @return \Illuminate\Database\Elqoeunt\Builder
	 */
	public function scopeOldProduct($query)
	{
		return $query->where($query->qualifyColumn('product_status'), 'old');
	} 

	/**
	 * Query where "product_status" attribute is 'new'.
	 * 
	 * @return \Illuminate\Database\Elqoeunt\Builder
	 */
	public function scopeNewProduct($query)
	{
		return $query->where($query->qualifyColumn('product_status'), 'new');
	} 

	/**
	 * Query where "product_status" attribute is 'repaired'.
	 * 
	 * @return \Illuminate\Database\Elqoeunt\Builder
	 */
	public function scopeRepairedProduct($query)
	{
		return $query->where($query->qualifyColumn('product_status'), 'repaired');
	} 

	/**
	 * Query where "brand_id" attribute in the given brands.
	 * 
	 * @return \Illuminate\Database\Elqoeunt\Builder
	 */
	public function scopeBrandedBy($query, $brands = [])
	{
		return $query->whereIn($query->qualifyColumn('brand_id'), (array) $brands);
	} 

    /**
     * Get the tag url.
     * 
     * @return string
     */
    public function url(): string
    {
        return $this->site()->url(urldecode($this->getTranslation('url')));
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

    public function featuredImage($conversion = 'product-thumbnail')
    { 
    	return data_get($this->galleryImages()->first(), $conversion);
    } 


	/**
	 * Get the sale price currency.
	 * 
	 * @return decimal
	 */
	public function saleCurrency(): string
	{
		return config('nova.currency', 'IRR');
	}

	/**
	 * Get the sale price of the item.
	 * 
	 * @return decimal
	 */
	public function salePrice(): float
	{
		return $this->price;
	}

	/**
	 * Get the real price of the item.
	 * 
	 * @return decimal
	 */
	public function oldPrice(): float
	{
		return $this->price;
	}

	/**
	 * Get the item name.
	 * 
	 * @return decimal
	 */
	public function getNameAttribute()
	{
		return $this->getTranslation('name');
	}

	/**
	 * Get the item name.
	 * 
	 * @return decimal
	 */
	public function saleName(): string
	{
		return $this->name;
	}

	/**
	 * Get the item summary.
	 * 
	 * @return decimal
	 */
	public function getSummaryAttribute()
	{
		return $this->getTranslation('summary');
	}

	/**
	 * Get the item description.
	 * 
	 * @return decimal
	 */
	public function saleDescription(): string
	{
		return $this->summary;
	}

	/**
	 * The cart storage key.
	 * 
	 * @return string
	 */
	public function storageKey()
	{
		return md5("product:{$this->getKey()}");
	}

    /**
     * Convert the model instance to an array.
     *  
     * @return array
     */ 
    public function toArray(): array
    {
    	return array_merge(parent::toArray(), [
    		'url' => $this->url(),
    		'sale_price' => $this->salePrice(),
    		'old_price' => $this->oldPrice(),
    		'gallery' => $this->galleryImages(),
    	]);
    }

    /**
     * Get an attribute from the model.
     *
     * @param  string  $key
     * @return mixed
     */
    public function getAttribute($key)
    {
    	if (preg_match('/([0-9]+)__attribute/', $key, $matches)) {
    		$this->loadMissing('combinations.attributes');

    		return $this->combinations->find($matches[1])->getRelationValue('attributes');
    	}

    	if (preg_match('/.*__attribute/', $key, $matches)) {
    		return collect();
    	}

    	return parent::getAttribute($key);
    }

    /**
     * Handle dynamic method calls into the model.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
    	if (preg_match('/([0-9]+)__attribute/', $method, $matches)) {
    		return $this->combinations()->findOrFail($matches[1])->attributes(); 
    	}

    	if (preg_match('/.*__attribute/', $method, $matches)) {
    		return $this->combinations(); 
    	}

    	return parent::__call($method, $parameters);
    }
}
