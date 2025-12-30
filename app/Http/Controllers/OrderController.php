<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::all()->sortByDesc('created_at');
        return view('admin.order.index', compact('orders'));
    }

    public function show(string $id)
    {
        $order = Order::findOrFail($id);
        $orderItems = OrderItem::where('order_id', $order->id)->get();
        return view('admin.order.show', compact('order', 'orderItems'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        // if ($order->status != 'pending' || $order->payment_method != 'tunai') {
        //     return redirect()->route('orders.index')->with('error', 'Order cannot be settled.');
        // }

        if (Auth::user()->role->role_name == 'admin' || Auth::user()->role->role_name == 'cashier') {
            $order->status = 'settlement';
            $order->save();

            return redirect()->route('orders.index')->with('success', 'Order settles successfully.');
        } else {
            $order->status = 'cooked';
        }
        $order->save();

        return redirect()->route('orders.index')->with('success', 'Order cooked successfully.');
    }
}
