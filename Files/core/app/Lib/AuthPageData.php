<?php

namespace App\Lib;

use App\Models\Category;
use App\Models\Subcategory;

class AuthPageData
{
    public static function register(): array
    {
        $register = getContent('register.content', true);
        $banner = getContent('banner.content', true);
        $switching = getContent('switching_button.content', true);

        return [
            'heading' => __(@$register?->data_values->heading ?: 'Create your account'),
            'image' => frontendImage('register', @$register?->data_values->image, '770x670'),
            'bannerShape' => frontendImage('banner', @$banner?->data_values->shape, '475x630'),
            'providerLabel' => __(@$switching?->data_values->freelancer_register_button ?: 'Join as Provider'),
            'customerLabel' => __(@$switching?->data_values->buyer_register_button ?: 'Join as Customer'),
        ];
    }

    public static function categoryOptions(): array
    {
        return Category::active()
            ->with(['subcategories' => fn ($q) => $q->active()->orderBy('name')])
            ->orderBy('name')
            ->get()
            ->map(fn ($category) => [
                'id' => $category->id,
                'name' => __($category->name),
                'subcategories' => $category->subcategories->map(fn ($sub) => [
                    'id' => $sub->id,
                    'name' => __($sub->name),
                ])->values()->all(),
            ])->values()->all();
    }
}
