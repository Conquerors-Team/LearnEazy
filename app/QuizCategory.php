<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

use App\Http\Controllers\Traits\HasSlug;
class QuizCategory extends Model
{
      use HasSlug;
    protected $table = "quizcategories";

    public static function boot()
    {
        parent::boot();
        QuizCategory::observe(new \App\Observers\UserActionsObserver);
    }

    public static function getRecordWithSlug($slug)
    {
        return QuizCategory::where('slug', '=', $slug)->first();
    }

    /**
     * Lists the list of quizes related to the selected category
     * @return [type] [description]
     */
    public function quizzes()
    {

        return $this->getQuizzes()
        ->where('start_date','<=',date('Y-m-d'))
        ->where('end_date','>=',date('Y-m-d'))
        ->where('total_questions','>','0')
        ->get();


    }

     public function cat_quizzes()
    {
        $batches  = getStudentBatches();

        return $this->getQuizzes()
         ->join('batch_quizzes','batch_quizzes.quiz_id','=','quizzes.id')
        ->where('start_date','<=',date('Y-m-d'))
        ->where('end_date','>=',date('Y-m-d'))
        ->where('total_questions','>','0')
        ->whereIn('batch_quizzes.batch_id',$batches)
        ->get();


    }

    public function getQuizzes()
    {
        return $this->hasMany('App\Quiz', 'category_id');

    }


     public static function getShowFrontCategories($limit=0)
    {
       if($limit > 0){

         $list   = Quiz::where('show_in_front',1)
                          ->where('is_paid',0)
                          ->groupby('category_id')
                          ->limit(6)
                          ->inRandomOrder()
                          ->get();
                          // dd($list);
       }
       else{


         $list   = Quiz::where('show_in_front',1)
                          ->where('is_paid',0)
                          ->groupby('category_id')
                          ->inRandomOrder()
                          ->get();

       }


      $cat_ids  = Arr::pluck($list,'category_id');

      $categories = [];
      foreach ($cat_ids as $key => $value) {

        $categories[]  = QuizCategory::where('id',$value)->first();

      }

      return $categories;

   }
}
