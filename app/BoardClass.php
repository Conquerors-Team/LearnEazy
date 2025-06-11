<?php

namespace App;

use App\Http\Controllers\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;

class BoardClass extends Model
{
    use HasSlug;
    // protected $appends = ['name_institute'];
    protected $table = 'board_classes';

    public static function boot()
    {
        parent::boot();
        BoardClass::observe(new \App\Observers\UserActionsObserver);
    }

    public static function getRecordWithSlug($slug)
    {
        return BoardClass::where('slug', '=', $slug)->first();
    }

    public function subjects()
    {
        return $this->belongsToMany(BoardSubject::class, 'board_classes_subjects');
    }
}
