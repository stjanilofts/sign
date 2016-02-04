<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Mail;

class Order extends Model
{
	protected $table = 'orders';

    protected $fillable = [
        'nafn',
        'netfang',
        'simi',
        'heimilisfang',
        'pnr',
        'stadur',
        'confirmed',
        'greidslumati',
        'afhendingarmati',
        'athugasemd',
        'reference',
    ];

    public function confirm()
    {
        if (! $this->confirmed) {
            $this->confirmed = 1;
            
            $order = $this;

            Mail::send('emails.receipt', ['order' => $order, 'items' => $order->getItems()], function ($m) use ($order) {
                $rcpt = [
                    $order->netfang => $order->nafn,
                ];
                $m->to($rcpt)->subject(config('formable.site_title'));
            });

            $this->save();
        }
    }

    public function unconfirm()
    {
        $this->confirmed = 0;
        $this->save();
    }

    public function products()
    {
        return \DB::table('order_product')->where('order_id', $this->id)->get();
    }

    public function getItems()
    {
        $items = \DB::table('order_product')
            ->where('order_id', $this->id)->get();

        return $items;
    }

    public function total()
    {
        $total = 0;

        foreach($this->getItems() as $item) {
            $total += $item->subtotal;
        }

        return $total;
    }

    public function addItem($item)
    {
        $product = \App\Product::find($item['id']);

        $qty        = $item['qty'];
        $vnr        = $product->vnr;
        $product_id = $product->id;        
        $title      = $product->title;
        $price      = $item['price'];
        $subtotal   = $item['subtotal'];
        $order_id   = $this->id;

        \DB::table('order_product')
            ->insert([
                'qty'           => $qty,
                'vnr'           => $vnr,
                'product_id'    => $product_id,
                'title'         => $title,
                'price'         => $price,
                'subtotal'      => $subtotal,
                'options'       => json_encode($item['options']),
                'order_id'      => $order_id,
            ]);
    }


    public function getShippingOption()
    {
        $shipping_options = config('formable.shipping_options');

        return array_key_exists($this->afhendingarmati, $shipping_options) ? $shipping_options[$this->afhendingarmati] : '';
    }

    public function getPaymentOption()
    {
        $payment_options = config('formable.payment_options');

        return array_key_exists($this->greidslumati, $payment_options) ? $payment_options[$this->greidslumati] : '';
    }
}
 