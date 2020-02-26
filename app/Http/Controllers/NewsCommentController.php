<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewsRequest;
use App\Http\Resources\NewsComment as NewsCommentResource;
use App\Jobs\CommentJob;
use App\Repositories\NewsRepositoryInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class NewsCommentController extends Controller
{
    private $newsRepository;
    public function __construct(NewsRepositoryInterface $newsRepository)
    {
        $this->newsRepository = $newsRepository;
    }

    /**
     * Comment a news
     * - - -
     * @param NewsRequest $request -> Validate request & permission
     * @param $newsId
     * @return NewsCommentResource|JsonResponse
     */
    public function comment(NewsRequest $request, $newsId)
    {
        try {
            // Check news
            $news = $this->newsRepository->find($newsId);
            if (!$news)
                throw new Exception('Data not found.', 400);

            // Create job for saving a comment
            $job = new CommentJob($news, [
                'user_id'       => Auth::user()->id,
                'comment'       => $request->input('comment'),
                'created_by'    => Auth::user()->id
            ]);

            // Add delay time to the job for 60 seconds, to see that our job is running and exist in redis-cli
            $this->dispatch($job->delay(60));

            return $this->success('Comment queued.');

        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

}
