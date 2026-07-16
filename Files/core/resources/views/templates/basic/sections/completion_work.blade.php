@php
    $content = getContent('completion_work.content', true)->data_values;
    $completionWorkElement = getContent('completion_work.element', false, 4, true);
@endphp
<div class="work-completion my-120">
    <div class="container">
        <div class="work-completion-wrapper">
            <div class="work-completion-content highlight">
                <h5 class="work-completion-content__subtitle text--base"> {{ __(@$content->heading) }}</h5>
                <h2 class="work-completion-content__title s-highlight" data-s-break="-1" data-s-length="1">{{ __(@$content->subheading) }}</h2>
                <ul class="list">
                    @forelse ($completionWorkElement as $completionWork)
                        <li class="list-item">{{ __(@$completionWork->data_values->done_step) }}</li>
                    @empty
                        @include('Template::partials.empty', ['message' => 'Done work not found!'])
                    @endforelse
                </ul>
            </div>
            <div class="work-completion-thumb">
                <img src="{{ frontendImage('completion_work', @$content->image, '1165x1190') }}" alt="">
            </div>
        </div>
    </div>
</div>
