<?php

namespace App\Repositories\Eloquent;

use App\Models\Role;
use App\Repositories\RoleRepositoryInterface;

class RoleRepository implements RoleRepositoryInterface
{
    private $role;
    public function __construct(Role $role)
    {
        $this->role = $role;
    }

    public function all() : array
    {
        return $this->role->all();
    }

    public function paginate(int $page)
    {
        return $this->role->paginate($page);
    }

    public function find(string $id) : ?Role
    {
        return $this->role->find($id);
    }

    public function findBySlug(string $slug): ?Role
    {
        return $this->role->where('slug', $slug)->first();
    }

    public function create(array $attributes) : Role
    {
        return $this->role->create($attributes);
    }

    public function update(string $id, array $attributes) : Role
    {
        $role = $this->role->findOrFail($id);
        $role->update($attributes);
        return $role;
    }

    public function delete(string $id) : void
    {
        $role = $this->role->findOrFail($id);
        $role->delete();
    }

}
