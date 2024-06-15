<?php

use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\AdminProductReviewController;
use App\Http\Controllers\Backend\AdminVendorProfileController;
use App\Http\Controllers\Backend\AdvertisementController;
use App\Http\Controllers\Backend\BrandController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\ChildCategoryController;
use App\Http\Controllers\Backend\CouponController;
use App\Http\Controllers\Backend\FlashSaleController;
use App\Http\Controllers\Backend\FooterGridTwoController;
use App\Http\Controllers\Backend\FooterInfoController;
use App\Http\Controllers\Backend\HomePageSettingController;
use App\Http\Controllers\Backend\OrderController;
use App\Http\Controllers\Backend\PaymentSettingController;
use App\Http\Controllers\Backend\PaypalSettingController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\ProductImageGalleryController;
use App\Http\Controllers\Backend\ProductVariantController;
use App\Http\Controllers\Backend\ProductVariantItemController;
use App\Http\Controllers\Backend\ProfileController;
use App\Http\Controllers\Backend\SellerProductController;
use App\Http\Controllers\Backend\SettingController;
use App\Http\Controllers\Backend\ShippingRuleController;
use App\Http\Controllers\Backend\SliderController;
use App\Http\Controllers\Backend\StripeSettingController;
use App\Http\Controllers\Backend\SubCategoryController;
use App\Http\Controllers\Backend\SubscriberController;
use App\Http\Controllers\Backend\TransactionController;
use App\Models\ShippingRule;
use Illuminate\Support\Facades\Route;

// Dashboard
Route::get("dashboard", [AdminController::class, "dashboard"])->name("dashboard");

// Profile
Route::get("profile", [ProfileController::class, "index"])->name("profile");
Route::post("profile/update", [ProfileController::class, "updateProfile"])->name("profile.update");
Route::post("profile/update/password", [ProfileController::class, "updatePassword"])->name("password.update");

// Slider
Route::put("slider/change-status", [SliderController::class, "changeStatus"])->name("slider.change-status");
Route::resource('slider', SliderController::class);

// Category
Route::put("category/change-status", [CategoryController::class, "changeStatus"])->name("category.change-status");
Route::resource('category', CategoryController::class);

// SubCategory
Route::put("sub-category/change-status", [SubCategoryController::class, "changeStatus"])->name("sub-category.change-status");
Route::resource('sub-category', SubCategoryController::class);

// ChildCategory
Route::put("child-category/change-status", [ChildCategoryController::class, "changeStatus"])->name("child-category.change-status");
Route::get("get-subcategories", [ChildCategoryController::class, "getSubCategories"])->name("get-subcategories");
Route::resource('child-category', ChildCategoryController::class);

// Brand
Route::put("brand/change-status", [BrandController::class, "changeStatus"])->name("brand.change-status");
Route::resource('brand', BrandController::class);

// Vendor
Route::resource('vendor-profile', AdminVendorProfileController::class);

// Product
Route::put("product/change-status", [ProductController::class, "changeStatus"])->name("products.change-status");
Route::put("product-variant/change-status", [ProductVariantController::class, "changeStatus"])->name("product-variant.change-status");
Route::put("product-variant-item/change-status", [ProductVariantItemController::class, "changeStatus"])->name("product-variant-item.change-status");

Route::get("product/get-subcategories", [ProductController::class, "getSubCategories"])->name("products.get-subcategories");
Route::get("product/get-childcategories", [ProductController::class, "getChildCategories"])->name("products.get-childcategories");
Route::resource('products', ProductController::class);
Route::resource('product-image-gallery', ProductImageGalleryController::class);
Route::resource('product-variant', ProductVariantController::class);

Route::get("product-variant-item", [ProductVariantItemController::class, "index"])->name("product-variant-item.index");
Route::get("product-variant-item/create", [ProductVariantItemController::class, "create"])->name("product-variant-item.create");
Route::post("product-variant-item", [ProductVariantItemController::class, "store"])->name("product-variant-item.store");
Route::get("product-variant-item/{variantItemId}/edit", [ProductVariantItemController::class, "edit"])->name("product-variant-item.edit");
Route::put("product-variant-item/{variantItemId}/update", [ProductVariantItemController::class, "update"])->name("product-variant-item.update");
Route::delete("product-variant-item/{variantItemId}", [ProductVariantItemController::class, "destroy"])->name("product-variant-item.destroy");

Route::get("seller-products", [SellerProductController::class, "index"])->name("seller-products.index");
Route::get("seller-pending-products", [SellerProductController::class, "pendingProducts"])->name("seller-pending-products.index");
Route::put("change-approve-status", [SellerProductController::class, "changeApproveStatus"])->name("change-approve-status");

