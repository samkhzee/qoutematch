@extends('admin.layouts.app')
@section('panel')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <form action="#" method="POST">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="fw-bold">@lang('App Id') </label>
                                <input type="text" class="form-control" placeholder="@lang('App Id')" name="app_id" value="{{ gs()->pusher_config->app_id }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="fw-bold">@lang('App Key') </label>
                                <input type="text" class="form-control" placeholder="@lang('App Key')" name="app_key" value="{{ gs()->pusher_config->app_key }}" required>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="fw-bold">@lang('App Secret Key') </label>
                                <input type="text" class="form-control" placeholder="@lang('App Secret Key')" name="app_secret_key" value="{{ gs()->pusher_config->app_secret_key }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="fw-bold">@lang('Cluster') </label>
                                <input type="text" class="form-control" placeholder="@lang('Cluster')" name="cluster" value="{{ gs()->pusher_config->cluster }}" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                </div>
            </form>
        </div><!-- card end -->
    </div>
</div>
@endsection

