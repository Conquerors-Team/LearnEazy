<?php

namespace App;

use App\Http\Controllers\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;

class BatchQuizzes extends Model
{
    use HasSlug;
    protected $table="batch_quizzes";

    public static function boot()
    {
        parent::boot();
        BatchQuizzes::observe(new \App\Observers\UserActionsObserver);
    }

    public static function addRecord($quiz, $batch_ids)
    {
    	if ( ! empty( $batch_ids ) ) {
            foreach ($batch_ids as $key => $value) {
        		$batch_quiz               = new BatchQuizzes();
                $batch_quiz->quiz_id      = $quiz->id;
        		$batch_quiz->category_id  = $quiz->category_id;
        		$batch_quiz->batch_id     = $value;
        		$batch_quiz->institute_id = $quiz->institute_id;
        		$batch_quiz->user_id      = $quiz->record_updated_by;
        		$batch_quiz->save();
        	}
        }
    }

    public static function addForAll($institute_id, $quiz)
    {

    	$all_batches  = Batch::where('institute_id',$institute_id)->pluck('id')->toArray();
    	foreach ($all_batches as $key => $value) {
    		$batch_quiz               = new BatchQuizzes();
    		$batch_quiz->quiz_id      = $quiz->id;
            $batch_quiz->category_id  = $quiz->category_id;
    		$batch_quiz->batch_id     = $value;
    		$batch_quiz->institute_id = $quiz->institute_id;
    		$batch_quiz->user_id      = $quiz->record_updated_by;
    		$batch_quiz->save();
    	}
    }

    public function quiz() {
        return $this->belongsTo(Quiz::class, 'quiz_id')->withDefault();
    }

}
