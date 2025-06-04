<?php

namespace App;

use Zizaco\Entrust\EntrustPermission;
use Spatie\Permission\Models\Role as SpatieRole;
use App\Http\Controllers\Traits\HasSlug;
class Permission extends SpatieRole
{

      use HasSlug;
    protected $table = 'permissions';

	public static function getRecordWithSlug($id)
    {
        return Permission::where('id', '=', $id)->first();
    }
}