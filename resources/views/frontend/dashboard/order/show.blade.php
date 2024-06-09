@php
    $address = json_decode($order->order_address);
    $shipping = json_decode($order->shipping_method);
    $coupon = json_decode($order->coupon);
@endphp

@extends('frontend.dashboard.layouts.master')

@section('title')
    {{ $settings->site_name }} | Khách hàng | Đơn hàng
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-9 col-xxl-10 col-lg-9 ms-auto">
            <div class="dashboard_content mt-2 mt-md-0">
                <a href="{{ route('user.orders.index') }}" class="btn btn-primary mb-3">
                    <i class="fas fa-caret-left"></i> Quay lại</a>
                <h3><i class="far fa-layer-group"></i> Quản lý đơn hàng</h3>
                <div class="wsus__dashboard_profile">
                    <div class="wsus__dash_pro_area section-body">

                        <div class="mb-4 d-flex justify-content-between align-items-center">
                            <p class="h5 fw-bold text-primary">Chi tiết đơn hàng #{{ $order->invoice_id }}</p>
                        </div>

                        <div class="wsus__invoice_header">
                            <div class="wsus__invoice_content">
                                <div class="row">
                                    <div class="col-xl-3 col-md-3 mb-5 mb-md-0">
                                        <div class="wsus__invoice_single">
                                            <h5>Khách hàng</h5>
                                            Tên khách hàng: {{ $address->name }}<br>
                                            Email: {{ $address->email }}<br>
                                            Số điện thoại: {{ $address->phone }}<br>

                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-3 mb-5 mb-md-0">
                                        <div class="wsus__invoice_single text-md-start">
                                            <h5>Địa chỉ</h5>
                                            Số nhà/Căn hộ: {{ $address->address }}<br>
                                            Xã/Phường: {{ $address->commune_ward }}<br>
                                            Quận/Huyện: {{ $address->district }}<br>
                                            Tỉnh/Thành phố: {{ $address->province_city }}<br>
                                            Lưu ý:
                                            {{ $address->other ? $address->other : 'Không có' }}<br>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-3">
                                        <div class="wsus__invoice_single text-md-start">
                                            <h5>Thông tin đơn hàng</h5>
                                            Thanh toán qua: {{ $order->payment_method }}<br>
                                            Mã thanh toán:
                                            {{ $order->transaction->transaction_id }}<br><br>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-3">
                                        <div class="wsus__invoice_single text-md-center">
                                            <h5>Ngày đặt hàng</h5>
                                            {{ $order->created_at }}<br><br>
                                            <h5>Trạng thái đơn hàng</h5>
                                            @foreach (config('order_status.order_status_admin') as $key => $orderStatus)
                                                @if ($key == $order->order_status)
                                                    {{ $orderStatus['status'] }}
                                                @endif
                                            @endforeach
                                            <br><br>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive my-5">
                                <table class="table table-striped table-hover table-md">
                                    <tr>
                                        <th data-width="40">#</th>
                                        <th>Tên sản phẩm</th>
                                        <th>Gian hàng</th>
                                        <th class="text-center">Đơn giá</th>
                                        <th class="text-center">Số lượng</th>
                                        <th class="text-center">Phiên bản</th>
                                        <th class="text-center">Tổng tiền phiên bản</th>
                                        <th class="text-right">Thành tiền</th>
                                    </tr>

                                    @foreach ($order->orderProducts as $index => $product)
                                        @php
                                            $variants = json_decode($product->variants);
                                        @endphp
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <a target="blank"
                                                    href="{{ route('product-detail', $product->product->slug) }}">{{ $product->product_name }}</a>
                                            </td>
                                            <td>{{ $product->vendor->name }}</td>
                                            <td class="text-center">
                                                {{ number_format($product->unit_price) }}đ</td>
                                            <td class="text-center">{{ $product->quantity }}
                                            </td>
                                            <td>
                                                @foreach ($variants as $key => $variant)
                                                    <span>{{ $key }}:
                                                        {{ $variant->name }}
                                                        {{ $variant->price > 0 ? '(+' . number_format($variant->price) . 'đ)' : '' }}
                                                    </span>
                                                    <br>
                                                @endforeach
                                            </td>
                                            <td class="text-center">
                                                + {{ number_format($product->variant_total) }}đ
                                            </td>
                                            <td class="text-right">
                                                {{ number_format(($product->unit_price + $product->variant_total) * $product->quantity) }}đ
                                            </td>
                                        </tr>
                                    @endforeach

                                </table>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <div class="d-flex flex-column">
                                <h6 class="mb-3 "><strong>Tổng tiền sản phẩm:</strong>
                                    {{ number_format($order->sub_total) }}đ </h6>
                                <h6 class="mb-3 "><strong>Phí vận chuyển:</strong>
                                    + {{ number_format($shipping->cost) }}đ </h6>
                                <h6 class="mb-3 "><strong>Giảm giá:</strong>
                                    @if ($coupon && $coupon->discount_type == 'amount')
                                        - {{ number_format($coupon->discount) }}đ
                                    @elseif ($coupon && $coupon->discount_type == 'percent')
                                        -
                                        {{ number_format(($order->sub_total * $coupon->discount) / 100) }}đ
                                        ({{ $coupon->discount }}%)
                                    @else
                                        - 0đ
                                    @endif
                                </h6>
                                <h5 class="mb-3 pt-3 border-top"><strong>Tổng tiền đơn hàng:</strong>
                                    {{ number_format($order->amount) }}đ </h5>
                            </div>
                        </div>

                        <div class="row pt-5">
                            <div class="">
                                <button class="btn btn-warning print-invoice">Print</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        $('#example').DataTable({
            "order": [
                [0, "desc"]
            ]
        });

        $(".print-invoice").on("click", function() {
            let printBody = $(".section-body")
            let originalContents = $("body").html()

            $("body").html(printBody.html())
            window.print()
            $("body").html(originalContents)
        })
    </script>
@endpush
