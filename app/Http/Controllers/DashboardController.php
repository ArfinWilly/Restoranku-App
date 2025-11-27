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
        // Menghitung total order untuk hari ini
        $totalOrderToday = Order::whereDate('created_at', now()->toDateString())->count();
        // Menghitung total menu
        $totalMenus = Item::count();
        // Menghitung total karyawan
        $totalEmployees = User::count();

        // Mengirim data ke view dashboard
        return view('admin.dashboard', compact('totalOrders', 'totalOrderToday', 'totalMenus', 'totalEmployees'));
    }
}
