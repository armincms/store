<?php

namespace Armincms\Store\Models; 

use Illuminate\Database\Eloquent\SoftDeletes;
use Zareismail\Markable\{Markable, HasDraft, HasPending};
use Armincms\Arminpay\Contracts\{Billable, Trackable};
use Armincms\Arminpay\Concerns\{HasTrackingCode, InteractsWithTransactions};  
use Armincms\Concerns\Authorization;  


class StoreOrder extends Model implements Billable, Trackable 
{ 	  
	use InteractsWithTransactions, HasTrackingCode, Authorization, SoftDeletes;
    use Carryable, HasDelivery, HasCancelation, HasDraft, HasPending, HasCompletion, HasPayment, HasOnHold, Markable;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
    	'created_at' => 'datetime',
    	'total' => 'float',
    ];

    public static function boot()
    {
    	parent::boot();

    	static::saving(function($model) {
    		if (empty($model->token)) {
    			$model->forceFill([
    				'token' => md5($model . time()),
    			]);
    		}
    	});
    }

    /**
     * Query with the given tracking_code.
     * 
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string $code 
     * @return \Illuminate\Database\Eloquent\Builder       
     */
    public function scopeViaToken($query, string $code)
    {
        return $query->where('token', $code);
    }

	/**
	 * Query the realted saleable.
	 * 
	 * @return \Illuminate\Database\Elqoeunt\Relations\BelongsTo
	 */
	public function saleables()
	{
		return $this->hasMany(OrderItem::class, 'order_id');
	}  

	/**
	 * Query the realted StoreProduct.
	 * 
	 * @return \Illuminate\Database\Elqoeunt\Relations\BelongsTo
	 */
	public function products()
	{
		return $this->belongsToMany(StoreProduct::class, 'store_saleables', 'order_id', 'product_id')
					->using(OrderItem::class)
					->withPivot('name', 'description', 'count', 'combination_id', 'sale_price', 'old_price');
	}   

	/**
	 * Query the realted StoreAddress.
	 * 
	 * @return \Illuminate\Database\Elqoeunt\Relations\BelongsTo
	 */
	public function address()
	{
		return $this->belongsTo(StoreAddress::class);
	}   

	/**
	 * Get total price of the item.
	 * 
	 * @return float
	 */
	public function totalPrice(): float
	{
		return floatval($this->saleables->sum->total() + $this->carrierCost());
	} 

	/**
	 * Get total cost of carriers.
	 * 
	 * @return float
	 */
	public function carrierCost()
	{
		return floatval($this->saleables->pluck('details.carrier')->unique('id')->sum('cost'));
	}

	/**
	 * The payment amount.
	 * 
	 * @return float
	 */
	public function amount(): float
	{
		return $this->totalPrice();
	}

	/**
	 * The payment currency.
	 * 
	 * @return float
	 */
	public function currency(): string
	{
		return $this->currency_code;
	}

	/**
	 * Return the path that should be called after the payment.
	 * 
	 * @return float
	 */
	public function callback(): string
	{
		return $this->finish_callback;
	}
}
