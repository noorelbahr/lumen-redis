<?php

namespace App\Repositories;

use App\User;

interface UserRepositoryInterface
{
    public function all() : array;
    public function paginate(int $page);
    public function find(string $id) : ?User;
    public function create(array $attributes) : User;
    public function update(string $id, array $attributes) : User;
    public function delete(string $id) : void ;
}
