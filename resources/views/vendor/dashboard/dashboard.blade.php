@extends('vendor.layouts.master')

@section('title')
    {{ $settings->site_name }} | Gian hàng | Thống kê
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-9 col-xxl-10 col-lg-9 ms-auto">
            <div class="dashboard_content">
                <div class="wsus__dashboard">
                    <div class="row">
                        <div class="col-xl-2 col-6 col-md-4">
                            <a class="wsus__dashboard_item red" href="dsahboard_order.html">
                                <i class="far fa-layer-group"></i>
                                <p>Sản Phẩm</p>
                                <h4 style="color: #fff">{{ $products }}</h4>
                            </a>
                        </div>
                        <div class="col-xl-2 col-6 col-md-4">
                            <a class="wsus__dashboard_item green" href="dsahboard_download.html">
                                <i class="fal fa-scroll"></i>
                                <p>Tổng Đơn Hàng</p>
                                <h4 style="color: #fff">{{ $totalOrders }}</h4>
                            </a>
                        </div>
                        <div class="col-xl-2 col-6 col-md-4">
                            <a class="wsus__dashboard_item green" href="dsahboard_download.html">
                                <i class="fal fa-calendar-week"></i>
                                <p>Đơn Hàng Hôm Nay</p>
                                <h4 style="color: #fff">{{ $todayOrders }}</h4>
                            </a>
                        </div>
                        <div class="col-xl-2 col-6 col-md-4">
                            <a class="wsus__dashboard_item sky" href="dsahboard_review.html">
                                <i class="fas fa-check"></i>
                                <p>Đơn Hàng Thành Công</p>
                                <h4 style="color: #fff">{{ $successOrders }}</h4>
                            </a>
                        </div>
                        <div class="col-xl-2 col-6 col-md-4">
                            <a class="wsus__dashboard_item blue" href="dsahboard_wishlist.html">
                                <i class="far fa-star"></i>
                                <p>Đánh Giá</p>
                                <h4 style="color: #fff">{{ $reviews }}</h4>
                            </a>
                        </div>
                        <div class="col-xl-4 col-6 col-md-4">
                            <a class="wsus__dashboard_item orange" href="dsahboard_profile.html">
                                <i class="far fa-money-bill"></i>
                                <p>Doanh Thu</p>
                                <h4 style="color: #fff">{{ number_format($totalEarnings) }}đ</h4>
                            </a>
                        </div>
                        <div class="col-xl-4 col-6 col-md-4">
                            <a class="wsus__dashboard_item orange" href="dsahboard_profile.html">
                                <i class="far fa-money-bill"></i>
                                <p>Doanh Thu Hôm Nay</p>
                                <h4 style="color: #fff">{{ number_format($todayEarnings) }}đ</h4>
                            </a>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Biểu đồ doanh thu -->
                        <form method="GET" class="d-flex align-items-center gap-2 mb-3">
                            <label for="year">Chọn năm:</label>
                            <select class="form-control w-25" name="year" id="year">
                                @foreach (range(\Carbon\Carbon::now()->year - 2, \Carbon\Carbon::now()->year) as $y)
                                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                                        {{ $y }}</option>
                                @endforeach
                            </select>
                            <button class="btn btn-primary" type="submit">Xem</button>
                        </form>
                        <div class="col-xl-6">
                            <div class="wsus__dashboard_item">
                                <canvas id="monthlyRevenueChart" width="400" height="200"></canvas>
                            </div>
                        </div>

                        <!-- Biểu đồ số đơn hàng -->
                        <div class="col-xl-6">
                            <div class="wsus__dashboard_item">
                                <canvas id="monthlyOrdersChart" width="400" height="200"></canvas>
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
        document.addEventListener('DOMContentLoaded', function() {
            // Biểu đồ doanh thu
            var ctxRevenue = document.getElementById('monthlyRevenueChart').getContext('2d');
            var chartRevenue = new Chart(ctxRevenue, {
                type: 'bar',
                data: {
                    labels: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7',
                        'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'
                    ],
                    datasets: [{
                        label: 'Doanh thu',
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1,
                        data: [
                            @foreach (range(1, 12) as $month)
                                {{ $monthlyEarnings[$month] ?? 0 }},
                            @endforeach
                        ],
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                callback: function(value) {
                                    return value.toLocaleString('en-US') + 'đ';
                                }
                            }
                        }]
                    },
                    hover: {
                        animationDuration: 0 // Tắt hiệu ứng zoom khi hover
                    },
                    animation: {
                        duration: 0 // Tắt toàn bộ animation
                    }
                }
            });

            // Biểu đồ số đơn hàng
            var ctxOrders = document.getElementById('monthlyOrdersChart').getContext('2d');
            var chartOrders = new Chart(ctxOrders, {
                type: 'bar',
                data: {
                    labels: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7',
                        'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'
                    ],
                    datasets: [{
                        label: 'Số đơn hàng',
                        backgroundColor: 'rgba(255, 159, 64, 0.5)',
                        borderColor: 'rgba(255, 159, 64, 1)',
                        borderWidth: 1,
                        data: [
                            @foreach (range(1, 12) as $month)
                                {{ $monthlyOrders[$month] ?? 0 }},
                            @endforeach
                        ],
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                stepSize: 1
                            }
                        }]
                    },
                }
            });
        });
    </script>
@endpush
