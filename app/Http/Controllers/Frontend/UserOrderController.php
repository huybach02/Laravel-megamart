<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderProduct;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserOrderController extends Controller
{
  public function index()
  {
    $orders = Order::where("user_id", Auth::user()->id)->latest()->get();

    return view("frontend.dashboard.order.index", compact("orders"));
  }

  public function show($id)
  {
    $order = Order::findOrFail($id);

    return view("frontend.dashboard.order.show", compact("order"));
  }

  public function cancelOrder(Request $request)
  {
    $request->validate([
      "cancel_reason" => ["required"],
    ]);

    $order = Order::findOrFail($request->order_id);

    if ($order->order_status == "pending") {
      $order->order_status = "cancelled";
      $order->cancel_reason = $request->cancel_reason;
      $order->refund_status = "Chưa hoàn tiền";
      $order->save();

      $orderProducts = OrderProduct::where("order_id", $order->id)->get();

      foreach ($orderProducts as $item) {
        $item->status = "cancelled";
        $item->save();
      }

      Toastr::success("Huỷ đơn hàng thành công, tiền đơn hàng sẽ được hoàn về trong 48 giờ", "Thành công");
    } else {
      Toastr::error("Đơn hàng đã được xử lý, không thể huỷ đơn hàng này", "Thất bại");
    }

    return redirect()->back();
  }
}
