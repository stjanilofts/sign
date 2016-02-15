<?php

Route::get('vefverslun', 'VorukerfiController@index');
Route::get('vefverslun/allar-vorur', 'VorukerfiController@allarvorur');
Route::get('vefverslun/vorulina/{vorulina}', 'VorukerfiController@vorulina');
Route::get('vefverslun/{slug?}', 'VorukerfiController@show')->where(['slug' => '.*']);

Route::get('/', 'HomeController@home');

/*Route::get('make', function() {
    \App\User::create([
        'name' => 'Hótelrekstur',
        'email' => 'hotelrekstur@hotelrekstur.is',
        'password' => bcrypt('ovm74n2bd'),
        'remember_token' => str_random(10),
    ]);
});*/

/*Route::get('vorur2', function() {
    return 'what';
});*/

Route::post('hafa-samband', 'ContactController@postContact');

Route::group(['middleware'=>'api', 'prefix' => 'api'], function () {
    Route::get('page/{slug?}', 'ApiController@page')->where(['slug' => '.*']);
    Route::get('product/{slug?}', 'ApiController@product')->where(['slug' => '.*']);
    Route::get('menu', 'ApiController@menu');
    Route::get('pottar', 'ApiController@pottar');
    Route::get('banner', 'ApiController@banner');
});

Route::group(['middleware'=>'auth', 'prefix' => 'admin'], function () {
	Route::get('/', function() {
		return view('admin.layout');
	});

    // Reorder formable items
    Route::post('formable/_reorder', 'FormableController@reorder');

    // Reorder images
    Route::post('formable/_reorderImages', 'FormableController@reorderImages');
    
    // Reorder files
    Route::post('formable/_reorderFiles', 'FormableController@reorderFiles');
    
    // Get uploaded image
    Route::post('formable/_uploadImage', 'FormableController@uploadImage');

    // Delete image
    Route::post('formable/_deleteImage', 'FormableController@deleteImage');
    
    // Get uploaded file
    Route::post('formable/_uploadFile', 'FormableController@uploadFile');
    
    // Get images from this item
    Route::post('formable/_images', 'FormableController@images');
    
    // Get files from this item
    Route::post('formable/_files', 'FormableController@files');
    
    // Toggle formable status
    Route::post('formable/_toggle', 'FormableController@toggle');

	// Formable stjórnhlutir....
	foreach(config('formable.hlutir') as $hlutur) {
		Route::resource(strtolower($hlutur), ucfirst($hlutur).'Controller');
		Route::get(strtolower($hlutur).'/subs/{id}', ucfirst($hlutur).'Controller@subsIndex');
        Route::get(strtolower($hlutur).'/prods/{id}', ucfirst($hlutur).'Controller@prodsIndex');
	}

    Route::resource('orders', 'OrdersController');
});

// Sýnir það sem er í körfunni
Route::get('karfa', 'CartController@index');

// Sýnir eyðublað til að skrá pöntun
Route::get('checkout', 'CheckoutController@index');

// Eftir að pöntun hefur verið gerð, er notandi færður hingað.
// Hér er ákveðið hvort eigi að fara á einhverja greiðslusíðu eða bara birta kvittun.
Route::get('order/{reference}', 'OrdersController@handleOrder');

Route::get('takk-fyrir', function() {
    return 'takk fyrir pöntunina';
});

Route::group(['prefix' => '_order'], function () {
    // Býr til pöntun
    Route::post('create-order', 'OrdersController@createOrder');
});

Route::group(['prefix' => '_vorur'], function () {
    // Gáir hvort að karfan innihaldi eitthvað af dóti
    Route::get('cart-has-items', 'CartController@cartHasItems');

    // Sýnir "Added to cart" view
    Route::get('cart-modal/{product_id}', 'CartController@cartModal');

    // Eyðir öllu úr körfunni
    Route::get('cart-destroy', 'CartController@cartDestroy');

    // Bætir við hlut í körfu
    Route::post('update-cart', 'CartController@updateCart');

    // Bætir við hlut í körfu
    Route::post('add-to-cart', 'CartController@addToCart');

    // Sækir það sem er í körfunni
    Route::get('get-cart-items', 'CartController@getCartItems');
});

Route::group(['middleware'=>'auth', 'prefix' => '_product'], function () {
    Route::post('{id}/save-options', 'ProductController@saveOptions');

    Route::get('{id}', function($id) {
        $product = \App\Product::find($id);
        return $product->options()->all();
    });
});

// Authentication routes...
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

Route::get('veftre', function() {
    return view('frontend.sitemap');
});

// Grípur allt sem ekki hefur verið fundið hér fyrir ofan
Route::get('{slug?}', ['as' => 'page', 'uses' => 'PageController@show'])->where(['slug' => '.*']);