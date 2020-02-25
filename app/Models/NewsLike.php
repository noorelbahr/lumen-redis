<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsLike extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'news_id',
        'user_id',
        'created_by',
        'updated_by'
    ];

    /**
     * Belongs To
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
