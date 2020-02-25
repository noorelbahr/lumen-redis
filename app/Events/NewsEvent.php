<?php

namespace App\Events;

use App\Models\News;
use Illuminate\Http\Request;

class NewsEvent extends Event
{
    public $news;
    public $action;

    // Actions
    const CREATE = 'create';
    const UPDATE = 'update';
    const DELETE = 'delete';

    /**
     * Create a new event instance.
     *
     * @param News $news
     * @param $action
     */
    public function __construct(News $news, $action)
    {
        $this->news     = $news;
        $this->action   = $action;
    }
}
