<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Http\Controllers\Traits\HasSlug;
class LmsSeries extends Model
{
    use HasSlug;
   protected $table = 'lmsseries';


   public static function boot()
    {
        parent::boot();
        LmsSeries::observe(new \App\Observers\UserActionsObserver);
    }

    public static function getRecordWithSlug($slug)
    {
        return LmsSeries::where('slug', '=', $slug)->first();
    }

    /**
     * This method lists all the items available in selected series
     * @return [type] [description]
     */
    public function getContents()
    {
        return DB::table('lmsseries_data')
          ->join('lmscontents', 'lmscontents.id', '=', 'lmscontent_id')
            ->where('lmsseries_id', '=', $this->id)->get();
    }


    public static function getFreeSeries($limit=0)
    {

        $records  = LmsSeries::where('show_in_front',1)
                                ->groupby('lms_category_id')
                                ->inRandomOrder()
                                ->pluck('lms_category_id')
                                ->toArray();
        if($limit > 0){

          $lms_cats  = LmsCategory::whereIn('id',$records)->limit(6)->get();
        }
        else{

          $lms_cats  = LmsCategory::whereIn('id',$records)->get();

        }
        return $lms_cats;

    }


    public function viewContents($limit= '')
    {

      $contents_data   = LmsSeriesData::where('lmsseries_id',$this->id)
                                  ->pluck('lmscontent_id')
                                  ->toArray();

       if($contents_data){

        if($limit!=''){

         $contents  = LmsContent::whereIn('id',$contents_data)->paginate($limit);
        }else{
         $contents  = LmsContent::whereIn('id',$contents_data)->get();

        }

         if($contents)
         return $contents;

          return FALSE;

       }

       return FALSE;


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

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'lmsseries_courses');
    }
}
