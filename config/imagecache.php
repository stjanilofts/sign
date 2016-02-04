<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Name of route
    |--------------------------------------------------------------------------
    |
    | Enter the routes name to enable dynamic imagecache manipulation.
    | This handle will define the first part of the URI:
    | 
    | {route}/{template}/{filename}
    | 
    | Examples: "images", "img/cache"
    |
    */
   
    'route' => 'imagecache',

    /*
    |--------------------------------------------------------------------------
    | Storage paths
    |--------------------------------------------------------------------------
    |
    | The following paths will be searched for the image filename, submited 
    | by URI. 
    | 
    | Define as many directories as you like.
    |
    */
    
    'paths' => array(
        public_path('resized'),
        public_path('prods'),
        public_path('tmp'),
        public_path('uploads'),
        public_path('img'),
    ),

    /*
    |--------------------------------------------------------------------------
    | Manipulation templates
    |--------------------------------------------------------------------------
    |
    | Here you may specify your own manipulation filter templates.
    | The keys of this array will define which templates 
    | are available in the URI:
    |
    | {route}/{template}/{filename}
    |
    | The values of this array will define which filter class
    | will be applied, by its fully qualified name.
    |
    */
   
    'templates' => array(
        'card' => 'App\Filters\cardFilter',
        'header' => 'App\Filters\headerFilter',
        'boximg' => 'App\Filters\boximgFilter',
        'banner' => 'App\Filters\bannerFilter',
        'frontpagebanner' => 'App\Filters\frontpagebannerFilter',
        'productbg' => 'App\Filters\productbgFilter',
        'titlebanner' => 'App\Filters\titlebannerFilter',
        'kubbur' => 'App\Filters\kubburFilter',
        'facebook' => 'App\Filters\facebookFilter',
        'article' => 'App\Filters\articleFilter',
        'logo' => 'App\Filters\logoFilter',
        'menuitem' => 'App\Filters\menuitemFilter',
        'boximage' => 'App\Filters\boximageFilter',
        'product' => 'App\Filters\productFilter',
        'productimage' => 'App\Filters\productimageFilter',
        'productlist' => 'App\Filters\productlistFilter',
        'tiny' => 'App\Filters\tinyFilter',
        'slick' => 'App\Filters\slickFilter',
        'slideset' => 'App\Filters\slidesetFilter',
        's' => 'App\Filters\sFilter',
        'm' => 'App\Filters\mFilter',
        'l' => 'App\Filters\lFilter',
        'small' => 'Intervention\Image\Templates\Small',
        'medium' => 'Intervention\Image\Templates\Medium',
        'large' => 'Intervention\Image\Templates\Large',
    ),

    /*
    |--------------------------------------------------------------------------
    | Image Cache Lifetime
    |--------------------------------------------------------------------------
    |
    | Lifetime in minutes of the images handled by the imagecache route.
    |
    */
   
    'lifetime' => 43200,

);
