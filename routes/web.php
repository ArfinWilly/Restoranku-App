<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('menu');
});

Route::get('/menu', [App\Http\Controllers\MenuController::class, 'index'])->name('menu');

Route::get('/cart', [App\Http\Controllers\MenuController::class, 'cart'])->name('cart');
Route::post('/cart/add', [App\Http\Controllers\MenuController::class, 'addCart'])->name('add.cart');
Route::post('/cart/update', [App\Http\Controllers\MenuController::class, 'updateCart'])->name('update.cart');
Route::post('/cart/remove', [App\Http\Controllers\MenuController::class, 'removeCart'])->name('remove.cart');
Route::get('/cart/clear', [App\Http\Controllers\MenuController::class, 'clearCart'])->name('clear.cart');

Route::get('/checkout' , [App\Http\Controllers\MenuController::class, 'checkout'])->name('checkout');
Route::post('/checkout/store' , [App\Http\Controllers\MenuController::class, 'storeOrder'])->name('checkout.store');
Route::get('/checkout/success/{orderId}' , [App\Http\Controllers\MenuController::class, 'checkoutSuccess'])->name('checkout.success');