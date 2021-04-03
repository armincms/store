<?php

namespace Armincms\Store\Conversions;
 
use Armincms\Conversion\Conversion;

class LogoConversion extends Conversion
{ 
    /**
     * Get the registered schemas.
     * 
     * @return array
     */
    public function schemas()
    {
        return array_merge([
            'icon' => [   
                'width'         => 16,
                'height'        => 16, 
                'upsize'        => false, // cutting type
                'compress'      => 25,
                'extension'     => null, // save extension
                'placeholder'   => image_placeholder(16, 16),
                'label'         => __('Common icon image'),
                'manipulations' => ['fit' => \Spatie\Image\Manipulations::FIT_FILL], 
            ],
            'logo' => [   
                'width'         => 64,
                'height'        => 64, 
                'upsize'        => false, // cutting type
                'compress'      => 25,
                'extension'     => null, // save extension
                'placeholder'   => image_placeholder(64, 64),
                'label'         => __('Common logo image'),
                'manipulations' => ['fit' => \Spatie\Image\Manipulations::FIT_FILL], 
            ], 
            'thumbnail' => [   
                'width'         => 128,
                'height'        => 128, 
                'upsize'        => false, // cutting type
                'compress'      => 25,
                'extension'     => null, // save extension
                'placeholder'   => image_placeholder(128, 128),
                'label'         => __('Common thumbnail image'),
                'manipulations' => ['fit' => \Spatie\Image\Manipulations::FIT_FILL], 
            ], 
        ], parent::schemas());
    }
}
