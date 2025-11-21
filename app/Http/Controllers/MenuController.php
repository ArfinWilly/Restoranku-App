<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Midtrans\Snap;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $tableNumber = $request->query('meja');

        if ($tableNumber) {
            Session::put('tableNumber', $tableNumber);
        }

        $items = Item::where('is_active' , 1)->orderBy('name' , 'asc')->get() ;

        // Logic to display the menu to customers
        return view('customer.menu' , compact('items' , 'tableNumber'));
    }

    public function cart()
    {
        $cart = Session::get('cart');
        // Logic to display the cart to customers
        return view('customer.cart' , compact('cart'));
    }

    public function addCart(Request $request)
    {
        $menuId = $request->input('id');
        $menu = Item::find($menuId);

        if (!$menu) {
            return response()->json([
                'status' => 'error' ,
                'message' => 'Menu not found'
            ]) ;
        } 

        $cart = Session::get('cart') ;

        if (isset($cart[$menuId])) {
            $cart[$menuId]['qty'] += 1 ;
        } else {
            $cart[$menuId] = [
                'id' => $menu->id ,
                'name' => $menu->name ,
                'price' => $menu->price ,
                'image' => $menu->img ,
                'qty' => 1
            ] ;
        }

        Session::put('cart' , $cart) ;

        return response()->json([
            'status' => 'success' ,
            'message' => 'Menu added to cart successfully' ,
            'cart' => $cart
        ]) ;
    }

    public function updateCart(Request $request)
    {
        $itemId = $request->input('id');
        $newQty = $request->input('qty');

       if ($newQty <= 0) {
           return response()->json([
               'success' => false 
           ]) ;
       }

       $cart = Session::get('cart') ;
       if (isset($cart[$itemId])) {
           $cart[$itemId]['qty'] = $newQty ;
           Session::put('cart' , $cart) ;

           Session::flash('success' , 'Cart updated successfully') ;

           return response()->json(['success' => true]) ;
       }

       return response()->json(['success' => false]) ;
    }

    public function removeCart(Request $request)
    {
        $itemId = $request->input('id');

        $cart = Session::get('cart') ;
        if (isset($cart[$itemId])) {
            unset($cart[$itemId]) ;
            Session::put('cart' , $cart) ;

            Session::flash('success' , 'Item removed from cart successfully') ;

            return response()->json(['success' => true]) ;
        }

        return response()->json(['success' => false]) ;
    }

    public function clearCart()
    {
        Session::forget('cart') ;

        return redirect()->route('cart')->with('success' , 'Cart cleared successfully') ;
    }


    public function checkout()
    {
        $cart = Session::get('cart');

        if( empty($cart) ) {
            return redirect()->route('cart')->with('error', 'Your cart is empty. Please add items to proceed to checkout.');
        }

        $tableNumber = Session::get('tableNumber');
        // Logic to display the checkout page to customers
        return view('customer.checkout' , compact('cart' , 'tableNumber'));
    }

    public function storeOrder(Request $request)
    {
        // You can access the cart items from the session
        $cart = Session::get('cart');
        $tableNumber = Session::get('tableNumber');

        if( empty($cart) ) {
            return redirect()->route('cart')->with('error', 'Your cart is empty. Please add items to place an order.');
        }

        $validator = Validator::make($request->all(), [
            'fullname' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->route('checkout')->withErrors($validator);
        }

        $total = 0 ;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['qty'];
        }

        $totalAmount = 0;
        foreach ($cart as $item) {
            $itemTotal = $item['price'] * $item['qty'];
            $totalAmount += $itemTotal;

            $itemDetails[] = [
                'id' => $item['id'],
                'name' => substr($item['name'] ?? '', 0, 50),
                'price' => (int) ($item['price'] + ($item['price'] * 0.1)), // Including 10% tax
                'quantity' => (int) ($item['qty'] ?? 0)
            ];
        }

        $user = User::firstOrCreate([
            'fullname' => $request->input('fullname') ,
            'phone' => $request->input('phone') ,
            'role_id' => 4
        ]);

        $order = Order::create([
            'order_code' => 'ORD' . $tableNumber . '-' . time() ,
            'user_id' => $user->id,
            'subtotal' => $totalAmount,
            'tax' => (int) ($totalAmount * 0.1),
            'grandTotal' => (int) ($totalAmount + ($totalAmount * 0.1)), // Including 10% tax
            'status' => 'pending',
            'table_number' => $tableNumber,
            'payment_method' => $request->input('payment_method'),
            'notes' => $request->input('notes')
        ]) ;

        foreach ($cart as $itemId => $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'item_id' => $item['id'],
                'quantity' => $item['qty'] ,
                'price' => $item['price'] * $item['qty'] ,
                'tax' => $item['qty'] * $item['price'] * 0.1 ,
                'totalPrice' => ($item['price'] * $item['qty']) + ($item['price'] * 0.1 * $item['qty'])
            ]) ;
        }

        // After storing the order, you might want to clear the cart
        Session::forget('cart');

        if ($request->payment_method == 'tunai') {
            return redirect()->route('checkout.success' , ['orderId' => $order->order_code])->with('success', 'Order placed successfully!');
        } else {
            // Logic to handle QRIS payment can be added here
            // For now, we will just redirect to success page
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = config('midtrans.is_production');
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            $params = [
                'transaction_details' => [
                    'order_id' => $order->order_code,
                    'gross_amount' => (int) $order->grandTotal,
                ],
                'item_details' => $itemDetails,
                'customer_details' => [
                    'first_name' => $user->fullname ?? 'Guest',
                    'phone' => $user->phone,
                ],
                'payment_type' => 'qris'
            ];

            try {
                $snapToken = Snap::getSnapToken($params);

                return response()->json([
                    'status' => 'success',
                    'snap_token' => $snapToken,
                    'order_code' => $order->order_code
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal membuat pesanan. Silahkan coba lagi.'
                ], 500);
            }
        }
    }

    public function checkoutSuccess($orderId)
    {
        $order = Order::where('order_code' , $orderId)->first();

        if (!$order) {
            return redirect()->route('menu')->with('error', 'Order not found.');
        }

        $orderItems = OrderItem::where('order_id' , $order->id)->get();
        if ($order->payment_method == 'qris') {
            $order -> status = 'settlement' ;
            $order -> save() ;
        }
        // Logic to display the order success page to customers
        return view('customer.success' , compact('order' , 'orderItems'));
    }
}
