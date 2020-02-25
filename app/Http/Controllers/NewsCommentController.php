<?php

namespace App\Http\Controllers;

use App\Http\Resources\NewsComment as NewsCommentResource;
use App\Repositories\NewsRepositoryInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
     * @param Request $request
     * @param $newsId
     * @return NewsCommentResource|JsonResponse
     */
    public function store(Request $request, $newsId)
    {
        try {
            // Validation roles
            $validator = Validator::make($request->all(), [
                'comment' => 'required|string'
            ]);

            // Throw on validator fails
            if ($validator->fails())
                throw new Exception($validator->errors()->first(), 400);

            // Check news
            $news = $this->newsRepository->find($newsId);
            if (!$news)
                throw new Exception('Data not found.', 400);

            // Save comment
            $comment = $news->comments()->create([
                'user_id'       => Auth::user()->id,
                'comment'       => $request->input('comment'),
                'created_by'    => Auth::user()->id
            ]);
            if (!$comment)
                throw new Exception('Failed to save comment data.', 500);

            return new NewsCommentResource($comment);

        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

}
