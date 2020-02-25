<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoleCollection;
use App\Repositories\RoleRepositoryInterface;
use App\Http\Resources\Role as RoleResource;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
     * @return RoleCollection
     */
    public function index()
    {
        try {
            // Check access
            if (!Auth::user()->hasAccess('role.list'))
                throw new Exception('Permission denied.', 403);

            $roles = $this->roleRepository->paginate(10);
            return new RoleCollection($roles);

        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Find a role
     * - - -
     * @param $id
     * @return RoleResource|JsonResponse
     */
    public function show($id)
    {
        try {
            // Check access
            if (!Auth::user()->hasAccess('role.detail'))
                throw new Exception('Permission denied.', 403);

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
     * @param Request $request
     * @return RoleResource|JsonResponse
     */
    public function store(Request $request)
    {
        try {
            // Check access
            if (!Auth::user()->hasAccess('role.create'))
                throw new Exception('Permission denied.', 403);

            // Validation roles
            $validator = Validator::make($request->all(), [
                'slug'          => 'required|string|max:50|unique:roles',
                'name'          => 'required|string|max:70',
                'permissions'   => 'required|array'
            ]);

            // Throw on validation fails
            if ($validator->fails())
                throw new Exception($validator->errors()->first(), 400);


            // Set user data
            $roleData = [
                'slug'          => $request->input('slug'),
                'name'          => $request->input('name'),
                'permissions'   => $this->formatPermissions($request->input('permissions')),
                'created_by'    => Auth::user() ? Auth::user()->id : null
            ];

            // Save data
            $role = $this->roleRepository->create($roleData);

            if (!$role)
                throw new Exception('Failed to create user data.', 500);

            return new RoleResource($role);

        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Update an user
     * - - -
     * @param Request $request
     * @param $id
     * @return UserResource|JsonResponse
     */
//    public function update(Request $request, $id)
//    {
//        try {
//            // Check user data
//            $user = $this->roleRepository->find($id);
//            if (!$user)
//                throw new Exception('Role not found.', 400);
//
//            // Validation roles
//            $validator = Validator::make($request->all(), [
//                'email'     => 'required|email|unique:users,email,' . $id,
//                'fullname'  => 'required|string|max:70',
//                'gender'    => 'nullable|in:male,female',
//                'password'  => 'nullable|min:6|confirmed'
//            ]);
//
//            // Throw on validator fails
//            if ($validator->fails())
//                throw new Exception($validator->errors()->first(), 400);
//
//            // Check user data
//            $userExist = $this->roleRepository->find($id);
//            if (!$userExist)
//                throw new Exception('User not found.', 400);
//
//            // Set user data
//            $userData = [
//                'email'         => $request->email,
//                'fullname'      => $request->fullname,
//                'gender'        => $request->gender,
//                'updated_by'    => Auth::user() ? Auth::user()->id : null
//            ];
//
//            // Has password?
//            if ($request->has('password'))
//                $userData['password'] = Hash::make($request->password);
//
//            // Save data
//            $user = $this->roleRepository->update($id, $userData);
//            if (!$user)
//                throw new Exception('Failed to save user data.', 500);
//
//            return new UserResource($user);
//
//        } catch (Exception $e) {
//            return $this->error($e->getMessage(), $e->getCode());
//        }
//    }
//
//    /**
//     * Delete an user
//     * - - -
//     * @param $id
//     * @return JsonResponse
//     */
//    public function destroy($id)
//    {
//        try {
//            // Check user data
//            $user = $this->roleRepository->find($id);
//            if (!$user)
//                throw new Exception('User not found.', 400);
//
//            $this->roleRepository->delete($id);
//
//            return $this->success('The data has been removed successfully.');
//
//        } catch (Exception $e) {
//            return $this->error($e->getMessage(), $e->getCode());
//        }
//    }

    /**
     * Get list of available permissions in config
     * - - -
     * @return JsonResponse
     */
    public function permissionList()
    {
        try {
            // Check access
            if (!Auth::user()->hasAccess('role.permission.list'))
                throw new Exception('Permission denied.', 403);

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
