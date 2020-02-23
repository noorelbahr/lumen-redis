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

    public function all()
    {
        return $this->user->all();
    }

    public function find($id)
    {
        return $this->user->find($id);
    }

    public function create(array $attributes)
    {
        return $this->user->create($attributes);
    }

    public function update($id, array $attributes)
    {
        $user = $this->user->find($id);
        $user->update($attributes);
        return $user;
    }

    public function delete($id)
    {
        $user = $this->user->find($id);
        return $user->delete();
    }
}
