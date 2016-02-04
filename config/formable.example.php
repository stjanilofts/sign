<?php

return [

    'site_title' => 'Kleifarás Dreifing',

    'site_description' => 'Kleifarás Dreifing',

    'company_name' => 'Kleifarás Dreifing',
    'company_email' => 'test@test.is',

    'email' => 'test@test.is',
    
    /*
    |--------------------------------------------------------------------------
    | Formable hlutir (models)
    |--------------------------------------------------------------------------
    |
    | Hvaða hlutir eru í notkun?
    |
    */
    'hlutir' => [
        'Page',
        //'News',
        'Category',
        'Product',
    ],

    /*
    |--------------------------------------------------------------------------
    | Tungumál í boði
    |--------------------------------------------------------------------------
    |
    | Hvaða tungumál eru í boði? Fyrsti er sem kemur alltaf fremst allsstaðar, og er hann sá sem er stilltur sem default í kerfinu.
    |
    */
    'locales' => ['is'],

    'flagicons' => [
        'is' => 'flagicons/Iceland.png'
    ],



    'shipping_options' => [
        'sott' => 'Sótt á staðinn',
        'sent' => 'Sent á pósthús',
    ],


    'payment_options' => [
        'milli' => 'Greitt með millifærslu',
        'kort' => 'Greitt með greiðslukorti',
    ],


];  