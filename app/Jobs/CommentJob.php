<?php

namespace App\Jobs;

use App\Models\News;

class CommentJob extends Job
{
    protected $news;
    protected $attributes;
    /**
     * Create a new job instance.
     *
     * @param News $news
     * @param array $attributes
     */
    public function __construct(News $news, array $attributes)
    {
        $this->news         = $news;
        $this->attributes   = $attributes;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->news->comments()->create($this->attributes);
    }
}
