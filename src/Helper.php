<?php

namespace Armincms\Store;

use Laravel\Nova\Nova;
use Laravel\Nova\Tool;

class Helper
{ 
	/**
	 * Returns array of shipping billings bevaiors.
	 * 
	 * @return array
	 */
	public static function shippingBillings()
	{
		return [
			'price' => __('According to total price'),
			'weight' => __('According to total weight'),
		];
	}

	/**
	 * Returns array of shipping billings bevaiors.
	 * 
	 * @return array
	 */
	public static function outofRangeBehaviors()
	{
		return [
			'apply_highest' => __('Apply the cost of the highest defined range'),
			'disabled' => __('Disable carrier'),
		];
	} 

	/**
	 * Returns array of product types.
	 * 
	 * @return array
	 */
	public static function productTypes(): array
	{
		return [
			'physical' => __('Physical Product'),
			'virtual'  => __('Virtual Product'),
			'package'  => __('Products Package'),
		];
	} 

	/**
	 * Returns array of product status.
	 * 
	 * @return array
	 */
	public static function productStatuses(): array
	{
		return [
			'new'  	=> __('New Product'),
			'old' 	=> __('Old Product'),
			'repaired'  => __('Repaired Product'), 
		];
	}
}
