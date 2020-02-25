<?php

namespace App\Http\Controllers;

use App\Http\Resources\NewsLike as NewsLikeResource;
use App\Repositories\NewsRepositoryInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class NewsLikeController extends Controller
{
    private $newsRepository;
    public function __construct(NewsRepositoryInterface $newsRepository)
    {
        $this->newsRepository = $newsRepository;
    }

    /**
     * Like a news
     * - - -
     * @param $newsId
     * @return NewsLikeResource|JsonResponse
     */
    public function like($newsId)
    {
        try {
            // Check news
            $news = $this->newsRepository->find($newsId);
            if (!$news)
                throw new Exception('Data not found.', 400);

            // Save like
            $like = $news->likes()->firstOrCreate([
                'user_id' => Auth::user()->id,
            ], [
                'created_by' => Auth::user()->id
            ]);
            if (!$like)
                throw new Exception('Failed to save like data.', 500);

            return new NewsLikeResource($like);

        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Unlike a news
     * - - -
     * @param $newsId
     * @return JsonResponse
     */
    public function unlike($newsId)
    {
        try {
            // Check news
            $news = $this->newsRepository->find($newsId);
            if (!$news)
                throw new Exception('Data not found.', 400);

            // Delete like
            $news->likes()
                ->where('user_id', Auth::user()->id)
                ->delete();

            return $this->success('Like data has been removed successfully.');

        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

}
