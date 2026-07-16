

<div class="freelancer-item">
    @if ($freelancer->badge)
        <span class="freelancer-item__status text--base fs-12">
            <i class="las la-award"></i> {{ __($freelancer->badge->badge_name) }}
        </span>
    @endif

    <div class="freelancer-item__thumb">
        <img src="{{ getImage(getFilepath('userProfile') . '/' . $freelancer->image, avatar: true) }}" alt="{{ __($freelancer->fullname) }}">
    </div>

    <div class="freelancer-item__content">
        <h6 class="freelancer-item__name d-flex align-items-center flex-wrap gap-1">
            {{ __($freelancer->fullname) }}
            @include('Template::partials.verification_badges', ['user' => $freelancer, 'compact' => true])
        </h6>
        <span class="freelancer-item__designation">
            {{ strLimit(__($freelancer->tagline), 30) }}
        </span>

      @if ($freelancer->avg_rating > 0) 
          <ul class="text-list review-rating-list mb-0">
              @for ($i = 0; $i < min($freelancer->avg_rating, 5); $i++)
                  <li class="review-rating-list__item"> <i class="las la-star"></i> </li>
              @endfor
  
              <li class="text-list__item">
                  {{ $freelancer->avg_rating }}/5
              </li>
          </ul>
  
      @endif
        <ul class="skill-list">
            @foreach ($freelancer->skills as $skill)
                <li class="skill-list__item">
                    <span class="skill-list__link">{{ __($skill->name) }}</span>
                </li>
            @endforeach
        </ul>

        <div class="freelancer-item__btn">
            <a href="{{ route('talent.explore', $freelancer->username) }}" class="btn--base btn btn--sm">
                @lang('View Profile')
            </a>
        </div>
    </div>
</div>


