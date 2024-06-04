<?php

// Set sidebar item active

use Gloudemans\Shoppingcart\Facades\Cart;

function setActive(array $route)
{
  if (is_array($route)) {
    foreach ($route as $r) {
      if (request()->routeIs($r)) {
        return "active";
      }
    }
  }
}

function checkDisCount($product)
{
  $currentDate = date("Y-m-d");

  if ($product->offer_end_date > $product->offer_start_date && $product->offer_start_date <= $currentDate && $currentDate <= $product->offer_end_date && $product->offer_price < $product->price) {
    return true;
  }

  return false;
}

function calculateDiscountPercent($product)
{
  if ($product->offer_price < $product->price) {
    return round((($product->price - $product->offer_price) / $product->price) * 100, 0);
  }
}

function productType($product)
{
  switch ($product->product_type) {
    case 'new_product':
      return "Mới";
      break;
    case 'featured_product':
      return "Nổi bật";
      break;
    case 'top_product':
      return "Phổ biến";
      break;
    default:
      return "Tốt nhất";
      break;
  }
}

function getCartTotal()
{
  $total = 0;

  foreach (Cart::content() as $item) {
    $total += ($item->price + $item->options->variants_total) * $item->qty;
  }

  return number_format($total);
}
