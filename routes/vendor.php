<?php

use App\Http\Controllers\Backend\VendorController;
use App\Http\Controllers\Backend\VendorProductController;
use App\Http\Controllers\Backend\VendorProfileController;
use App\Http\Controllers\Backend\VendorShopProfileController;
use Illuminate\Support\Facades\Route;

Route::get("dashboard", [VendorController::class, "dashboard"])->name("dashboard");
Route::get("profile", [VendorProfileController::class, "index"])->name("profile");
Route::put("profile", [VendorProfileController::class, "updateProfile"])->name("profile.update");
Route::post("profile", [VendorProfileController::class, "updatePassword"])->name("profile.update.password");

Route::get("product/get-subcategories", [VendorProductController::class, "getSubCategories"])->name("products.get-subcategories");
Route::get("product/get-childcategories", [VendorProductController::class, "getChildCategories"])->name("products.get-childcategories");

Route::resource("shop-profile", VendorShopProfileController::class);
Route::resource("products", VendorProductController::class);
