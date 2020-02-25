<?php

namespace App\Http\Controllers;

use App\Events\NewsEvent;
use App\Http\Requests\NewsRequest;
use App\Repositories\NewsRepositoryInterface;
use App\Http\Resources\News as NewsResource;
use App\Http\Resources\NewsCollection;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    private $newsRepository;
    public function __construct(NewsRepositoryInterface $newsRepository)
    {
        $this->newsRepository = $newsRepository;
    }

    /**
     * Show all news
     * - - -
     * @param NewsRequest $request -> Validate request & permission
     * @return NewsCollection|JsonResponse
     */
    public function index(NewsRequest $request)
    {
        $news = $this->newsRepository->paginate(10);
        return new NewsCollection($news);
    }

    /**
     * Show news detail
     * - - -
     * @param NewsRequest $request -> Validate request & permission
     * @param $id
     * @return NewsResource|JsonResponse
     */
    public function show(NewsRequest $request, $id)
    {
        try {
            $news = $this->newsRepository->find($id);
            if (!$news)
                throw new Exception('Data not found.', 400);

            return new NewsResource($news);

        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Create a news
     * - - -
     * @param NewsRequest $request -> Validate request & permission
     * @return NewsResource|JsonResponse
     */
    public function store(NewsRequest $request)
    {
        try {
            // Handle slug
            $slug = Str::slug($request->input('title'), '-');
            $slugCount = $this->newsRepository->countBySlug($slug);
            if ($slugCount > 0)
                $slug .= '-' . $slugCount;

            // Set news data
            $newsData = [
                'user_id'       => Auth::user() ? Auth::user()->id : null,
                'slug'          => $slug,
                'title'         => $request->input('title'),
                'content'       => $request->input('content'),
                'created_by'    => Auth::user() ? Auth::user()->id : null
            ];

            // Append tags if exists
            if ($request->input('tags'))
                $newsData['tags'] = implode(',', $request->input('tags'));

            // Set heading image if exists
            if ($request->hasFile('heading_image')) {
                $uploadedFile = $request->file('heading_image');
                $filename = time() . $uploadedFile->getClientOriginalName();

                Storage::disk('public')->putFileAs('news', $uploadedFile, $filename);

                $newsData['heading_image'] = $filename;
            }

            // Save data
            $news = $this->newsRepository->create($newsData);
            if (!$news)
                throw new Exception('Failed to create news data.', 500);

            // Trigger event
            event(new NewsEvent($news, NewsEvent::CREATE));

            return new NewsResource($news);

        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Update a news
     * - - -
     * @param NewsRequest $request -> Validate request & permission
     * @param $id
     * @return NewsResource|JsonResponse
     */
    public function update(NewsRequest $request, $id)
    {
        try {
            // Check news
            $news = $this->newsRepository->find($id);
            if (!$news)
                throw new Exception('Data not found.', 400);

            // Handle slug
            $slug = Str::slug($request->input('title'), '-');
            $slugCount = $this->newsRepository->countBySlug($slug);
            if ($slugCount > 0)
                $slug .= '-' . $slugCount;

            // Set news data
            $newsData = [
                'slug'          => $slug,
                'title'         => $request->input('title'),
                'content'       => $request->input('content'),
                'updated_by'    => Auth::user() ? Auth::user()->id : null
            ];

            // Append tags if exists
            if ($request->input('tags'))
                $newsData['tags'] = implode(',', $request->input('tags'));

            // Set heading image if exists
            if ($request->hasFile('heading_image')) {
                $uploadedFile = $request->file('heading_image');
                $filename = time() . $uploadedFile->getClientOriginalName();

                Storage::disk('public')->putFileAs('news', $uploadedFile, $filename);

                $newsData['heading_image'] = $filename;
            }

            // Save data
            $news = $this->newsRepository->update($id, $newsData);
            if (!$news)
                throw new Exception('Failed to save news data.', 500);

            // Trigger event
            event(new NewsEvent($news, NewsEvent::UPDATE));

            return new NewsResource($news);

        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Delete a news
     * - - -
     * @param NewsRequest $request -> Validate request & permission
     * @param $id
     * @return JsonResponse
     */
    public function destroy(NewsRequest $request, $id)
    {
        try {
            // Check news
            $news = $this->newsRepository->find($id);
            if (!$news)
                throw new Exception('Data not found.', 400);

            // Delete news
            $this->newsRepository->delete($id);

            // Trigger event
            event(new NewsEvent($news, NewsEvent::DELETE));

            return $this->success('The data has been removed successfully.');

        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

}
