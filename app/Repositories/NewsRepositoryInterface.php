<?php

namespace App\Repositories;

use App\Models\News;

interface NewsRepositoryInterface
{
    public function all() : array;
    public function paginate(int $page);
    public function find(string $id) : ?News;
    public function countBySlug(string $slug) : int;
    public function create(array $attributes) : News;
    public function update(string $id, array $attributes) : News;
    public function delete(string $id) : void;
}
