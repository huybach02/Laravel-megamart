<?php

use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\AdminVendorProfileController;
use App\Http\Controllers\Backend\BrandController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\ChildCategoryController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\ProductImageGalleryController;
use App\Http\Controllers\Backend\ProfileController;
use App\Http\Controllers\Backend\SliderController;
use App\Http\Controllers\Backend\SubCategoryController;
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
Route::get("product/get-subcategories", [ProductController::class, "getSubCategories"])->name("products.get-subcategories");
Route::get("product/get-childcategories", [ProductController::class, "getChildCategories"])->name("products.get-childcategories");
Route::resource('products', ProductController::class);
Route::resource('product-image-gallery', ProductImageGalleryController::class);

Route::fallback(function () {
  return redirect()->route("admin.dashboard");
});
