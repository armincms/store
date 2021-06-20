<?php 

namespace Armincms\Store;

use Illuminate\Support\Str; 

class Locate
{ 
    public static function moduleLocales()
    {
        return collect([
                Nova\Product::class
            ])->map(function($resource) {
                return [
                    'title' => $resource::label(),
                    'name'  => Str::singular($resource::uriKey()),
                    'id'    => '*',
                    'childrens' => $resource::newModel()->get()->mapInto($resource)->map(function($resource) { 
                        return [
                            'title' => $resource->title() ?? $resource->getKey(), 
                            'name'  => Str::singular($resource::uriKey()),
                            'id'    => $resource->getKey(),
                            'url'   => $resource->url(),
                        ];
                    })->toArray(),
                ];
            })
            ->push(static::serializeCategoryIndex())
            ->push([
                'title' => __('Product Category'),
                'name'  => 'product-category',
                'id'    => '*',
                'childrens' => Nova\Category::newModel()
                                    ->whereDoesntHave('parent')->with('subCategories')->get()
                                    ->mapInto(Nova\Category::class)
                                    ->map([static::class, 'categoryInformation'])
                                    ->toArray()
            ])->values()->toArray();
    }

    public static function categoryInformation($category)
    {
        $childrens = $category->subCategories->mapInto(Nova\Category::class)->map([
            static::class, 'categoryInformation'
        ]);

        return array_filter([
            'title' => $category->title() ?? $category->getKey(), 
            'name'  => Str::kebab(class_basename($category::$model)),
            'id'    => $category->getKey(),
            'childrens' => $childrens->isEmpty() ? null : $childrens->all(),
            'url'   => $category->url(),
        ]);
    }

    public static function categoryLocates()
    {
        return Nova\Category::newModel() 
                    ->whereDoesntHave('parent')
                    ->with([
                        'subCategories' => function($query) {
                            $query->published();
                        }
                    ])
                    ->get()
                    ->mapInto(Nova\Category::class)
                    ->map([static::class, 'menuInfotmation'])
                    ->prepend(static::serializeCategoryIndex())
                    ->toArray();
    } 

    public static function menuInfotmation($category)
    {
        return [
            'id'    => $category->id,
            'title' => $category->title(), 
            'active'=> $category->isPublished(),
            'url'   => $category->url(),
            'childs'=> $category->subCategories->mapInto(Nova\Category::class)->map([
                static::class, 'menuInfotmation'
            ])->toArray()
        ];

    }

    public static function serializeCategoryIndex()
    {
        return [
            'id' => 'index',
            'title' => __('Category Index'),
            'active' => 1,
            'url' => \Site::findByComponent(new Components\CategoryIndex)->url(''),
        ];
    }
}
