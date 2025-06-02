<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
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
