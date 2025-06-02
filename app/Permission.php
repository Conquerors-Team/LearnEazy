<?php

namespace App;

use Zizaco\Entrust\EntrustPermission;
use Spatie\Permission\Models\Role as SpatieRole;

class Permission extends SpatieRole
{
	public static function getRecordWithSlug($id)
    {
        return Permission::where('id', '=', $id)->first();
    }
}