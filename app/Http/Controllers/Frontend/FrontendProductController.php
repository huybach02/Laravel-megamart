<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ChildCategory;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class FrontendProductController extends Controller
{
  public function showProduct(string $slug)
  {
    $product = Product::with(["vendor", "category", "productImageGalleries", "variants", "brand"])->where("slug", $slug)->where("status", 1)->first();

    $reviews = ProductReview::where(["product_id" => $product->id, "status" => 1])->latest()->paginate(5);

    $reviewCount = ProductReview::where(["product_id" => $product->id, "status" => 1])->count();

    $relatedProducts=Product::where("category_id",$product->category_id)->latest()->limit(12)->get();

    return view("frontend.pages.product-detail", compact("product", "reviews", "reviewCount","relatedProducts"));
  }

  public function productIndex(Request $request)
  {
    $products = Product::where(["status" => 1, "is_approved" => 1])->latest()->paginate(12);
    if ($request->has("category")) {
      $category = Category::where("slug", $request->category)->first();
      $products = Product::where(["status" => 1, "category_id" => $category->id, "is_approved" => 1])->when($request->has("brand"), function ($query) use ($request) {
        $brand = Brand::where("slug", $request->brand)->first();

        return $query->where("brand_id", $brand->id);
      })->when($request->has("price_range"), function ($query) use ($request) {
        $price = explode(";", $request->price_range);
        $from = $price[0];
        $to = $price[1];

        return $query->where("price", ">=", $from)->where("price", "<=", $to);
      })->paginate(12);
    }
    if ($request->has("sub_category")) {
      $subCategory = SubCategory::where("slug", $request->sub_category)->first();
      $products = Product::where(["status" => 1, "sub_category_id" => $subCategory->id, "is_approved" => 1])->when($request->has("price_range"), function ($query) use ($request) {
        $price = explode(";", $request->price_range);
        $from = $price[0];
        $to = $price[1];

        return $query->where("price", ">=", $from)->where("price", "<=", $to);
      })->paginate(12);
    }
    if ($request->has("child_category")) {
      $childCategory = ChildCategory::where("slug", $request->child_category)->first();
      $products = Product::where(["status" => 1, "child_category_id" => $childCategory->id, "is_approved" => 1])->when($request->has("price_range"), function ($query) use ($request) {
        $price = explode(";", $request->price_range);
        $from = $price[0];
        $to = $price[1];

        return $query->where("price", ">=", $from)->where("price", "<=", $to);
      })->paginate(12);
    }
    if ($request->has("brand")) {
      $brand = Brand::where("slug", $request->brand)->first();
      $products = Product::where(["status" => 1, "brand_id" => $brand->id, "is_approved" => 1])->when($request->has("price_range"), function ($query) use ($request) {
        $price = explode(";", $request->price_range);
        $from = $price[0] ?? 0;
        $to = $price[1] ?? 100000000;

        return $query->where("price", ">=", $from)->where("price", "<=", $to);
      })->when($request->has("category"), function ($query) use ($request) {
        $category = Category::where("slug", $request->category)->first();

        return $query->where("category_id", $category->id);
      })->paginate(12);
    }
    if ($request->has("search")) {
      $products = Product::where(["status" => 1, "is_approved" => 1])->where("name", "like", "%$request->search%")->when($request->has("category"), function ($query) use ($request) {
        $category = Category::where("slug", $request->category)->first();

        return $query->where("category_id", $category->id);
      })->when($request->has("brand"), function ($query) use ($request) {
        $brand = Brand::where("slug", $request->brand)->first();

        return $query->where("brand_id", $brand->id);
      })->when($request->has("price_range"), function ($query) use ($request) {
        $price = explode(";", $request->price_range);
        $from = $price[0];
        $to = $price[1];

        return $query->where("price", ">=", $from)->where("price", "<=", $to);
      })->paginate(12);
    }

    $categories = Category::where("status", 1)->get();
    $brands = Brand::where("status", 1)->get();

    return view("frontend.pages.products", compact("products", "categories", "brands"));
  }

  public function changeListView(Request $request)
  {
    session()->put("product_list_style", $request->style);
  }
}