

<div class="custom--dropdown">
    <div class="custom--dropdown__selected dropdown-list__item">
        <div class="thumb"> <img
                src="{{ getImage(getFilePath('language') . '/' . $currentLang->image, getFileSize('language')) }}"
                alt="image">
        </div>
        <span class="text"> {{ __(@$currentLang->name) }} </span>
    </div>
    <ul class="dropdown-list">
        @foreach ($languages as $language)
            @if ($language->id != $currentLang->id)
                <li class="dropdown-list__item langSel" data-code="{{ $language->code }}">
                    <a href="{{ route('lang', $language->code) }}" class="thumb">
                        <img src="{{ getImage(getFilePath('language') . '/' . $language->image, getFileSize('language')) }}"
                            alt="image">
                        <span class="text"> {{ __($language->name) }} </span>
                    </a>
                </li>
            @endif
        @endforeach
    </ul>
</div>
