<ul class="nav nav-tabs mb-4 topTap breadcrumb-nav" role="tablist">
    <button class="breadcrumb-nav-close"><i class="las la-times"></i></button>
    <li class="nav-item {{ menuActive(['admin.category.index']) }}" role="presentation">
        <a href="{{ route('admin.category.index') }}" class="nav-link text-dark" type="button">
            <i class="las la-bezier-curve"></i> @lang('Categories')
        </a>
    </li>
    <li class="nav-item {{ menuActive(['admin.category.subcategories']) }}" role="presentation">
        <a href="{{ route('admin.category.subcategories') }}" class="nav-link text-dark" type="button">
            <i class="las la-stream"></i> @lang('Subcategories')
        </a>
    </li>
    <li class="nav-item {{ menuActive(['admin.category.skills']) }}" role="presentation">
        <a href="{{ route('admin.category.skills') }}" class="nav-link text-dark" type="button">
            <i class="las la-rainbow"></i> @lang('Skills')
        </a>
    </li>
    <li class="nav-item {{ menuActive(['admin.marketplace.forms*']) }}" role="presentation">
        <a href="{{ route('admin.marketplace.forms.index') }}" class="nav-link text-dark" type="button">
            <i class="las la-wpforms"></i> @lang('Form Builder')
        </a>
    </li>
</ul>
