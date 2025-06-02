<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BoardSubject extends Model
{
    // protected $appends = ['name_institute'];
    protected $table = 'board_subjects';

    public static function boot()
    {
        parent::boot();
        BoardSubject::observe(new \App\Observers\UserActionsObserver);
    }

    public static function getRecordWithSlug($slug)
    {
        return BoardSubject::where('slug', '=', $slug)->first();
    }

    public function board_chapters() {
        return $this->hasMany(BoardChapter::class, 'subject_id');
    }
}
