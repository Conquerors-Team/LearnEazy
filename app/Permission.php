<?php

namespace App;

// use Spatie\Permission\Models\Role as SpatieRole;
use App\Http\Controllers\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{

      use HasSlug;
    protected $table = 'permissions';

	public static function getRecordWithSlug($id)
    {
        return Permission::where('id', '=', $id)->first();
    }
}