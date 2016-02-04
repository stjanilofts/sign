<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Product;

class MatsedillController extends Controller
{
    public function show($slug)
    {
        $item = Product::where('slug', $slug)->first();

        $data['item'] = $item;

        $prev = Product::where('id', '<', $item->id)->where('status', 1)->max('id');
    	$next = Product::where('id', '>', $item->id)->where('status', 1)->min('id');

    	if(!$next) {
    		$next = Product::where('status', 1)->min('id');
    	}
    	
    	if(!$prev) {
    		$prev = Product::where('status', 1)->max('id');
    	}

    	$data['prev'] = Product::find($prev);
    	$data['next'] = Product::find($next);

        return view('frontend.menuitem')->with($data);
    }
}
