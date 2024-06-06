<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\PaypalSetting;
use App\Models\Product;
use App\Models\StripeSetting;
use App\Models\Transaction;
use Brian2694\Toastr\Facades\Toastr;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Illuminate\Support\Str;
use Stripe\Stripe;
use Stripe\Charge;

class PaymentController extends Controller
{
  public function index()
  {
    if (!Session::has("shipping_address") || !Session::has("shipping_method")) {
      return redirect()->route("user.checkout");
    }

    return view("frontend.pages.payment");
  }

  public function paymentSuccess()
  {
    return view('frontend.pages.payment-success');
  }

  public function storeOrder($paymentMethod, $paymentStatus, $transactionId, $paidAmount, $paidCurrencyName)
  {
    $order = new Order();

    $order->invoice_id = Str::random(15);
    $order->user_id = Auth::user()->id;
    $order->sub_total = getCartTotal();
    $order->amount = getPayableAmount();
    $order->currency_name = "VND";
    $order->currency_icon = "VND";
    $order->product_quantity = Cart::content()->count();
    $order->payment_method = $paymentMethod;
    $order->payment_status = $paymentStatus;
    $order->order_address = json_encode(Session::get("shipping_address"));
    $order->shipping_method = json_encode(Session::get("shipping_method"));
    $order->coupon = json_encode(Session::get("coupon")) ?? "";
    $order->order_status = 0;
    $order->save();


    foreach (Cart::content() as $item) {
      $product = Product::find($item->id);

      $orderProduct = new OrderProduct();

      $orderProduct->order_id = $order->id;
      $orderProduct->product_id = $item->id;
      $orderProduct->vendor_id = $product->vendor_id;
      $orderProduct->product_name = $product->name;
      $orderProduct->variants = json_encode($item->options->variants);
      $orderProduct->variant_total = $item->options->variants_total;
      $orderProduct->unit_price = $item->price;
      $orderProduct->quantity = $item->qty;
      $orderProduct->save();
    }


    $transaction = new Transaction();

    $transaction->order_id = $order->id;
    $transaction->transaction_id = $transactionId;
    $transaction->payment_method = $paymentMethod;
    $transaction->amount = getPayableAmount();
    $transaction->amount_real_currency = $paidAmount;
    $transaction->amount_real_currency_name = $paidCurrencyName;
    $transaction->save();
  }

  public function clearSession()
  {
    $userId = Auth::user()->id;
    Cart::restore($userId); // Khôi phục giỏ hàng hiện tại từ database

    Cart::destroy();

    Cart::erase($userId);

    Session::forget("shipping_method");
    Session::forget("shipping_address");
    Session::forget("coupon");
  }

  public function paypalConfig()
  {
    $paypalSetting = PaypalSetting::first();

    $config = [
      'mode'    => $paypalSetting->mode == 1 ? "sandbox" : "live", // Can only be 'sandbox' Or 'live'. If empty or invalid, 'live' will be used.
      'sandbox' => [
        'client_id'         => $paypalSetting->client_id,
        'client_secret'     => $paypalSetting->secret_key,
        'app_id'            => "",
      ],
      'live' => [
        'client_id'         => $paypalSetting->client_id,
        'client_secret'     => $paypalSetting->secret_key,
        'app_id'            => "",
      ],

      'payment_action' =>  'Sale', // Can only be 'Sale', 'Authorization' or 'Order'
      'currency'       =>  'USD',
      'notify_url'     =>   "", // Change this accordingly for your application.
      'locale'         =>  'en_US', // force gateway language  i.e. it_IT, es_ES, en_US ... (for express checkout only)
      'validate_ssl'   =>  true, // Validate SSL when creating api client.
    ];

    return $config;
  }

  public function payWithPaypal()
  {
    $paypalSetting = PaypalSetting::first();

    $config = $this->paypalConfig();

    $provider = new PayPalClient($config);
    $provider->getAccessToken();
    // $provider->setApiCredentials($config);

    $total = getPayableAmount();
    $payableAmount = round($total / $paypalSetting->currency_rate, 2);

    $response = $provider->createOrder([
      "intent" => "CAPTURE",
      "application_context" => [
        "return_url" => route('user.paypal.success'),
        "cancel_url" => route('user.paypal.cancel'),
      ],
      "purchase_units" => [
        [
          "amount" => [
            "currency_code" => $config["currency"],
            "value" => $payableAmount
          ]
        ]
      ]
    ]);

    if (isset($response["id"]) && $response["id"] !== null) {
      foreach ($response["links"] as $link) {
        if ($link["rel"] == "approve") {
          return redirect()->away($link["href"]);
        }
      }
    } else {
      return redirect()->route("user.paypal.cancel");
    }
  }

  public function paypalSuccess(Request $request)
  {
    $config = $this->paypalConfig();

    $provider = new PayPalClient($config);
    $provider->getAccessToken();

    $response = $provider->capturePaymentOrder($request->token);

    if (isset($response["status"]) && $response["status"] == "COMPLETED") {

      $paypalSetting = PaypalSetting::first();
      $total = getPayableAmount();
      $payableAmount = round($total / $paypalSetting->currency_rate, 2);

      $this->storeOrder("Paypal", 1, $response["id"], $payableAmount, "USD");

      $this->clearSession();

      return redirect()->route("user.payment.success");
    }

    return redirect()->route("user.paypal.cancel");
  }

  public function paypalCancel()
  {
    Toastr::error("Thanh toán không thành công! Vui lòng kiểm tra lại thông tin và thử lại sau.", "Không thành công");

    return redirect()->route("user.payment");
  }

  public function payWithStripe(Request $request)
  {
    $stripeSetting = StripeSetting::first();

    $total = getPayableAmount();
    $payableAmount = round($total / $stripeSetting->currency_rate, 2);

    Stripe::setApiKey($stripeSetting->secret_key);
    $response = Charge::create([
      "amount" => $payableAmount * 100,
      "currency" => "usd",
      "source" => $request->stripe_token,
      "description" => "Payment from Megamart"
    ]);

    if ($response->status == "succeeded") {

      $this->storeOrder("Stripe", 1, $response->id, $payableAmount, "USD");

      $this->clearSession();

      return redirect()->route("user.payment.success");
    } else {
      Toastr::error("Thanh toán không thành công! Vui lòng kiểm tra lại thông tin và thử lại sau.", "Không thành công");

      return redirect()->route("user.payment");
    }
  }
}
