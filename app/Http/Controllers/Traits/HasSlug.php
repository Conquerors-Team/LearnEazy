<?php
namespace App\Http\Controllers\Traits;

use Illuminate\Support\Str;

trait HasSlug
{
    public static function makeSlug($string)
    {
        $slug = Str::slug($string);
        // Get current model
        $model = new static;

        // Check for existing slugs
        $count = $model->whereRaw("slug LIKE '{$slug}%'")->count();

        // Append count if needed
        return $count ? "{$slug}-{$count}" : $slug;
    }
}