// Flash sale
Route::get("flash-sale", [FlashSaleController::class, "index"])->name("flash-sale.index");
Route::put("flash-sale", [FlashSaleController::class, "update"])->name("flash-sale.update");
Route::post("flash-sale/add-product", [FlashSaleController::class, "addProduct"])->name("flash-sale.add-product");
Route::put("flash-sale/show-at-home/change-status", [FlashSaleController::class, "changeShowAtHomeStatus"])->name("flash-sale.show-at-home.change-status");
Route::put("flash-sale/change-status", [FlashSaleController::class, "changeStatus"])->name("flash-sale.change-status");
Route::delete("flash-sale/{id}", [FlashSaleController::class, "destroy"])->name("flash-sale.destroy");

// Settings
Route::get("settings", [SettingController::class, "index"])->name("setting.index");
Route::put("general-setting-update", [SettingController::class, "generalSettingUpdate"])->name("general-setting-update");
Route::put("email-setting-update", [SettingController::class, "emailSettingUpdate"])->name("email-setting-update");

// Homepage Settings
Route::get("home-page-setting", [HomePageSettingController::class, "index"])->name("home-page-setting.index");
Route::put("popular-category-section", [HomePageSettingController::class, "updatePopularCategorySection"])->name("popular-category-section");
Route::put("product-slider-section-one", [HomePageSettingController::class, "updateProductSliderSectionOne"])->name("product-slider-section-one");
Route::put("product-slider-section-two", [HomePageSettingController::class, "updateProductSliderSectionTwo"])->name("product-slider-section-two");
Route::put("weekly-best-rated", [HomePageSettingController::class, "weeklyBestRated"])->name("weekly-best-rated");
Route::put("weekly-best-sell", [HomePageSettingController::class, "weeklyBestSell"])->name("weekly-best-sell");

// Coupon
Route::put("coupons/change-status", [CouponController::class, "changeStatus"])->name("coupons.change-status");
Route::resource("coupons", CouponController::class);

// Shipping Rule
Route::put("shipping-rule/change-status", [ShippingRuleController::class, "changeStatus"])->name("shipping-rule.change-status");
Route::resource("shipping-rule", ShippingRuleController::class);

// Payment Setting
Route::get("payment-settings", [PaymentSettingController::class, "index"])->name("payment-settings.index");
Route::resource("paypal-setting", PaypalSettingController::class);
Route::put("stripe-setting/{id}", [StripeSettingController::class, "update"])->name("stripe-setting.update");

// Orders
Route::put("order-status", [OrderController::class, "changeOrderStatus"])->name("order.status");
Route::put("payment-status", [OrderController::class, "changePaymentStatus"])->name("order.payment-status");

Route::get("order-filter", [OrderController::class, "orderFilter"])->name("order.filter");
Route::resource("order", OrderController::class);

Route::get("transactions", [TransactionController::class, "index"])->name("transactions.index");

// Footer
Route::resource("footer-info", FooterInfoController::class);
Route::put("footer-grid-two/change-status", [FooterGridTwoController::class, "changeStatus"])->name("footer-grid-two.change-status");
Route::resource("footer-grid-two", FooterGridTwoController::class);

// Subscriber
Route::get("subscribers", [SubscriberController::class, "index"])->name("subscribers.index");
Route::post("subscribers-send-mail", [SubscriberController::class, "sendMail"])->name("subscribers-send-mail");

// Advertisement
Route::get("advertisement", [AdvertisementController::class, "index"])->name("advertisement.index");
Route::put("advertisement/home-page-banner-one", [AdvertisementController::class, "homePageBannerOne"])->name("advertisement.home-page-banner-one");
Route::put("advertisement/home-page-banner-two", [AdvertisementController::class, "homePageBannerTwo"])->name("advertisement.home-page-banner-two");
Route::put("advertisement/home-page-banner-three", [AdvertisementController::class, "homePageBannerThree"])->name("advertisement.home-page-banner-three");
Route::put("advertisement/home-page-banner-four", [AdvertisementController::class, "homePageBannerFour"])->name("advertisement.home-page-banner-four");

// Review
Route::get("reviews", [AdminProductReviewController::class, "index"])->name("reviews.index");
Route::put("reviews/change-status", [AdminProductReviewController::class, "changeStatus"])->name("reviews.change-status");
Route::delete("reviews/{id}", [AdminProductReviewController::class, "destroy"])->name("reviews.destroy");

Route::fallback(function () {
  return redirect()->route("admin.dashboard");
});
