<?php

namespace App;

use App\Http\Controllers\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;

class BoardChapter extends Model
{
    use HasSlug;
    // protected $appends = ['name_institute'];
    protected $table = 'board_chapters';

    public static function boot()
    {
        parent::boot();
        BoardChapter::observe(new \App\Observers\UserActionsObserver);
    }

    public static function getRecordWithSlug($slug)
    {
        return BoardChapter::where('id', '=', $slug)->first();
    }

    public function board_subject()
    {
        return $this->belongsTo('App\BoardSubject');
    }

     public function subject()
    {
        return $this->belongsTo(BoardSubject::class, 'subject_id')->withDefault();
    }
}
