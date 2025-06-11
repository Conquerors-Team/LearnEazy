<?php

namespace App;

use App\Http\Controllers\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;

class LmsContent extends Model
{
  use HasSlug;
    protected $table = 'lmscontents';

    public static function boot()
    {
        parent::boot();
        LmsContent::observe(new \App\Observers\UserActionsObserver);
    }

    public static function getRecordWithSlug($slug)
    {
        return LmsContent::where('slug', '=', $slug)->first();
    }

    public function category()
    {
        return $this->belongsTo('App\Lmscategory', 'category_id');
    }

    public function quiz()
    {
        return $this->belongsTo('App\Quiz', 'quiz_id')->withDefault();
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
}
