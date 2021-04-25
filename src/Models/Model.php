<?php

namespace Armincms\Store\Models;

use Illuminate\Support\Str; 
use Illuminate\Support\Collection; 
use Illuminate\Database\Eloquent\{Model as LaravelModel, Builder}; 
use Armincms\Concerns\{HasMediaTrait, Authorization};
use Armincms\Contracts\Authorizable; 
use Armincms\Targomaan\Concerns\InteractsWithTargomaan;
use Armincms\Targomaan\Contracts\Translatable; 
use Spatie\MediaLibrary\HasMedia\HasMedia;       

class Model extends LaravelModel implements HasMedia, Translatable
{
    use InteractsWithTargomaan, HasMediaTrait; 

    const LOCALE_KEY = 'language'; 

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'json', 
    ];

    protected $medias = [
        // 'image' => [
        //     'disk' => 'armin.image',
        //     'schemas' => [
        //         '*', 'blog', 'blog.list'
        //     ],
        // ]
    ];  

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            // 'slug' => [
            //     'source' => 'title'
            // ]
        ]; 
    }   
}
