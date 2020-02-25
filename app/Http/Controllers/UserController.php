<?php

namespace App\Http\Controllers;

use App\Repositories\RoleRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use App\Http\Resources\User as UserResource;
use App\Http\Resources\UserCollection;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    private $userRepository;
    private $roleRepository;
    public function __construct(UserRepositoryInterface $userRepository, RoleRepositoryInterface $roleRepository)
    {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
    }

    /**
     * Show all users
     * - - -
     * @return UserCollection|JsonResponse
     */
    public function index()
    {
        try {
            // Check access
//            if (!Auth::user()->hasAccess('users.list'))
//                throw new Exception('Permission denied.', 403);

            $users = $this->userRepository->paginate(10);
            return new UserCollection($users);

        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Find an user
     * - - -
     * @param $id
     * @return UserResource|JsonResponse
     */
    public function show($id)
    {
        try {
            // Check access
            if (!Auth::user()->hasAccess('users.detail'))
                throw new Exception('Permission denied.', 403);

            // Check user data
            $user = $this->userRepository->find($id);
            if (!$user)
                throw new Exception('User not found.', 400);

            return new UserResource($user);

        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Create an user
     * - - -
     * @param Request $request
     * @return UserResource|JsonResponse
     */
    public function store(Request $request)
    {
        try {
            // Check access
            if (!Auth::user()->hasAccess('users.create'))
                throw new Exception('Permission denied.', 403);

            // Validation roles
            $validator = Validator::make($request->all(), [
                'email'     => 'required|email|unique:users',
                'fullname'  => 'required|string|max:70',
                'gender'    => 'nullable|in:male,female',
                'password'  => 'required|min:6|confirmed'
            ]);

            // Throw on validation fails
            if ($validator->fails())
                throw new Exception($validator->errors()->first(), 400);

            // Set user data
            $userData = [
                'email'         => $request->input('email'),
                'fullname'      => $request->input('fullname'),
                'gender'        => $request->input('gender'),
                'password'      => Hash::make($request->input('password')),
                'created_by'    => Auth::user() ? Auth::user()->id : null
            ];

            // Save data
            $user = $this->userRepository->create($userData);

            if (!$user)
                throw new Exception('Failed to create user data.', 500);

            return new UserResource($user);

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
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            // Check access
//            if (!Auth::user()->hasAccess('users.update'))
//                throw new Exception('Permission denied.', 403);

            // Check user data
            $user = $this->userRepository->find($id);
            if (!$user)
                throw new Exception('User not found.', 400);

            // Validation roles
            $validator = Validator::make($request->all(), [
                'email'     => 'required|email|unique:users,email,' . $id,
                'fullname'  => 'required|string|max:70',
                'gender'    => 'nullable|in:male,female',
                'password'  => 'nullable|min:6|confirmed',
                'role'      => 'required|exists:roles,slug'
            ]);

            // Throw on validator fails
            if ($validator->fails())
                throw new Exception($validator->errors()->first(), 400);

            // Check user data
            $userExist = $this->userRepository->find($id);
            if (!$userExist)
                throw new Exception('User not found.', 400);

            // Check role data
            $role = $this->roleRepository->findBySlug($request->input('role'));
            if (!$role)
                throw new Exception('Role not found.', 400);

            // Set user data
            $userData = [
                'email'         => $request->input('email'),
                'fullname'      => $request->input('fullname'),
                'gender'        => $request->input('gender'),
                'updated_by'    => Auth::user() ? Auth::user()->id : null
            ];

            // Has password?
            if ($request->has('password'))
                $userData['password'] = Hash::make($request->input('password'));

            // Save data
            $user = $this->userRepository->update($id, $userData);
            if (!$user)
                throw new Exception('Failed to save user data.', 500);

            $user->roles()->attach($role);

            DB::commit();

            return new UserResource($user);

        } catch (Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Delete an user
     * - - -
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        try {
            // Check access
            if (!Auth::user()->hasAccess('users.delete'))
                throw new Exception('Permission denied.', 403);

            // Check user data
            $user = $this->userRepository->find($id);
            if (!$user)
                throw new Exception('User not found.', 400);

            $this->userRepository->delete($id);

            return $this->success('The data has been removed successfully.');

        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

}
