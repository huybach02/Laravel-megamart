<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ChildCategory;
use App\Models\Coupon;
use App\Models\FlashSaleItem;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\SubCategory;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
  public function dashboard()
  {
    $categories = Category::count();
    $subCategories = SubCategory::count();
    $childCategories = ChildCategory::count();
    $brands = Brand::count();
    $products = Product::count();
    $productsAdmin = Product::where("vendor_id", Auth::user()->vendor->id)->count();
    $productsVendor = Product::where("vendor_id", "!=", Auth::user()->vendor->id)->count();
    $productsPending = Product::where("is_approved", 0)->count();
    $flashSales = FlashSaleItem::count();
    $coupons = Coupon::count();
    $orders = Order::count();
    $pending_orders = Order::where("order_status", "pending")->count();
    $processed_and_ready_to_ship_orders = Order::where("order_status", "processed_and_ready_to_ship")->count();
    $dropped_off_orders = Order::where("order_status", "dropped_off")->count();
    $shipped_orders = Order::where("order_status", "shipped")->count();
    $out_for_delivery_orders = Order::where("order_status", "out_for_delivery")->count();
    $delivered_orders = Order::where("order_status", "delivered")->count();
    $cancelled_orders = Order::where("order_status", "cancelled")->count();
    $refunded_orders = Order::where("order_status", "refunded")->count();
    $users = User::count();
    $customers = User::where("role", "user")->count();
    $vendors = User::where("role", "vendor")->count();
    $vendorsPending = Vendor::where("status", 0)->count();
    $reviews = ProductReview::count();
    $blogs = Blog::count();
    $subTotals = Order::where("payment_status", 1)->where("order_status", "delivered")->sum("sub_total");
    $amount = Order::where("payment_status", 1)->where("order_status", "delivered")->sum("amount");
    $amountSale = $subTotals - $amount;

    return view("admin.dashboard", compact(
      "categories",
      "subCategories",
      "childCategories",
      "brands",
      "products",
      "productsAdmin",
      "productsVendor",
      "productsPending",
      "flashSales",
      "coupons",
      "orders",
      "pending_orders",
      "processed_and_ready_to_ship_orders",
      "dropped_off_orders",
      "shipped_orders",
      "out_for_delivery_orders",
      "delivered_orders",
      "cancelled_orders",
      "refunded_orders",
      "users",
      "customers",
      "vendors",
      "vendorsPending",
      "reviews",
      "blogs",
      "subTotals",
      "amount",
      "amountSale"
    ));
  }

  public function login()
  {
    return view("admin.auth.login");
  }
}
