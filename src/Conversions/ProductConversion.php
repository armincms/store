<?php

namespace Armincms\Store\Conversions;
 
use Armincms\Conversion\Conversion;

class ProductConversion extends Conversion
{ 
    /**
     * Get the registered schemas.
     * 
     * @return array
     */
    public function schemas()
    {
        return array_merge([
            'larg' => [   
                'width'         => 1024,
                'height'        => 578, 
                'upsize'        => false, // cutting type
                'compress'      => 25,
                'extension'     => null, // save extension
                'placeholder'   => image_placeholder(1024, 578),
                'label'         => __('Common larg image'),
                'manipulations' => ['fit' => \Spatie\Image\Manipulations::FIT_MAX], 
            ],
            'mid' => [   
                'width'         => 578,
                'height'        => 320, 
                'upsize'        => false, // cutting type
                'compress'      => 25,
                'extension'     => null, // save extension
                'placeholder'   => image_placeholder(578, 320),
                'label'         => __('Common mid image'),
                'manipulations' => ['fit' => \Spatie\Image\Manipulations::FIT_MAX], 
            ],
            'thumbnail' => [  
                'width'         => 320,
                'height'        => 217, 
                'upsize'        => false, // cutting type
                'compress'      => 25,
                'extension'     => null, // save extension
                'placeholder'   => image_placeholder(320, 217),
                'label'         => __('Common thumbnail image'),
                'manipulations' => ['fit' => \Spatie\Image\Manipulations::FIT_MAX], 
            ], 
        ], parent::schemas());
    }
}
