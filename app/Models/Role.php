<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use Uuids, SoftDeletes;

    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'slug',
        'name',
        'permissions',
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
        'deleted_at',
        'deleted_by'
    ];

    /**
     * Belongs to Many
     */
    public function users()
    {
        return $this->belongsToMany('App\User', 'role_users','role_id', 'user_id')->withTimestamps();
    }

    /**
     * Get mapped permissions list
     * - - -
     * @return mixed|null
     */
    public function getMappedPermissionsAttribute()
    {
        return $this->permissions ?
            json_decode($this->permissions, true) :
            null;
    }

}
