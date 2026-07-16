@php
    $howWork = getContent('how_work.content', true)->data_values;
    $workElement = getContent('how_work.element', false, null, true);
@endphp
<div class="how-wowrk-section my-120">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="section-heading two">
                    <h2 class="section-heading__title s-highlight" data-s-break="-2" data-s-length="2">
                        {{ __(@$howWork->heading) }}</h2>
                    <p class="section-heading__desc"> {{ __(@$howWork->subheading) }}</p>
                </div>
            </div>
        </div>
        <div class="row gy-4">
            @forelse ($workElement as $work)
                <div class="col-lg-3 col-sm-6">
                    <div class="how-work-item">
                        <span class="how-work-item__icon">
                            @php echo @$work->data_values->icon @endphp
                        </span>
                        <div class="how-work-item__content">
                            <h5 class="how-work-item__title"> {{ __(@$work->data_values->title) }} </h5>
                            <p class="how-work-item__desc"> {{ __(@$work->data_values->content) }} </p>
                        </div>
                        <div class="how-work-item__shape">
                            <img src="{{ asset(activeTemplate(true) . 'shape/how-work.png') }}" alt="">
                        </div>
                    </div>
                </div>
            @empty
                @include('Template::partials.empty', ['message' => 'Work process not found!'])
            @endforelse
        </div>
    </div>
</div>
