<!--============================
    HEADER START
==============================-->
<header>
    <div class="container">
        <div class="row">
            <div class="col-2 col-md-1 d-lg-none">
                <div class="wsus__mobile_menu_area">
                    <span class="wsus__mobile_menu_icon"><i class="fal fa-bars"></i></span>
                </div>
            </div>
            <div class="col-xl-2 col-7 col-md-8 col-lg-2">
                <div class="wsus_logo_area">
                    <a class="wsus__header_logo" href="{{ route('home') }}">
                        <img src="{{ asset('logo.png') }}" alt="logo" width="100px" class=" w-75 ">
                    </a>
                </div>
            </div>
            <div class="col-xl-5 col-md-6 col-lg-4 d-none d-lg-block mt-lg-3">
                <div class="wsus__search">
                    <form action="{{ route('product.index') }}">
                        <input type="text" name="search" placeholder="Tìm kiếm sản phẩm..."
                            value="{{ request()->search }}">
                        <button type="submit"><i class="far fa-search"></i></button>
                    </form>
                </div>
            </div>
            <div class="col-xl-5 col-3 col-md-3 col-lg-6 mt-lg-3">
                <div class="wsus__call_icon_area">
                    <div class="wsus__call_area">
                        <div class="wsus__call">
                            <i class="fas fa-user-headset"></i>
                        </div>
                        <div class="wsus__call_text">
                            <p>megamart@gmail.com</p>
                            <p>1800-1234</p>
                        </div>
                    </div>
                    <ul class="wsus__icon_area">
                        <li><a href="{{ route('wishlist.index') }}"><i class="fal fa-heart"></i><span
                                    id="wishlist-count">{{ Auth::check() ? \App\Models\Wishlist::where('user_id', Auth::user()->id)->count() : 0 }}</span></a>
                        </li>
                        <li><a href="compare.html"><i class="fal fa-random"></i><span>03</span></a></li>
                        <li><a class="wsus__cart_icon" href="#"><i class="fal fa-shopping-bag"></i><span
                                    id="cart-count">{{ Cart::content()->count() }}</span></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="wsus__mini_cart">
        <h4>Giỏ hàng <span class="wsus_close_mini_cart"><i class="far fa-times"></i></span></h4>
        <ul class="mini-cart-wrapper">

            @foreach (Cart::content() as $product)
                <li id="mini-cart-{{ $product->rowId }}" style="display: flex; align-items: center">
                    <div class="wsus__cart_img">
                        <a href="#"><img src="{{ asset($product->options->image) }}" alt="product"
                                class="img-fluid w-100"></a>
                        <a class="wsis__del_icon remove-sidebar-product" data-id="{{ $product->rowId }}"
                            href="#"><i class="fas fa-minus-circle"></i></a>
                    </div>
                    <div class="wsus__cart_text">
                        <a class="wsus__cart_title"
                            href="{{ route('product-detail', $product->options->slug) }}">{{ $product->name }}</a>
                        <br>
                        @foreach ($product->options->variants as $key => $variant)
                            <small>{{ $key }}: {{ $variant['name'] }}</small><br>
                        @endforeach
                        <small>Số lượng: {{ $product->qty }}</small>
                        <p>{{ number_format($product->price + $product->options->variants_total) }}đ</p>
                    </div>
                </li>
            @endforeach

            @if (Cart::content()->count() == 0)
                <li style="text-align: center">
                    Giỏ hàng đang trống
                </li>
            @endif

        </ul>

        <div class="mini-cart-action {{ Cart::content()->count() === 0 ? 'd-none' : '' }}">
            <h5>Tổng tiền <span id="mini-cart-subtotal">{{ number_format(getCartTotal()) }}đ</span></h5>
            <div class="wsus__minicart_btn_area">
                <a class="common_btn" href="{{ route('cart-details') }}">Xem giỏ hàng</a>
                <a class="common_btn" href="check_out.html">checkout</a>
            </div>
        </div>

    </div>

</header>
<!--============================
  HEADER END
==============================-->
