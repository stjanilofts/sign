<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function home() {
        $data['forsidumyndir'] = \App\Page::where('slug', '_forsidumyndir')->first()->getSubs();

        $cats   = \App\Category::where('status', 1)->get();
        $prods  = \App\Product::where('status', 1)->get();

        $items = [$cats, $prods];

        $kubbar = [];

        foreach($items as $item) {
            foreach($item as $v) {
                $frontpaged = trim($v->extras()->get('frontpaged')) ?: 0;
                $size = trim($v->extras()->get('size'));
                $titill = trim($v->extras()->get('titill')) ?: $v->title;
            
                if($frontpaged && $frontpaged != 0) {
                    $kubbar[] = [
                        'title' => $titill,
                        'size' => $size ? $size : 1,
                        'frontpaged' => $frontpaged,
                        'fillimage' => $v->fillimage ? true : false,
                        'path' => $v->fullPath(),
                        'image' => $v->img()->first(),
                        'slug' => $v->slug,
                    ];
                }
            }
        }

        usort($kubbar, function($a, $b) {
            if($a['frontpaged'] == $b['frontpaged']) return 0;

            return $a['frontpaged'] < $b['frontpaged'] ? -1 : 1;
        });

        $data['kubbar'] = $kubbar;

        return view('frontend.layout')->with($data);
    }
}
