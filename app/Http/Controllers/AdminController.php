<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{

    public function dashboard()
    {
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalOrders = Order::count();

        $ordersByCategory = DB::table('categories')
            ->leftJoin('products', 'categories.id', '=', 'products.category_id')
            ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
            ->leftJoin('orders', 'order_items.order_id', '=', 'orders.id')
            ->select('categories.name', DB::raw('COUNT(order_items.id) as total'))
            ->groupBy('categories.name')
            ->orderByDesc('total')
            ->get();

        $monthlyRevenue = Order::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, SUM(total) as revenue")
            ->groupBy('month')
            ->orderBy('month', 'ASC')
            ->get();

        return view('admin.dashboard', compact('totalProducts', 'totalCategories', 'totalOrders', 'ordersByCategory', 'monthlyRevenue'));
    }

    public function orders()
    {
        $orders = Order::with('items.product')->orderBy('orders.id', 'DESC')->paginate(5);
        return view('admin.orders.index', compact('orders'));
    }
}
