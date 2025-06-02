<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class LmsNote extends Model
{

    public static function boot()
    {
        parent::boot();
        LmsNote::observe(new \App\Observers\UserActionsObserver);
    }

    public static function getRecordWithSlug($slug)
    {
        return LmsNote::where('slug', '=', $slug)->first();
    }


    public function subject() {
      return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function chapter() {
      return $this->belongsTo(Chapter::class, 'chapter_id');
    }

    public function topic() {
      return $this->belongsTo(Topic::class, 'topic_id');
    }

    public function sub_topic() {
      return $this->belongsTo(Topic::class, 'sub_topic_id');
    }

    public function quiz() {
      return $this->belongsTo(Quiz::class, 'quiz_id');
    }
}
