@php
    $categories = App\Models\Category::active()
        ->where('is_featured', Status::YES)
        ->orderBy('id', 'DESC')
        ->withCount([
            'jobs' => function ($query) {
                $query->published()->approved();
            },
        ])
        ->get();
@endphp

@if ($categories->count())
    <div class="category-section my-120">
        <div class="container">
            <div class="category-slider">
                @foreach ($categories as $category)
                    <div>
                        <a href="{{ route('freelance.jobs', ['category_id' => $category->id]) }}" class="category-item">
                            <div class="category-item__thumb">
                                <img src="{{ getImage(getFilepath('category') . '/' . $category->image, getFileSize('category')) }}" alt="">
                            </div>
                            <div class="category-item__content">
                                <h5 class="category-item__title"> {{ __(@$category->name) }} </h5>
                                <p class="category-item__text"> {{ $category->jobs_count }} @lang('Job') </p>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
