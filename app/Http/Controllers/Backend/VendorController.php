<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VendorController extends Controller
{
  public function dashboard()
  {
    $todayOrders = Order::whereDate("created_at", Carbon::today())->whereHas("orderProducts", function ($query) {
      $query->where("vendor_id", Auth::user()->vendor->id);
    })->count();
    $totalOrders = Order::whereHas("orderProducts", function ($query) {
      $query->where("vendor_id", Auth::user()->vendor->id);
    })->count();
    $successOrders = Order::whereHas("orderProducts", function ($query) {
      $query->where("vendor_id", Auth::user()->vendor->id);
    })->where("order_status", "delivered")->count();
    $products = Product::where("vendor_id", Auth::user()->vendor->id)->count();
    $reviews = ProductReview::where("vendor_id", Auth::user()->vendor->id)->count();
    $totalEarnings = OrderProduct::join('orders', 'order_products.order_id', '=', 'orders.id')
      ->where('order_products.vendor_id', Auth::user()->vendor->id)
      ->where('orders.order_status', 'delivered')
      ->where("orders.payment_status", 1)
      ->sum(DB::raw('(order_products.unit_price + COALESCE(order_products.variant_total, 0)) * order_products.quantity'));
    $todayEarnings = OrderProduct::join('orders', 'order_products.order_id', '=', 'orders.id')
      ->whereDate('order_products.created_at', Carbon::today())
      ->where('order_products.vendor_id', Auth::user()->vendor->id)
      ->where('orders.order_status', 'delivered')
      ->where("orders.payment_status", 1)
      ->sum(DB::raw('(order_products.unit_price + COALESCE(order_products.variant_total, 0)) * order_products.quantity'));

    return view("vendor.dashboard.dashboard", compact("todayOrders", "totalOrders", "products", "successOrders", "reviews", "totalEarnings", "todayEarnings"));
  }
}
