<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class CartController extends Controller
{
    public function index()
    {
        $cart = \Cart::content();

        return view('frontend.cart.index')->with(['cart' => $cart]);
    }

    public function cartDestroy(Request $request)
    {
        \Cart::destroy();

        return response()->json('success', 200);
    }

    public function cartHasItems()
    {
        $count = (count(\Cart::content()->toArray()));
        return response()->json(['itemCount'=>($count ?: 0)]);
    }

    public function getCartItems(Request $request)
    {
        $items = [];

        $total = 0;

        foreach(\Cart::content() as $rowid => $item) {
            $product = \App\Product::find($item['id']);

            $items[] = [
                'id'        => $item['id'],
                'name'      => $item['name'],
                'options'   => $item['options'],
                'price'     => $product->formatPrice($item['price']),
                'qty'       => $item['qty'],
                'path'      => $product->fullPath(),
                'rowid'     => $item['rowid'],
                'subtotal'  => $product->formatPrice($item['subtotal']),
                'image'     => $product->img()->first()
            ];

            $total += $item['subtotal'];
        }

        //dd($items);

        return response()->json(['items' => $items, 'total' => kalFormatPrice($total)]);
    }

    public function cartModal($product_id)
    {
        $product = \App\Product::find($product_id);

        return view('frontend.cart.modal')->with(compact('product'));
    }

    public function addToCart(Request $request)
    {
        $product_id = $request->get('product_id');

        if( ! $product_id) return response()->json('error', 200);

        $product = \App\Product::find($product_id);

        if( ! $product) return response()->json('error', 200);

        $selected = $request->get('selected');
        $quantity = $request->get('quantity') ?: 1;

        $options = [];
        $modifiers = [];

        if($selected) {
            foreach($selected as $sel) {
                $option_id      = $sel['option_id'];
                $value_id       = $sel['value_id'];
            
                foreach($product->options as $k => $opt) {
                    if($k == $option_id) {
                        foreach($opt['values'] as $l => $val) {
                            if($l == $value_id) {
                                $modifiers[] = (int) $val['modifier'];
                                $options[$opt['text']] = $val['text'];
                            }
                        }
                    }
                }
            }
        }

        $price = $product->price;

        foreach($modifiers as $modifier) {
            $price = $price + ($modifier);
        }
       
        \Cart::add(
            $product->id,
            $product->title,
            $quantity,
            $price,
            $options
        );

        return response()->json(['status' => 'success', 'product' => $product], 200);
    }

    public function updateCart(Request $request)
    {
        $items = $request->get('items');

        $rowids = [];

        foreach($items as $item) {
            $rowids[] = $item['rowid'];
            \Cart::update($item['rowid'], $item['qty']);
        }

        // Fjarlægjum vörur sem ekki lengur eru í körfunni
        foreach(\Cart::content() as $rowid => $item) {
            if(!in_array($rowid, $rowids)) {
                \Cart::remove($item['rowid']);
            }
        }

        return response()->json('success', 200);
    }
}
