<?php

namespace App\Repositories\Eloquent;

use App\Repositories\NewsRepositoryInterface;
use App\Models\News;

class NewsRepository implements NewsRepositoryInterface
{
    private $news;
    public function __construct(News $news)
    {
        $this->news = $news;
    }

    public function all() : array
    {
        return $this->news->all();
    }

    public function paginate(int $page)
    {
        return $this->news->paginate($page);
    }

    public function find(string $id) : ?News
    {
        return $this->news->find($id);
    }

    public function countBySlug(string $slug) : int
    {
        return $this->news->where('slug', $slug)->count();
    }

    public function create(array $attributes) : News
    {
        return $this->news->create($attributes);
    }

    public function update(string $id, array $attributes) : News
    {
        $news = $this->news->findOrFail($id);
        $news->update($attributes);
        return $news;
    }

    public function delete(string $id) : void
    {
        $news = $this->news->findOrFail($id);
        $news->delete();
    }

}
