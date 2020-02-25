<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class NewsComment extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'news_id',
        'user_id',
        'comment',
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
     * Belongs To
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
