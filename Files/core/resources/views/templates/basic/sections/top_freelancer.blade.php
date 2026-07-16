@php
    $content = getContent('top_freelancer.content', true)->data_values;
    $counterElement = getContent('counter.element', false, 3, true);
    $topHundredFreelancers = App\Models\User::active()
        ->orderBy('earning', 'DESC')
        ->orderByDesc('users.avg_rating')
        ->with([
            'projects' => function ($query) {
                $query->where('status', Status::PROJECT_COMPLETED);
            },
            'skills',
            'badge',
        ])
        ->take(100)
        ->get();

@endphp
<div class="best-freelancer-section py-120 my-120">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-heading style-left highlight">
                    <h2 class="section-heading__title s-highlight" data-s-break="-1" data-s-length="1">
                        {{ __(@$content->heading) }}</h2>
                    <p class="section-heading__desc"> {{ __(@$content->subheading) }}</p>
                </div>
            </div>
        </div>

        @if ($topHundredFreelancers->count())
            <div class="best-freelancer">
                @foreach ($topHundredFreelancers ?? [] as $freelancer)
                    @include('Template::partials.freelancer')
                @endforeach
            </div>
        @endif
        <div class="counter-up-wrapper">
            <div class="counterup-item ">
                @forelse ($counterElement as $counter)
                    <div class="counterup-item__content">
                        <div class="counterup-wrapper">
                            <span class="counterup-item__icon">
                                @php  echo @$counter->data_values->icon; @endphp
                            </span>
                            <div class="content">
                                <div class="counterup-item__number">
                                    <h5 class="counterup-item__title"><span class="odometer"
                                            data-odometer-final="{{ __(@$counter->data_values->digit) }}"></span>
                                        @if ($loop->iteration == 3)
                                            @lang('Minute')
                                        @else
                                            @lang('Million')
                                        @endif
                                    </h5>
                                </div>
                                <span class="counterup-item__text mb-0">
                                    {{ __(@$counter->data_values->content) }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    @include('Template::partials.empty', ['message' => 'Facilities not found!'])
                @endforelse
            </div>
        </div>
    </div>
</div>
