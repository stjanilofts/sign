<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ProductController extends ItemableController
{
    public $model = 'Product';

    public function create($id = 0, $extra = array())
    {
        $flokkar = \App\Category::lists('title', 'id')->toArray();
        $flokkar[0] = ' - Enginn flokkur - ';

        $extra['flokkar'] = $flokkar;
        $extra['selectedFlokkurId'] = 0;

        if( ! $extra['selectedFlokkurId']) {
            $extra['selectedFlokkurId'] = \Request::has('parent_id') ? \Request::get('parent_id') : 0;
        }

        //dd($extra);

        return parent::create($id, $extra);
    }

    public function edit($id, $extra = array())
    {
    	$vara = \App\Product::find($id);

        $flokkar = \App\Category::lists('title', 'id')->toArray();
        $flokkar[0] = ' - Enginn flokkur - ';

        $extra['flokkar'] = $flokkar;
        $extra['selectedFlokkurId'] = $vara->{$vara->parent_key} ?: 0;

        if(!$extra['selectedFlokkurId']) {
            $extra['selectedFlokkurId'] = \Request::has('parent_id') ? \Request::get('parent_id') : 0;
        }

    	return parent::edit($id, $extra);
    }

    public function saveOptions($id, Request $request)
    {
        $product = \App\Product::find($id);

        $options = $request->get('options');

        $product->options()->update($options);

        /*$product->update([
            'options' => $options
        ]);*/

        return $product->options()->all();
    }
}