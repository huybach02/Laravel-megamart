<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ChildCategory;
use App\Models\FlashSale;
use App\Models\FlashSaleItem;
use App\Models\HomePageSetting;
use App\Models\Product;
use App\Models\Slider;
use App\Models\SubCategory;
use App\Models\Vendor;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class HomeController extends Controller
{
  public function index()
  {
    $sliders = Slider::where("status", 1)->orderBy("serial", "asc")->get();
    $flashSaleDate = FlashSale::first();
    $flashSaleItems = FlashSaleItem::where("show_at_home", 1)->where("status", 1)->get();
    $popularCategories = HomePageSetting::where("key", "popular_category_section")->first();
    $brands = Brand::where("status", 1)->where("is_featured", 1)->latest()->get();
    $typeBaseProducts = $this->getTypeBaseProduct();
    $categorySliderSectionOne = HomePageSetting::where("key", "product_slider_section_one")->first();
    $categorySliderSectionTwo = HomePageSetting::where("key", "product_slider_section_two")->first();
    $weeklyBestRated = HomePageSetting::where("key", "weekly_best_rated")->first();
    $weeklyBestSell = HomePageSetting::where("key", "weekly_best_sell")->first();
    $homePageBannerOne = Advertisement::where("key", "home_page_banner_one")->first();
    $homePageBannerOne = json_decode($homePageBannerOne?->value);
    $homePageBannerTwo = Advertisement::where("key", "home_page_banner_two")->first();
    $homePageBannerTwo = json_decode($homePageBannerTwo?->value);
    $homePageBannerThree = Advertisement::where("key", "home_page_banner_three")->first();
    $homePageBannerThree = json_decode($homePageBannerThree?->value);
    $homePageBannerFour = Advertisement::where("key", "home_page_banner_four")->first();
    $homePageBannerFour = json_decode($homePageBannerFour?->value);

    return view("frontend.home.home", compact("sliders", "flashSaleDate", "flashSaleItems", "popularCategories", "brands", "typeBaseProducts", "categorySliderSectionOne", "categorySliderSectionTwo", "weeklyBestRated", "weeklyBestSell", "homePageBannerOne", "homePageBannerTwo", "homePageBannerThree", "homePageBannerFour"));
  }

  public function getTypeBaseProduct()
  {
    $typeBaseProducts = [];

    $typeBaseProducts["new_product"] = Product::where(["product_type" => "new_product", "is_approved" => 1, "status" => 1])->orderBy("created_at", "desc")->take(8)->get();
    $typeBaseProducts["featured_product"] = Product::where(["product_type" => "featured_product", "is_approved" => 1, "status" => 1])->orderBy("created_at", "desc")->take(8)->get();
    $typeBaseProducts["top_product"] = Product::where(["product_type" => "top_product", "is_approved" => 1, "status" => 1])->orderBy("created_at", "desc")->take(8)->get();
    $typeBaseProducts["best_product"] = Product::where(["product_type" => "best_product", "is_approved" => 1, "status" => 1])->orderBy("created_at", "desc")->take(8)->get();

    return $typeBaseProducts;
  }

  public function vendorPage()
  {
    $vendors = Vendor::latest()->paginate(12);

    return view("frontend.pages.vendor", compact("vendors"));
  }

  public function vendorDetail(Request $request, $id)
  {
    $products = Product::where(["status" => 1, "is_approved" => 1, "vendor_id" => $id])->latest()->paginate(12);
    $categories = Category::where("status", 1)->get();
    $brands = Brand::where("status", 1)->get();
    $vendor = Vendor::where("id", $id)->first();

    return view("frontend.pages.vendor-detail", compact("products", "categories", "brands", "vendor"));
  }
}
