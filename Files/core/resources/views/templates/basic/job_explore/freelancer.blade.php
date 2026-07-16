@forelse ($similarFreelancers as $freelancer)
    @php
        $freelancerJobs = $freelancer->projects->count();
        $freelancerSuccessJobs = $freelancer->projects->where('status', Status::PROJECT_COMPLETED)->count();
        $freelancerSuccessJobPercent = $freelancerJobs > 0 ? ($freelancerSuccessJobs / $freelancerJobs) * 100 : 0;

        $transactionsQuery = App\Models\Transaction::where('user_id', $freelancer->id);
        $totalEarnings = $transactionsQuery->sum('amount');
    @endphp

    <div class="bid-item">
        <a href="{{ route('talent.explore', $freelancer->username) }}" class="bid-item__thumb">
            <img src="{{ getImage(getFilePath('userProfile') . '/' . $freelancer->image, avatar: true) }}" alt="">
        </a>
        <div class="bid-item__content">
            <div class="bid-item__top">
                <div class="w-100">
                    <div class="d-flex justify-content-between mx-auto align-items-center">
                        <p class="bid-item__name mb-0 d-flex align-items-center flex-wrap gap-1">
                            {{ __($freelancer->fullname) }}
                            @include('Template::partials.verification_badges', ['user' => $freelancer, 'compact' => true])
                        </p>
                        <a href="{{ route('talent.explore', $freelancer->username) }}"
                            class="btn btn--base btn--xsm">@lang('View Profile')</a>
                    </div>
                    <div class="d-flex aligns-items-center gap-2 justify-content-start flex-wrap my-2">
                        <div class="location">
                            <p class="text"> <i class="las la-globe"></i>{{ __($freelancer->country_name) }}
                            </p>
                        </div>
                        <p class="text">
                            <span class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" viewBox="0 0 229.5 229.5"
                                    style="enable-background:new 0 0 512 512" xml:space="preserve" class="">
                                    <g>
                                        <path
                                            d="M214.419 32.12A7.502 7.502 0 0 0 209 25.927L116.76.275a7.496 7.496 0 0 0-4.02 0L20.5 25.927a7.5 7.5 0 0 0-5.419 6.193c-.535 3.847-12.74 94.743 18.565 139.961 31.268 45.164 77.395 56.738 79.343 57.209a7.484 7.484 0 0 0 3.522 0c1.949-.471 48.076-12.045 79.343-57.209 31.305-45.217 19.1-136.113 18.565-139.961zm-40.186 53.066-62.917 62.917c-1.464 1.464-3.384 2.197-5.303 2.197s-3.839-.732-5.303-2.197l-38.901-38.901a7.497 7.497 0 0 1 0-10.606l7.724-7.724a7.5 7.5 0 0 1 10.606 0l25.874 25.874 49.89-49.891a7.497 7.497 0 0 1 10.606 0l7.724 7.724a7.5 7.5 0 0 1 0 10.607z"
                                            fill="currentColor" opacity="1" data-original="#000000" class="" />
                                    </g>
                                </svg>
                            </span>
                            <span> {{ showAmount($freelancerSuccessJobPercent, currencyFormat: false) }}%
                                @lang('Job Success')</span>
                        </p>
                        <span class="text">
                            <span class="icon">
                                <i class="las la-hand-holding-usd"></i>
                            </span>
                            @lang('Total Earned') {{ formatNumber($totalEarnings) }}
                        </span>
                        <span class="text">
                            <span class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" aria-hidden="true"
                                    viewBox="0 0 24 24" role="img">
                                    <path vector-effect="non-scaling-stroke" stroke="var(--icon-color, #001e00)"
                                        stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M12.99 3L6 13.06h5.28L10.17 21l6.99-10.06h-5.28L12.99 3z" fill="currentColor"
                                        opacity="1"></path>
                                </svg>
                            </span>
                        </span>
                        @if ($freelancer->badge)
                            <span class="text">
                                <span class="icon">
                                    <i class="las la-award"></i>
                                </span>
                                {{ __($freelancer->badge->badge_name) }}
                            </span>
                        @endif
                    </div>
                    <div class="freelancer-title">{{ __($freelancer->tagline) }}</div>
                    <ul class="review-rating-list">

                        <ul class="review-rating-list">
                            @for ($i = 0; $i < min($freelancer->avg_rating, 5); $i++)
                                <li class="review-rating-list__item"> <i class="las la-star"></i> </li>
                            @endfor
                        </ul>


                        <li class="rating-list__number"> ({{ getAmount($freelancer->reviews_count) }}
                            @lang('reviews'))
                        </li>

                    </ul>
                </div>
            </div>
            <p class="bid-item__desc">
                {{ strLimit(strip_tags(@$freelancer->about), 190) }}
            </p>
        </div>
    </div>
@empty
    <div class="empty-message text-center py-5">
        <img src="{{ asset(activeTemplate(true) . 'images/empty.png') }}" alt="empty">
        <p class="text-muted mt-3">@lang('No freelancer found!')</p>
    </div>
@endforelse
