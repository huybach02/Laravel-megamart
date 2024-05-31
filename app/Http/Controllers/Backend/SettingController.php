<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class SettingController extends Controller
{
  public function index()
  {
    $generalSetting = GeneralSetting::first();

    return view("admin.setting.index", compact("generalSetting"));
  }

  public function generalSettingUpdate(Request $request)
  {
    $request->validate([
      "site_name" => ["required", "max:100"],
      "contact_email" => ["required", "email"]
    ]);

    GeneralSetting::updateOrCreate([
      "id" => 1
    ], [
      "site_name" => $request->site_name,
      "contact_email" => $request->contact_email
    ]);

    Toastr::success("Cập nhật cài đặt thành công", "Thành công");

    return redirect()->back();
  }
}
