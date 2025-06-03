<?php
namespace App;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Models\Role as SpatieRole;
use App\Models\User;

class Role extends SpatieRole
{
    protected $fillable = ['name', 'display_name', 'description'];

    public static function getRoles()
    {
        return self::all();
    }

    public static function getRoleId($role_name)
    {
        return self::where('name', $role_name)->first();
    }

    public function permission(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'permission_role');
    }

    /**
     * Correctly overrides Spatie's users() method.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'model_has_roles', 'role_id', 'model_id');
    }
}
