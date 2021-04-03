<?php

namespace Armincms\Store\Models;

use Armincms\Models\Translation;
use Core\HttpSite\Concerns\HasPermalink; 

class StoreProductTranslation extends Translation  
{ 	   
	use HasPermalink;

    protected static $sluggable = 'name';

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    public static $autoLink = false;
}
