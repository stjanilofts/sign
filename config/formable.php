<?php

return [

    'site_title' => 'Dún & fiður',

    'site_description' => 'Dún & fiður',

    'company_name' => 'Dún & fiður',
    'company_email' => 'test@netvistun.is',

    'email' => 'test@netvistun.is',
    
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