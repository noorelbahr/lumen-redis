<?php

namespace App\Repositories;

use App\Models\Role;

interface RoleRepositoryInterface
{
    public function all() : array;
    public function paginate(int $page);
    public function find(string $id) : ?Role;
    public function findBySlug(string $slug) : ?Role;
    public function create(array $attributes) : Role;
    public function update(string $id, array $attributes) : Role;
    public function delete(string $id) : void;
}
