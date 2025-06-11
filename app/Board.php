<?php

namespace App;

use App\Http\Controllers\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    use HasSlug;
    protected $table="boards";

    public static function boot()
    {
        parent::boot();
        Board::observe(new \App\Observers\UserActionsObserver);
    }

    public static function getRecordWithSlug($id)
    {
        return Board::where('id', '=', $id)->first();
    }

    public function classes()
    {
        return $this->belongsToMany(BoardClass::class, 'boards_board_classes');
    }
}
