@extends('admin.layouts.master')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Quản Lý Sản Phẩm</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Quản Lý Sản Phẩm</a></div>
                <div class="breadcrumb-item"><a href="#">Thương Hiệu</a></div>
            </div>
        </div>

        <div class="section-body">
            <h2 class="section-title">Thương Hiệu</h2>
            <p class="section-lead">Tuỳ chỉnh thương hiệu của sản phẩm.</p>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Danh sách thương hiệu</h4>
                            <div class="card-header-action">
                                <a href="{{ route('admin.brand.create') }}" class="btn btn-primary mb-3">+ Thêm mới</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                {{ $dataTable->table(['class' => 'table nowrap', 'style' => 'width: 100%;']) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}

    <script>
        $(document).ready(function() {
            $("body").on('click', ".change-status", function() {
                let isChecked = $(this).is(":checked")
                let id = $(this).data('id')

                $.ajax({
                    url: "{{ route('admin.brand.change-status') }}",
                    method: "PUT",
                    data: {
                        id: id,
                        status: isChecked
                    },
                    success: function(data) {
                        toastr.success(data.message)
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                })
            })
        })
    </script>
@endpush
