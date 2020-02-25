<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use App\Http\Resources\RoleCollection;
use App\Repositories\RoleRepositoryInterface;
use App\Http\Resources\Role as RoleResource;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    private $roleRepository;
    public function __construct(RoleRepositoryInterface $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    /**
     * Show all roles
     * - - -
     * @param RoleRequest $request -> Validate request & permission
     * @return RoleCollection
     */
    public function index(RoleRequest $request)
    {
        $roles = $this->roleRepository->paginate(10);
        return new RoleCollection($roles);
    }

    /**
     * Find a role
     * - - -
     * @param RoleRequest $request -> Validate request & permission
     * @param $id
     * @return RoleResource|JsonResponse
     */
    public function show(RoleRequest $request, $id)
    {
        try {
            // Check role data
            $role = $this->roleRepository->find($id);
            if (!$role)
                throw new Exception('Role not found.', 400);

            return new RoleResource($role);

        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Create a role
     * - - -
     * @param RoleRequest $request -> Validate request & permission
     * @return RoleResource|JsonResponse
     */
    public function store(RoleRequest $request)
    {
        try {
            // Set role data
            $roleData = [
                'slug'          => $request->input('slug'),
                'name'          => $request->input('name'),
                'permissions'   => $this->formatPermissions($request->input('permissions')),
                'created_by'    => Auth::user() ? Auth::user()->id : null
            ];

            // Save data
            $role = $this->roleRepository->create($roleData);

            if (!$role)
                throw new Exception('Failed to create role data.', 500);

            return new RoleResource($role);

        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Update a role
     * - - -
     * @param RoleRequest $request -> Validate request & permission
     * @param $id
     * @return RoleResource|JsonResponse
     */
    public function update(RoleRequest $request, $id)
    {
        try {
            // Check role data
            $roleExist = $this->roleRepository->find($id);
            if (!$roleExist)
                throw new Exception('Role not found.', 400);

            // Set role data
            $roleData = [
                'slug'          => $request->input('slug'),
                'name'          => $request->input('name'),
                'permissions'   => $this->formatPermissions($request->input('permissions')),
                'updated_by'    => Auth::user() ? Auth::user()->id : null
            ];

            // Save data
            $role = $this->roleRepository->update($id, $roleData);
            if (!$role)
                throw new Exception('Failed to save role data.', 500);

            return new RoleResource($role);

        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Delete a role
     * - - -
     * @param RoleRequest $request -> Validate request & permission
     * @param $id
     * @return JsonResponse
     */
    public function destroy(RoleRequest $request, $id)
    {
        try {
            // Check role data
            $role = $this->roleRepository->find($id);
            if (!$role)
                throw new Exception('Role not found.', 400);

            $this->roleRepository->delete($id);

            return $this->success('The data has been removed successfully.');

        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Get list of available permissions in config
     * - - -
     * @param RoleRequest $request -> Validate request & permission
     * @return JsonResponse
     */
    public function permissionList(RoleRequest $request)
    {
        try {
            return $this->success(config('permissions', []));

        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Format permissions
     * - - -
     * @param array $permissionsArray
     * @return false|string|null
     */
    private function formatPermissions(array $permissionsArray)
    {
        $availPermissions = config('permissions', []);

        if (count($availPermissions) === 0 || count($permissionsArray) === 0)
            return null;

        $permissions = [];
        foreach ($availPermissions as $permission) {
            $permissions[$permission] = in_array($permission, $permissionsArray);
        }

        return json_encode($permissions);
    }

}
