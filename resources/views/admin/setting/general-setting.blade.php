<div class="tab-pane fade show active" id="list-home" role="tabpanel" aria-labelledby="list-home-list">
    <div class="card">
        <div class="card-body border">
            <form action="{{ route('admin.general-setting-update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Tên website</label>
                    <input type="text" class="form-control" name="site_name" placeholder=""
                        value="{{ old('site_name', $generalSetting->site_name) }}">
                    @if ($errors->has('site_name'))
                        <p class="text-danger">{{ $errors->first('site_name') }}</p>
                    @endif
                </div>
                <div class="form-group">
                    <label>Email liên lạc</label>
                    <input type="email" class="form-control" name="contact_email" placeholder=""
                        value="{{ old('contact_email', $generalSetting->contact_email) }}">
                    @if ($errors->has('contact_email'))
                        <p class="text-danger">{{ $errors->first('contact_email') }}</p>
                    @endif
                </div>
                <button type="submit" class="btn btn-primary">Xác nhận</button>
            </form>
        </div>
    </div>
</div>
