<section id="wsus__flash_sell" class="wsus__flash_sell_2">
    <div class=" container">
        <div class="row">
            <div class="col-xl-12">
                <div class="offer_time" style="background: url({{ asset('flashsale.png') }})">
                    <div class="wsus__flash_coundown">
                        <span class=" end_text">flash sell</span>
                        <div class="simply-countdown simply-countdown-example"></div>
                        <a class="common_btn" href="{{ route('flash-sale') }}">Xem thêm <i
                                class="fas fa-caret-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row flash_sell_slider">

            @foreach ($flashSaleItems as $item)
                @php
                    $product = \App\Models\Product::find($item->product_id);
                @endphp

                @if (checkDiscount($product))
                    <div class="col-xl-3 col-sm-6 col-lg-4">
                        <div class="wsus__product_item">
                            <span class="wsus__new">{{ productType($product) }}</span>
                            <span class="wsus__minus">-{{ calculateDiscountPercent($product) }}%</span>
                            <a class="wsus__pro_link" href="{{ route('product-detail', $product->slug) }}">
                                <img src="{{ asset($product->thumb_image) }}" alt="product"
                                    class="img-fluid w-100 img_1" />
                                <img src="
                            @if (isset($product->productImageGalleries[0]->image)) {{ asset($product->productImageGalleries[0]->image) }}
                            @else
                            {{ asset($product->thumb_image) }} @endif
                          "
                                    alt="product" class="img-fluid w-100 img_2" />
                            </a>
                            <ul class="wsus__single_pro_icon">
                                <li><a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal"><i
                                            class="far fa-eye"></i></a></li>
                                <li><a href="#"><i class="far fa-heart"></i></a></li>
                                <li><a href="#"><i class="far fa-random"></i></a>
                            </ul>
                            <div class="wsus__product_details">
                                <a class="wsus__category" href="">{{ $product->category->name }}
                                </a>
                                <p class="wsus__pro_rating">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                    <span>(133 review)</span>
                                </p>
                                <a class="wsus__pro_name"
                                    href="{{ route('product-detail', $product->slug) }}">{{ $product->name }}</a>
                                @if (checkDiscount($product))
                                    <p class="wsus__price mt-2">{{ number_format($product->offer_price) }} đ
                                        <del>{{ number_format($product->price) }} đ</del>
                                    </p>
                                @else
                                    <p class="wsus__price mt-2">{{ number_format($product->price) }} đ</p>
                                @endif
                                <a class="add_cart" href="#">add to cart</a>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach

        </div>
    </div>
</section>

@push('scripts')
    <script>
        $(document).ready(function() {

            simplyCountdown(".simply-countdown-example", {
                year: {{ date('Y', strtotime($flashSaleDate->end_date)) }},
                month: {{ date('m', strtotime($flashSaleDate->end_date)) }},
                day: {{ date('d', strtotime($flashSaleDate->end_date)) }},
                hours: {{ date('H', strtotime($flashSaleDate->end_date)) }},
                minutes: {{ date('i', strtotime($flashSaleDate->end_date)) }},
                enableUtc: false,
            });
        })
    </script>
@endpush
