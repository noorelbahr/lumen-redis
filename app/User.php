<?php

namespace App;

use App\Traits\Uuids;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Laravel\Lumen\Auth\Authorizable;
use Laravel\Passport\HasApiTokens;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Uuids, SoftDeletes, Authenticatable, Authorizable, HasApiTokens;

    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'fullname',
        'gender',
        'picture',
        'password',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'deleted_at',
        'deleted_by'
    ];

    /**
     * Belongs to Many
     */
    public function roles()
    {
        return $this->belongsToMany('App\Models\Role', 'role_users','user_id', 'role_id')->withTimestamps();
    }

    /**
     * Check user access
     * - - -
     * @param string $permission
     * @return bool
     */
    public function hasAccess(string $permission) : bool
    {
        $roles = $this->roles;
        if (!$roles)
            return false;

        foreach ($roles as $role) {
            $permissionList = $role->mapped_permissions;
            if ($permissionList && in_array($permission, $permissionList))
                return true;
        }

        return false;
    }
}
