<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        // Menghitung total data untuk setiap model
        $totalOrders = Order::count();
        // Menghitung total pendapatan dari semua order
        $totalRevenues = Order::sum('grandTotal');
        // Menghitung total order untuk hari ini
        $totalOrderToday = Order::whereDate('created_at', now()->toDateString())->count();
        // Menghitung total pendapatan untuk hari ini
        $totalRevenueToday = Order::whereDate('created_at', now()->toDateString())->sum('grandTotal');
        // Mengirim data ke view dashboard
        return view('admin.dashboard', compact('totalOrders', 'totalRevenues', 'totalOrderToday', 'totalRevenueToday'));
    }
}
