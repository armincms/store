<?php

namespace Armincms\Store\Http\Requests;

trait InteractsWithProduct
{
    /**
     * Find the product for the incoming request.
     * 
     * @return \Illuminate\Datbase\Eloquent\Model
     */
    public function findProductOrFail($productId = null)
    {
        return once(function () use ($productId) {
            return $this->newModel()->findOrFail($productId ?? $this->route('product'));
        });
    }

    /**
     * Instanciate new query.
     * 
     * @return \Illuminate\Database\Elqoeunt\Builder
     */
    public function newProduct()
    {
        return $this->newModel()->active();
    }

    /**
     * Instanciate new query.
     * 
     * @return \Illuminate\Database\Elqoeunt\Builder
     */
    public function newModel()
    {
        return new \Armincms\Store\Models\StoreProduct;
    }
}
