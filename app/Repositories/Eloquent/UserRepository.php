<?php

namespace App\Repositories\Eloquent;

use App\Repositories\UserRepositoryInterface;
use App\User;

class UserRepository implements UserRepositoryInterface
{
    private $user;
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function all() : array
    {
        return $this->user->all();
    }

    public function paginate(int $page)
    {
        return $this->user->paginate($page);
    }

    public function find(string $id) : ?User
    {
        return $this->user->find($id);
    }

    public function create(array $attributes) : User
    {
        return $this->user->create($attributes);
    }

    public function update(string $id, array $attributes) : User
    {
        $user = $this->user->findOrFail($id);
        $user->update($attributes);
        return $user;
    }

    public function delete(string $id) : void
    {
        $user = $this->user->findOrFail($id);
        $user->delete();
    }
}
