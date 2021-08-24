<?php

namespace Armincms\Store;

use Illuminate\Support\Facades\Gate; 
use Illuminate\Support\ServiceProvider as LaravelServiceProvider; 
use Laravel\Nova\Nova as LaravelNova; 

class ServiceProvider extends LaravelServiceProvider 
{ 

    /**
     * Register any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->configureWebComponents(); 
        $this->registerResources();
        $this->registerPolicies(); 
        $this->registeLocates();   
        $this->routes(); 

        $this->app->resolving('conversion', function($manager) {
            $manager->extend('logo', function() {
                return new Conversions\LogoConversion;
            });
            $manager->extend('product', function() {
                return new Conversions\ProductConversion;
            });
        });

        $this->app->booted(function($app) { 
            LaravelNova::serving(function() {
                LaravelNova::script('armincms-store-range', __DIR__.'/../dist/js/range.js'); 
                LaravelNova::script('armincms-store-scrips', __DIR__.'/../dist/js/field.js'); 
                LaravelNova::script('nova-nested-tree-attach-many', __DIR__.'/../dist/js/tree.js'); 
            });  
        });
    }

    public function configureWebComponents()
    { 
        \Site::push('store', function($store) {
            $store->directory('store');
            $store->pushMiddleware(Http\Middlewares\Authenticate::class);
  
            $store->pushComponent(new Components\Cart); 
            $store->pushComponent(new Components\Product); 
            $store->pushComponent(new Components\Category); 
            $store->pushComponent(new Components\Shipping); 
            $store->pushComponent(new Components\Checkout); 
            $store->pushComponent(new Components\Invoice); 
            $store->pushComponent(new Components\CategoryIndex); 

            $store->pushComponent(new Components\Dashboard\Dashboard); 
            $store->pushComponent(new Components\Dashboard\Login); 
        }); 
    }

    public function registerResources()
    { 
        LaravelNova::resources([
            Nova\Attribute::class,
            Nova\AttributeGroup::class,
            Nova\Brand::class,
            Nova\Carrier::class,
            Nova\Category::class,
            Nova\Combination::class,
            Nova\Feature::class,
            Nova\FeatureValue::class,
            Nova\Order::class,
            Nova\OrderItem::class,
            Nova\Product::class,
        ]);
    }

    public function registerPolicies()
    { 
        Gate::policy(Models\StoreBrand::class, Policies\Policy::class);
        Gate::policy(Models\StoreFeature::class, Policies\Policy::class);
        Gate::policy(Models\StoreProduct::class, Policies\Policy::class);
        Gate::policy(Models\StoreCarrier::class, Policies\Policy::class);
        Gate::policy(Models\StoreAttribute::class, Policies\Policy::class); 
        Gate::policy(Models\StoreCombination::class, Policies\Policy::class); 
        Gate::policy(Models\StoreFeatureValue::class, Policies\Policy::class);
        Gate::policy(Models\StoreAttributeGroup::class, Policies\Policy::class);
    }

    public function registeLocates()
    {    
        \Config::set('module.locatables.store', [
            'title' => 'Store', 
            'name'  => 'store',
            'items' => [Locate::class, 'moduleLocales']
        ]);    

        \Config::set('menu.menuables.store', [
            'title' => 'Product Category',
            'callback' => [Locate::class, 'categoryLocates'],
        ]);   
    } 

    public function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        \Route::middleware(['web'])
            ->namespace('Armincms\Store\Http\Controllers')
            ->group(__DIR__.'/../routes/web.php'); 

        app('routes')->refreshNameLookups();
    } 
}
