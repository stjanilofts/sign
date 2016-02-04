<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOrderRequest;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Order;
use Mail;


class OrdersController extends Controller
{
    public function index()
    {
        $orders = Order::orderBy('created_at', 'DESC')->get();
        return view('admin.orders.table')->with(['items' => $orders]);
    }

    public function show($id)
    {
        $order = Order::find($id);
        return view('admin.orders.show')->with(['order' => $order]);
    }

    public function createOrder(CreateOrderRequest $request)
    {
        $order = \DB::transaction(function () use ($request) {
            $order = Order::create($request->all());

            $order->reference = \Hashids::encode($order->id);

            $order->save();

            foreach(\Cart::content() as $item) {
                $order->addItem($item);
            }

            return $order;
        });

        if($order) return response()->json(['success'=>true, 'reference'=>$order->reference], 200);
        
        return response()->json(['success'=>false]);
    }



	public function handleOrder($reference)
    {
        $order = Order::where('reference', $reference)->first();

        if(! $order) {
            return redirect()->route('home');
        }

        // Millifærsla
        if($order->greidslumati=='milli') {
            $this->confirmOrder($order);
        }

        // Borgað með korti
		if($order->greidslumati=='kort') {
            $this->confirmOrder($order);
		}
    }



    public function confirmOrder(Order $order) {
        // Sendir kvittun ef að það er ekki búið að gera það áður
        $order->confirm();

        return 'sent receipt';
    }















    public function destroy($id)
    {
        Order::find($id)->delete();

        return redirect()->action('OrdersController@index');
    }
}