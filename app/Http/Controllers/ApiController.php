<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Cache;
use App\Page;
use App\Product;
use App\Category;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    public function page($slug)
    {
        $parts = array_values(array_filter(explode("/", $slug)));

        $first = false;
        $siblings = false;
        $submenu = [];

        foreach($parts as $idx => $part) {
            $page = Page::where('slug', $part)->where('status', 1)->first() ?: false;

            if($idx == 0) {
                $first = $page;
                $siblings = Page::where('parent_id', $page->id)->where('status', 1)->orderBy('order')->get();
            }

            if($page && $idx == (count($parts) - 1)) {
                if($siblings) {
                    /*$submenu[$first->id] = [
                        'title' => $first->title,
                        'path' => ltrim(rtrim($first->path, '/'), '/'),
                        'slug' => $first->slug,
                        'order' => $first->order,
                    ];*/

                    foreach($siblings as $sibling) {
                        $submenu[$sibling->id] = [
                            'title' => $sibling->title,
                            //'path' => ltrim(rtrim($sibling->path, '/'), '/'),
                            'path' => rtrim($sibling->path, '/'),
                            'slug' => $sibling->slug,
                            'order' => $sibling->order,
                        ];
                    }
                }

                // Spes til að ná í vörur tengdar þessari content síðu...
                $map = [
                    'kaminur' => 'kaminur',
                    'heitir-pottar' => 'pottar',
                    'lok-a-heita-potta' => 'lok-a-heita-potta',
                ];

                if(array_key_exists($page->slug, $map)) {
                    $page->products = Category::where('slug', $map[$page->slug])->first()->products;
                    if($page->products) {
                        foreach($page->products as &$product) {
                            $product->cmscontent = cmsContent($product);
                            $product->short = shortenClean($product->content, 140);
                            $product->path = 'vorur/'.$map[$page->slug].'/'.$product->slug;
                            $product->image = $product->img()->first();
                        }
                    }
                }

                if(!empty($submenu)) {
                    if(count($submenu) > 1) {
                        $page->submenu = $submenu;
                    }
                }

                $page->cmscontent = cmsContent($page);

                return $page;
            }
        }
    }

    public function menu()
    {
        //return Page::where('parent_id', 0)->where('status', 1)->get();

        function rec($parent_id = 0) {
            $items = Page::where('parent_id', $parent_id)->where('status', 1)->get();

            $menu = [];

            if($items) {
                foreach($items as $item) {
                    $menu[$item->id] = [
                        'title' => $item->title,
                        'path' => rtrim($item->path, '/'),
                        'slug' => $item->slug,
                        'order' => $item->order,
                    ];

                    if($item->hasSubs()) {
                        $menu[$item->id]['subs'] = rec($item->id);
                    }
                }
            }

            return $menu;
        }

        //if (Cache::has('menu')) return Cache::get('menu');

        //return Cache::remember('menu', 120, function() {
            return rec();
        //});
    }

    public function pottar()
    {
        $category = \App\Category::where('slug', 'pottar')->first();
        $pottar = $category->products;

        foreach($pottar as &$product) {
            $product->image = $product->img()->first();
            $product->content = shortenClean($product->content, 140);
            $product->path = 'vorur/'.$category->slug.'/'.$product->slug;
        }

        return $pottar;
    }

    public function banner()
    {
        $banners = Page::whereSlug('_forsidumyndir')->first()->getSubs();

        foreach($banners as &$banner) {
            $banner->image = $banner->img()->first();
        }

        return $banners;
    }

    public function product($slug)
    {
        //$parts = array_values(array_filter(explode("/", $slug)));
        $parts = array_values(array_filter(explode("/", $slug)));
        $last = $parts[count($parts) - 1];

        return Product::whereSlug($last)->first();
    }

    public function cards()
    {
        // Hérna eru boxin á forsíðu valin

    	$cards = [];

        // Um SFH
        $card1 = Page::whereSlug('um-sfh')->first() ?: false;
        if($card1) {
            $cards[] = [
            	'title' => $card1->title,
            	'content' => shortenClean($card1->content),
            	'path' => rtrim($card1->path, '/'),
            	'icon' => 'fa-question',
            ];
        }

        // Aðilar að SFH
        $card2 = Page::whereSlug('adilar-ad-sfh')->first() ?: false;
        if($card2) {
            $cards[] = [
                'title' => $card2->title,
                'content' => shortenClean($card2->content),
                'path' => rtrim($card2->path, '/'),
                'icon' => 'fa-users',
            ];
        }

        // Hafa samband
        $card3 = Page::whereSlug('gjaldskrar')->first() ?: false;
        if($card3) {
            $cards[] = [
                'title' => $card3->title,
                'content' => shortenClean($card3->content),
                'path' => rtrim($card3->path, '/'),
                'icon' => 'fa-money',
            ];
        }

        // Hafa samband
        $card4 = Page::whereSlug('hafa-samband')->first() ?: false;
        if($card4) {
            $cards[] = [
                'title' => $card4->title,
                'content' => shortenClean($card4->content),
                'path' => rtrim($card4->path, '/'),
                'icon' => 'fa-envelope',
            ];
        }

        return $cards;
	}
}
