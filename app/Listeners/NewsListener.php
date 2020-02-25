<?php

namespace App\Listeners;

use App\Events\NewsEvent;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;

class NewsListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  NewsEvent  $event
     * @return void
     */
    public function handle(NewsEvent $event)
    {
        Log::create([
            'user_id' => Auth::user()->id,
            'reference_id' => $event->news->id,
            'reference' => 'news',
            'action' => $event->action,
            'status' => 'success'
        ]);
    }
}
