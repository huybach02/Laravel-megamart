<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="index.html">
                <img width="100px" src="{{ asset('logo.png') }}" alt="">
            </a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="index.html">St</a>
        </div>
        <ul class="sidebar-menu">
            {{-- <li class="menu-header">Dashboard</li> --}}
            <li class="dropdown">
                <a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="fas fa-fire"></i><span>Thống
                        kê</span></a>
            </li>
            {{-- <li class="menu-header">Starter</li> --}}
            <li class="dropdown {{ setActive(['admin.slider.*']) }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-columns"></i>
                    <span>Quản Lý Website</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ setActive(['admin.slider.*']) }}"><a class="nav-link"
                            href="{{ route('admin.slider.index') }}">Slider</a></li>
                    <li><a class="nav-link" href="layout-transparent.html">Transparent Sidebar</a></li>
                    <li><a class="nav-link" href="layout-top-navigation.html">Top Navigation</a></li>
                </ul>
            </li>

            <li
                class="dropdown {{ setActive(['admin.category.*', 'admin.sub-category.*', 'admin.child-category.*']) }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-list"></i>
                    <span>Quản Lý Danh Mục</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ setActive(['admin.category.*']) }}"><a class="nav-link"
                            href="{{ route('admin.category.index') }}">Danh mục cấp 1</a></li>
                    <li class="{{ setActive(['admin.sub-category.*']) }}"><a class="nav-link"
                            href="{{ route('admin.sub-category.index') }}">Danh mục cấp 2</a></li>
                    <li class="{{ setActive(['admin.child-category.*']) }}"><a class="nav-link"
                            href="{{ route('admin.child-category.index') }}">Danh mục cấp 3</a></li>
                </ul>
            </li>

            <li
                class="dropdown {{ setActive(['admin.brand.*', 'admin.products.index', 'admin.seller-products.*', 'admin.seller-pending-products.*']) }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i
                        class="fas fa-layer-group"></i>
                    <span>Quản Lý Sản Phẩm</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ setActive(['admin.brand.*']) }}"><a class="nav-link"
                            href="{{ route('admin.brand.index') }}">Thương Hiệu</a></li>
                    <li class="{{ setActive(['admin.products.index']) }}">
                        <a class="nav-link" href="{{ route('admin.products.index') }}">Sản Phẩm Của MegaMart</a>
                    </li>
                    <li class="{{ setActive(['admin.seller-products.index']) }}"><a class="nav-link"
                            href="{{ route('admin.seller-products.index') }}">Sản Phẩm Của Người Bán</a></li>
                    <li class="{{ setActive(['admin.seller-pending-products.index']) }}"><a class="nav-link"
                            href="{{ route('admin.seller-pending-products.index') }}">Sản Phẩm Đang Chờ Duyệt</a></li>

                </ul>
            </li>

            <li class="dropdown {{ setActive(['admin.vendor-profile.*']) }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-briefcase"></i>
                    <span>Quản Lý Bán Hàng</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ setActive(['admin.vendor-profile.*']) }}"><a class="nav-link"
                            href="{{ route('admin.vendor-profile.index') }}">Thông Tin Gian Hàng</a></li>
                    {{-- <li class="{{ setActive(['admin.sub-category.*']) }}"><a class="nav-link"
                            href="{{ route('admin.sub-category.index') }}">Danh mục cấp 2</a></li>
                    <li class="{{ setActive(['admin.child-category.*']) }}"><a class="nav-link"
                            href="{{ route('admin.child-category.index') }}">Danh mục cấp 3</a></li> --}}
                </ul>
            </li>

        </ul>

    </aside>
</div>
