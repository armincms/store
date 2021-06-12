<?php

namespace Armincms\Store;
 

class Cart
{ 
	/**
	 * The cart items.
	 * 
	 * @var array
	 */
	protected $items = [];

	public function __construct()
	{
		$this->items = (array) session()->get(static::storeKey());
	}

	/**
	 * Determine if the given product exists in the cart.
	 * 
	 * @param  int $productId 
	 * @return boolean            
	 */
	public function has(int $productId)
	{
		return $this->count($productId) > 0;
	} 

	/**
	 * Add a product into the cart with the given quantity.
	 * 
	 * @param  int $productId 
	 * @param  int $quantity 
	 * @return $this            
	 */
	public function add(int $productId, int $quantity = 1)
	{
		if ($this->has($productId)) { 
			return $this->increment($productId, $quantity);
		}

		$this->items[$productId] = $quantity; 

		return $this;		
	}

	/**
	 * Update quantity of a products in cart.
	 * 
	 * @param  int $productId 
	 * @param  int $quantity 
	 * @return $this            
	 */
	public function update(int $productId, int $quantity = 1)
	{
		if (! $this->has($productId)) { 
			return $this->add($productId, $quantity);
		}

		$this->items[$productId] = $quantity; 

		return $this;		
	} 

	/**
	 * Increase the quantity of a given item.
	 * 
	 * @param  int $productId 
	 * @param  int $quantity 
	 * @return $this            
	 */
	public function increment(int $productId, int $quantity = 1)
	{
		if (! $this->has($productId)) {
			return $this->add($productId, $quantity);
		}

		$this->items[$productId] += $quantity; 

		return $this;		
	}

	/**
	 * Decrease the quantity of a given item.
	 * 
	 * @param  int $productId 
	 * @param  int $quantity 
	 * @return $this            
	 */
	public function decrement(int $productId, int $quantity = 1)
	{
		if (! $this->has($productId) || $this->quantity($productId) < $quantity) { 
			return $this->remove($productId); 
		}

		$this->increment($productId, -abs($quantity)); 

		return $this;		
	}

	/**
	 * Remove the given product from cart.
	 * 
	 * @param  int    $productId 
	 * @return $this            
	 */
	public function remove(int $productId)
	{
		if ($this->has($productId)) {
			unset($this->items[$productId]);
		}

		return $this;		
	}

	/**
	 * Get count of the product in the cart.
	 * 
	 * @param  int    $productId 
	 * @return int            
	 */
	public function quantity(int $productId) : int
	{ 
		return $this->items[$productId] ?? 0;		
	}

	/**
	 * Return all of the available items.
	 * 
	 * @return array
	 */
	public function all()
	{
		return array_filter($this->items);
	}

	/**
	 * Return all of the available items.
	 * 
	 * @return array
	 */
	public function count(int $productId)
	{  
		return intval($this->items[$productId] ?? 0);
	}

	/**
	 * Delete the cart with all of the items.
	 * 
	 * @return array
	 */
	public function delete()
	{
		$this->items = [];

		session()->forget(static::storeKey());

		return $this;
	}

	/**
	 * Get the session storage key.
	 * 
	 * @return string
	 */
	public static function storeKey(): string
	{
		return 'store.shopping-cart.items';
	}

	public function __destruct()
	{ 
		session([
			static::storeKey() => $this->items
		]);  
	}
}
