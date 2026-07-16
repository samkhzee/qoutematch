@extends('Template::layouts.frontend')
@section('content')
    <section class="container py-120">
        <div class="row gy-4 justify-content-center">
            @forelse($blogs ?? [] as $blog)
                <div class="col-xl-4 col-sm-6">
                    <a href="{{ route('blog.details', $blog->slug) }}" class="blog-item">
                        <div class="blog-item__thumb">
                            <img src="{{ frontendImage('blog', 'thumb_' . @$blog->data_values->image, '485x300') }}"
                                class="fit-image" alt="">
                        </div>
                        <div class="blog-item__content">
                            <h6 class="blog-item__title">
                                {{ __(strLimit(@$blog->data_values->title, 80)) }}
                            </h6>
                            <ul class="text-list flex-align">
                                <li class="text-list__item">
                                    {{ showDateTime($blog->created_at, 'd M, Y') }}
                                </li>
                            </ul>
                        </div>
                    </a>
                </div>
                @if ($blogs->hasPages())
                    <div class="col-12">
                        {{ paginateLinks($blogs) }}

                    </div>
                @endif
            @empty
                <div class="d-flex flex-column justify-content-center align-items-center ">
                    <div class="text-center">
                        <img src="{{ asset(activeTemplate(true) . 'images/empty.png') }}" alt="empty" class="img-fluid">
                        <h6 class="text-muted mt-3">@lang('Blogs not found')</h6>
                    </div>
                </div>
            @endforelse
        </div>
        @if ($sections->secs != null)
            <div class="pt-120">
                @foreach (json_decode($sections->secs) as $sec)
                    @include('Template::sections.' . $sec)
                @endforeach
            </div>
        @endif
    </section>
@endsection
