@extends('Template::layouts.frontend')
@section('content')
    <div class="job-category-section mb-120 mt-60">
        <div class="container">
            <div class="job-category-wrapper">
                <div class="category-sidebar">
                    <span class="sidebar-filter__close d-xl-none d-flex"><i class="las la-times"></i></span>
                    <div class="accordion sidebar--acordion">
                        <div class="filter-block">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" data-bs-toggle="collapse" data-bs-target="#budget"
                                            type="button" aria-expanded="true">
                                        @lang('Budget')
                                    </button>
                                </h2>
                                <div class="accordion-collapse show collapse" id="budget">
                                    <div class="accordion-body">
                                        <ul class="filter-block__list">
                                            <li class="filter-block__item">
                                                <div class="project-value">
                                                    <input class="form--control" name="min_budget" type="number"
                                                           placeholder="@lang('Min')">
                                                    <span class="project-value__text"> @lang('to') </span>
                                                    <input class="form--control" name="max_budget" type="number"
                                                           placeholder="@lang('Max')">
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="filter-block">
                            <h2 class="accordion-header">
                                <button class="accordion-button" data-bs-toggle="collapse" data-bs-target="#category"
                                        type="button" aria-expanded="true">
                                    @lang('Categories')
                                </button>
                            </h2>
                            <div class="accordion-collapse show collapse" id="category">
                                <div class="accordion-body">
                                    <ul class="filter-block__list category">
                                        <li class="filter-block__item">
                                            <div class="form--check">
                                                <input class="form-check-input" id="subcat_all"
                                                       name="category_id" type="radio" value=""
                                                       @checked(!request()->category_id)>
                                                <label class="form-check-label" for="subcat_all">
                                                    <span class="label-text"> @lang('All') </span>
                                                    <span class="label-text"> ({{ $categories->sum('jobs_count') }}) </span>
                                                </label>
                                            </div>
                                        </li>
                                        @forelse ($categories as $category)
                                            <li class="filter-block__item">
                                                <div class="form--check">
                                                    <input class="form-check-input" id="subcat_{{ $category->id }}"
                                                           name="category_id" type="radio" value="{{ $category->id }}"
                                                           @checked($category->id == request()->category_id)>
                                                    <label class="form-check-label" for="subcat_{{ $category->id }}">
                                                        <span class="label-text"> {{ __($category->name) }} </span>
                                                        <span class="label-text"> ({{ $category->jobs_count }}) </span>
                                                    </label>
                                                </div>
                                            </li>
                                        @empty
                                            <li class="filter-block__item"> @lang('Categories not found!')</li>
                                        @endforelse
                                        <li class="load-more-button text-end">@lang('Show more')</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="filter-block">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" data-bs-toggle="collapse" data-bs-target="#subcategory"
                                            type="button" aria-expanded="true">
                                        @lang('Specialities')
                                    </button>
                                </h2>
                                <div class="accordion-collapse show collapse" id="subcategory">
                                    <div class="accordion-body">
                                        <ul class="filter-block__list">
                                            @forelse ($subcategories as $subcategory)
                                                <li class="filter-block__item">
                                                    <div class="form--check">
                                                        <input class="form-check-input" id="sub_{{ $subcategory->id }}"
                                                               name="subcategory_id" type="checkbox"
                                                               value="{{ $subcategory->id }}">
                                                        <label class="form-check-label" for="sub_{{ $subcategory->id }}">
                                                            <span class="label-text">{{ __($subcategory->name) }}</span>
                                                            <span class="label-text"> ({{ $subcategory->jobs_count }})
                                                            </span>
                                                        </label>
                                                    </div>
                                                </li>
                                            @empty
                                                <li class="filter-block__item"> @lang('Speciality not found!')</li>
                                            @endforelse
                                            <li class="load-more-button text-end">@lang('Show more')</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="filter-block">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" data-bs-toggle="collapse" data-bs-target="#scope"
                                            type="button" aria-expanded="true">
                                        @lang('Project Scope')
                                    </button>
                                </h2>
                                <div class="accordion-collapse show collapse" id="scope">
                                    <div class="accordion-body">
                                        <ul class="filter-block__list">
                                            <li class="filter-block__item">
                                                <div class="form--check">
                                                    <input class="form-check-input" id="large" name="project_scope[]"
                                                           type="checkbox" value="{{ Status::SCOPE_LARGE }}">
                                                    <label class="form-check-label" for="large">
                                                        <span class="label-text"> @lang('Large') </span>
                                                        <span class="label-text"> ({{ $counting['large'] }}) </span>
                                                    </label>
                                                </div>
                                            </li>
                                            <li class="filter-block__item">
                                                <div class="form--check">
                                                    <input class="form-check-input" id="medium" name="project_scope[]"
                                                           type="checkbox" value="{{ Status::SCOPE_MEDIUM }}">
                                                    <label class="form-check-label" for="medium">
                                                        <span class="label-text"> @lang('Medium') </span>
                                                        <span class="label-text"> ({{ $counting['medium'] }}) </span>
                                                    </label>
                                                </div>
                                            </li>
                                            <li class="filter-block__item">
                                                <div class="form--check">
                                                    <input class="form-check-input" id="small" name="project_scope[]"
                                                           type="checkbox" value="{{ Status::SCOPE_SMALL }}">
                                                    <label class="form-check-label" for="small">
                                                        <span class="label-text"> @lang('Small') </span>
                                                        <span class="label-text"> ({{ $counting['small'] }}) </span>
                                                    </label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="filter-block">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" data-bs-toggle="collapse" data-bs-target="#level"
                                            type="button" aria-expanded="true">
                                        @lang('Experience Level')
                                    </button>
                                </h2>
                                <div class="accordion-collapse show collapse" id="level">
                                    <div class="accordion-body">
                                        <ul class="filter-block__list">
                                            <li class="filter-block__item">
                                                <div class="form--check">
                                                    <input class="form-check-input" id="pro-level" name="skill_level[]"
                                                           type="checkbox" value="1">
                                                    <label class="form-check-label" for="pro-level">
                                                        <span class="label-text"> @lang('Pro Level')</span>
                                                        <span class="label-text"> ({{ $counting['pro'] }}) </span>
                                                    </label>
                                                </div>
                                            </li>
                                            <li class="filter-block__item">
                                                <div class="form--check">
                                                    <input class="form-check-input" id="expart" name="skill_level[]"
                                                           type="checkbox" value="2">
                                                    <label class="form-check-label" for="expart">
                                                        <span class="label-text"> @lang('Expart') </span>
                                                        <span class="label-text"> ({{ $counting['expert'] }}) </span>
                                                    </label>
                                                </div>
                                            </li>
                                            <li class="filter-block__item">
                                                <div class="form--check">
                                                    <input class="form-check-input" id="intermediate"
                                                           name="skill_level[]" type="checkbox" value="3">
                                                    <label class="form-check-label" for="intermediate">
                                                        <span class="label-text">@lang('Intermediate') </span>
                                                        <span class="label-text"> ({{ $counting['intermediate'] }})
                                                        </span>
                                                    </label>
                                                </div>
                                            </li>
                                            <li class="filter-block__item">
                                                <div class="form--check">
                                                    <input class="form-check-input" id="entry" name="skill_level[]"
                                                           type="checkbox" value="4">
                                                    <label class="form-check-label" for="entry">
                                                        <span class="label-text">@lang('Entry') </span>
                                                        <span class="label-text"> ({{ $counting['entry'] }}) </span>
                                                    </label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="job-category-body">
                    <div class="job-category-body__bar d-xl-none d-block">
                        <span class="job-category-body__bar-icon"><i class="las la-list"></i></span>
                    </div>
                    <div class="job-category-body__top">
                        <div class="search-container">
                            <input class="form--control" name="search" type="search" value="{{ request()->search }}"
                                   placeholder="@lang('Type job keyword')" autocomplete="off">
                            <span class="search-container__icon"> <i class="las la-search"></i> </span>
                        </div>
                    </div>
                    <div class="job-category-body__content">
                        @include('Template::job_explore.job')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            "use strict";

            function getFilters() {
                return {
                    min_budget: $('input[name="min_budget"]').val(),
                    max_budget: $('input[name="max_budget"]').val(),
                    category_id: $('input[name="category_id"]:checked').val(),
                    subcategory_id: $('input[name="subcategory_id"]:checked').map(function() {
                        return $(this).val();
                    }).get(),
                    project_scope: $('input[name="project_scope[]"]:checked').map(function() {
                        return $(this).val();
                    }).get(),
                    skill_level: $('input[name="skill_level[]"]:checked').map(function() {
                        return $(this).val();
                    }).get(),
                    search: $('input[name="search"]').val(),
                };
            }

            function showSkeletonLoading(jobCount = 5) {
                let skeletonHTML = '<div class="skeleton-loading">';
                let rows = Math.max(jobCount, 5);
                for (let i = 0; i < rows; i++) {
                    skeletonHTML += `
                    <div class="skeleton-loading">
                        <div class="skeleton-line"></div>
                        <div class="skeleton-line"></div>
                        <div class="skeleton-line"></div>
                        <div class="skeleton-line"></div>
                        <div class="skeleton-line"></div>
                        <div class="skeleton-line"></div>
                    </div> `;
                }
                skeletonHTML += '</div>';
                $('.job-category-body__content').html(skeletonHTML);
            }

            function fetchJobs(page = null) {
                let filters = getFilters();
                if (page) {
                    filters.page = page;
                }

                $.ajax({
                    url: "{{ route('freelance.filter.jobs') }}",
                    method: "GET",
                    data: filters,
                    beforeSend: function() {
                        showSkeletonLoading(filters.jobCount || 5);
                    },
                    success: function(response) {
                        $('.job-category-body__content').fadeOut(300, function() {
                            $(this).html(response.data.html).fadeIn(300);
                        });
                    },
                    error: function(xhr, status, error) {
                        $('.job-category-body__content').html(
                            `<div class="text-center py-5 text-danger">Failed to load data. Please try again.</div>`
                        );
                    }
                });
            }

            let delayTimer;
            $('.filter-block__list input, .search-container input').on('change keyup', function() {
                clearTimeout(delayTimer);
                delayTimer = setTimeout(function() {
                    fetchJobs();
                }, 500);
            });

            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                let page = $(this).attr('href').split('page=')[1];
                fetchJobs(page);
            });
        });
    </script>
@endpush


@push('style')
    <style>
        .job-category-wrapper .job-category-body__content:has(.pagination) .d-none.flex-sm-fill.d-sm-flex.align-items-sm-center.justify-content-sm-between {
            padding: 20px;
        }

        .job-category-wrapper .job-category-body__content .pagination {
            border: 1px solid hsl(var(--black)/.1);
        }

        .job-category-body__content {
            transition: opacity 0.5s ease-in-out;
        }

        .skeleton-loading {
            padding: 20px;
        }

        .skeleton-line {
            height: 20px;
            background: #e0e0e0;
            margin-bottom: 10px;
            border-radius: 4px;
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% {
                opacity: 0.6;
            }

            50% {
                opacity: 1;
            }

            100% {
                opacity: 0.6;
            }
        }

        .job-category-body__content {
            transition: opacity 0.5s ease-in-out;
        }

        .fade-out {
            opacity: 0;
        }

        .fade-in {
            opacity: 1;
        }
    </style>
@endpush
