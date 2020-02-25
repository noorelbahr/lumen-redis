<?php

namespace App\Http\Controllers;

use App\Events\NewsEvent;
use App\Repositories\NewsRepositoryInterface;
use App\Http\Resources\News as NewsResource;
use App\Http\Resources\NewsCollection;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
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
     * @return NewsCollection|JsonResponse
     */
    public function index()
    {
        try {
            // Check access
            if (!Auth::user()->hasAccess('news.list'))
                throw new Exception('Permission denied.', 403);

            $news = $this->newsRepository->paginate(10);
            return new NewsCollection($news);

        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Show news detail
     * - - -
     * @param $id
     * @return NewsResource|JsonResponse
     */
    public function show($id)
    {
        try {
            // Check access
            if (!Auth::user()->hasAccess('news.detail'))
                throw new Exception('Permission denied.', 403);

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
     * @param Request $request
     * @return NewsResource|JsonResponse
     */
    public function store(Request $request)
    {
        try {
            // Check access
            if (!Auth::user()->hasAccess('news.create'))
                throw new Exception('Permission denied.', 403);

            // Validation roles
            $validator = Validator::make($request->all(), [
                'title'         => 'required|string|max:150',
                'content'       => 'required',
                'heading_image' => 'required|image|max:2048',
                'tags'          => 'nullable|array'
            ]);

            // Throw on validation fails
            if ($validator->fails())
                throw new Exception($validator->errors()->first(), 400);

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
     * @param Request $request
     * @param $id
     * @return NewsResource|JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            // Check access
            if (!Auth::user()->hasAccess('news.update'))
                throw new Exception('Permission denied.', 403);

            // Check news
            $news = $this->newsRepository->find($id);
            if (!$news)
                throw new Exception('Data not found.', 400);

            // Validation roles
            $validator = Validator::make($request->all(), [
                'title'         => 'required|string|max:150',
                'content'       => 'required',
                'heading_image' => 'nullable|image|max:2048',
                'tags'          => 'nullable|array'
            ]);

            // Throw on validator fails
            if ($validator->fails())
                throw new Exception($validator->errors()->first(), 400);

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
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        try {
            // Check access
            if (!Auth::user()->hasAccess('news.delete'))
                throw new Exception('Permission denied.', 403);

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
