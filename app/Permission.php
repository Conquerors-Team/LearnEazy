<?php

namespace App;

use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{
	public static function getRecordWithSlug($id)
    {
        return Permission::where('id', '=', $id)->first();
    }
}