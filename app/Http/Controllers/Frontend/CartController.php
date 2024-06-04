<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariantItem;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
  public function addToCart(Request $request)
  {
    $product = Product::findOrFail($request->product_id);

    if ($product->quantity == 0) {
      return response([
        "message" => "Sản phẩm đã hết hàng",
        "status" => "error"
      ]);
    } elseif ($product->quantity < $request->quantity) {
      return response([
        "message" => "Sản phẩm không đủ số lượng",
        "status" => "error"
      ]);
    }

    $variants = [];

    $variantTotalAmount = 0;

    if ($request->has("variants_items")) {
      foreach ($request->variants_items as $item_id) {
        $variantItem = ProductVariantItem::find($item_id);
        $variants[$variantItem->productVariant->name]["name"] = $variantItem->name;
        $variants[$variantItem->productVariant->name]["price"] = $variantItem->price;
        $variantTotalAmount += $variantItem->price;
      }
    }

    $price = checkDisCount($product) ? $product->offer_price : $product->price;

    $cartData = [];

    $cartData["id"] = $product->id;
    $cartData["name"] = $product->name;
    $cartData["qty"] = $request->quantity;
    $cartData["price"] = $price;
    $cartData["weight"] = 10;
    $cartData["options"]["variants"] = $variants;
    $cartData["options"]["variants_total"] = $variantTotalAmount;
    $cartData["options"]["image"] = $product->thumb_image;
    $cartData["options"]["slug"] = $product->slug;
    $cartData["options"]["stock"] = $product->quantity;

    Cart::add($cartData);

    return response([
      "message" => "Thêm sản phẩm vào giỏ hàng thành công",
      "status" => "success"
    ]);
  }

  public function cartDetails()
  {
    $cartItems = Cart::content();

    return view("frontend.pages.cart-detail", compact("cartItems"));
  }

  public function updateProductQuantity(Request $request)
  {
    $productId = Cart::get($request->rowId)->id;

    $product = Product::findOrFail($productId);

    if ($product->quantity == 0) {
      return response([
        "message" => "Sản phẩm đã hết hàng",
        "status" => "error"
      ]);
    } elseif ($product->quantity < $request->quantity) {
      return response([
        "message" => "Sản phẩm không đủ số lượng",
        "status" => "error"
      ]);
    }

    Cart::update($request->rowId, $request->quantity);

    $productTotal = $this->getProductTotal($request->rowId);

    return response([
      "message" => "Cập nhật số lượng sản phẩm thành công",
      "status" => "success",
      "product_total" => $productTotal
    ]);
  }

  public function getProductTotal($rowId)
  {
    $cartItem = Cart::get($rowId);

    return ($cartItem->price + $cartItem->options->variants_total) * $cartItem->qty;
  }

  public function clearCart()
  {
    Cart::destroy();

    return response([
      "message" => "Xóa giỏ hàng thành công",
      "status" => "success"
    ]);
  }

  public function removeProduct($rowId)
  {
    Cart::remove($rowId);

    return redirect()->back();
  }

  public function getCartCount()
  {
    return Cart::content()->count();
  }

  public function getCartProducts()
  {
    return Cart::content();
  }

  public function removeSidebarProduct(Request $request)
  {
    Cart::remove($request->rowId);

    return response([
      "message" => "Xóa sản phẩm khỏi giỏ hàng thành công",
      "status" => "success"
    ]);
  }

  public function cartTotal()
  {
    $total = 0;

    foreach (Cart::content() as $item) {
      $total += $this->getProductTotal($item->rowId);
    }

    return $total;
  }
}
