@php
    $content = getContent('blog.content', true)->data_values;
    $blogElement = getContent('blog.element', false, 6);
@endphp

<section class="blog my-120">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-heading two">
                    <h2 class="section-heading__title s-highlight" data-s-break="-2" data-s-length="2">
                        {{ __(@$content->heading) }}</h2>
                    <p class="section-heading__desc">{{ __(@$content->subheading) }}</p>
                </div>
            </div>
        </div>
        <div class="row gy-4 justify-content-center">
            @foreach ($blogElement ?? [] as $blog)
                <div class="col-xl-4 col-sm-6">
                    <a href="{{ route('blog.details', $blog->slug) }}" class="blog-item">
                        <div class="blog-item__thumb">
                            <img src="{{ frontendImage('blog', 'thumb_' . @$blog->data_values->image, '485x300') }}" class="fit-image" alt="">
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
            @endforeach
        </div>
    </div>
</section>
