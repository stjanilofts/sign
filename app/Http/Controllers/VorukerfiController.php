<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class VorukerfiController extends Controller
{
    public $crumbs = [];

    public function index()
    {
        $cats   = \App\Category::where('status', 1)->where('parent_id', 0)->orderBy('order')->get();
        $prods  = \App\Product::where('status', 1)->orderBy('order')->get();

        if($cats) {
            $data['items'] = $cats->merge($prods);
        } else {
            $data['items'] = $prods;
        }

        $data['pagetitle'] = 'Vörur';

        return view('frontend.products')->with($data);
    }

    public function allarvorur()
    {
        //$cats   = \App\Category::where('status', 1)->orderBy('category_id')->get();
        $prods  = \App\Product::where('status', 1)->orderBy('category_id')->orderBy('order')->get();

        $data['items'] = $prods;

        $data['pagetitle'] = 'Allar vörur';

        return view('frontend.products')->with($data);
    }

    // Sýnir annaðhvort vöru eða flokk
    public function show($slug)
    {
        // Tökum bara síðasta stykkið
        $e = array_filter(explode("/", $slug));
        $last = end($e);

        $item = \App\Category::where('status', 1)->where('slug', $last)->first();

        if($item) {
            $cats   = \App\Category::where('status', 1)->where('parent_id', $item->id)->orderBy('order')->get();
            $prods  = \App\Product::where('status', 1)->where('category_id', $item->id)->orderBy('order')->get();

            $data['items'] = $cats->merge($prods);
            $data['pagetitle'] = $item->title;

            return view('frontend.products')->with($data);
        }

        $item = \App\Product::where('status', 1)->where('slug', $last)->first();

        if(!$item) {
            if (!$item) abort(404, 'Fann ekki síðu!');
        }

        $data['item'] = $item;
        $data['images'] = $item->img()->all();
        $data['colors'] = $item->skirt()->all();
        $data['image'] = is_array($data['images']) && array_key_exists(0, $data['images']) ? $data['images'][0] : ['name' => $item->img()->first(), 'title' => ''];
        $data['siblings'] = $item->getSiblings();

        $data['pagetitle'] = isset($item->category->title) ? $item->category->title : 'Vörur';

        if($item->category_id > 1) {
            $data['pagetitle'] = $item->title;
        }

        return view('frontend.product')->with($data);
    }
}
