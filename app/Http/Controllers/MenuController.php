<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $tableNumber = $request->query('table');

        if ($tableNumber) {
            Session::put('table_number', $tableNumber);
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

}
