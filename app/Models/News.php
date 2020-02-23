<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use Uuids, SoftDeletes;

    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'slug',
        'title',
        'content',
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
}
