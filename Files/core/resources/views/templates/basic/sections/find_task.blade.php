@php
    $content = getContent('find_task.content', true)->data_values;
    $taskElement = getContent('find_task.element', false, 4, true);
@endphp

<div class="work-section my-120">
    <div class="container">
        <div class="work-container">
            <div class="work-container__content highlight">
                <h5 class="work-container__subtitle"> {{ __(@$content->subtitle) }} </h5>
                <h2 class="work-container__title s-highlight" data-s-break="-2" data-s-length="2">
                    {{ __(@$content->heading) }} </h2>
                <p class="work-container__desc"> {{ __(@$content->subheading) }} </p>
                <ul class="list">
                    @forelse ($taskElement as $item)
                        <li class="list-item"> {{ __(@$item->data_values->find_step) }} </li>
                    @empty
                        <div class="d-flex flex-column justify-content-start align-items-start ">
                            <div class="text-center">
                                <img src="{{ asset(activeTemplate(true) . 'images/empty.png') }}" alt="empty"
                                    class="img-fluid">
                                <h6 class="text-white mt-3">@lang('Choose us data not found')</h6>
                            </div>
                        </div>
                    @endforelse
                </ul>
                <a href="{{ route('freelance.jobs') }}" class="btn btn--base btn--lg">
                    {{ __(@$content->button_name) }}</a>
            </div>
            <div class="work-container__thumb">
                <img src="{{ frontendImage('find_task', @$content->image, '510x780') }}" alt="">
            </div>
            <div class="work-container__shape">
                <img src="{{ frontendImage('find_task', @$content->shape, '190x420') }}" alt="">
            </div>
        </div>
    </div>
</div>
