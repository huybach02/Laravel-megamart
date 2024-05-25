<div class="dashboard_sidebar">
    <span class="close_icon">
        <i class="far fa-bars dash_bar"></i>
        <i class="far fa-times dash_close"></i>
    </span>
    <a href="{{ route('vendor.dashboard') }}" class="dash_logo p-3 bg-white">
        <img src="{{ asset('logo_transparent.png') }}" width="140px" alt="logo" class="img-fluid">
        <p class="mt-2 ">Gian hàng</p>
    </a>
    <ul class="dashboard_link">
        <li><a class="mb-4 bg-dark" href="{{ route('home') }}"><i class="fas fa-chevron-left"></i>Quay
                về Trang
                chủ</a>
        </li>
        <li><a class="active" href="{{ route('vendor.dashboard') }}"><i class="fas fa-tachometer"></i>Dashboard</a></li>
        <li><a href="{{ route('vendor.shop-profile.index') }}"><i class="far fa-hotel"></i> Thông tin gian hàng</a></li>
        <li><a href="{{ route('vendor.profile') }}"><i class="far fa-user"></i> Thông tin tài khoản</a></li>
        <li>
            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault();
            this.closest('form').submit();"
                    class="mt-4 bg-danger"><i class="far fa-sign-out-alt"></i> Đăng xuất</a>
            </form>

        </li>
    </ul>
</div>
