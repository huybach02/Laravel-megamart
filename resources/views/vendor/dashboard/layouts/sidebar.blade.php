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
        <li><a class="mb-4" href="{{ route('home') }}"><i class="fas fa-chevron-left"></i>Quay về Trang chủ</a></li>
        <li><a class="active" href="dsahboard.html"><i class="fas fa-tachometer"></i>Dashboard</a></li>
        <li><a href="dsahboard_order.html"><i class="fas fa-list-ul"></i> Orders</a></li>
        <li><a href="dsahboard_download.html"><i class="far fa-cloud-download-alt"></i> Downloads</a></li>
        <li><a href="dsahboard_review.html"><i class="far fa-star"></i> Reviews</a></li>
        <li><a href="dsahboard_wishlist.html"><i class="far fa-heart"></i> Wishlist</a></li>
        <li><a href="dsahboard_profile.html"><i class="far fa-user"></i> My Profile</a></li>
        <li><a href="dsahboard_address.html"><i class="fal fa-gift-card"></i> Addresses</a></li>
        <li>
            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault();
            this.closest('form').submit();"><i
                        class="far fa-sign-out-alt"></i> Log out</a>
            </form>

        </li>
    </ul>
</div>
