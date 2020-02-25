<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Repositories\RoleRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use App\Http\Resources\User as UserResource;
use App\Http\Resources\UserCollection;
use App\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
     * @param UserRequest $request -> Validate request & permission
     * @return UserCollection|JsonResponse
     */
    public function index(UserRequest $request)
    {
        $users = $this->userRepository->paginate(10);
        return new UserCollection($users);
    }

    /**
     * Find an user
     * - - -
     * @param UserRequest $request -> Validate request & permission
     * @param $id
     * @return UserResource|JsonResponse
     */
    public function show(UserRequest $request, $id)
    {
        try {
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
     * @param UserRequest $request -> Validate request & permission
     * @return UserResource|JsonResponse
     */
    public function store(UserRequest $request)
    {
        try {
            // Check role data
            $role = $this->roleRepository->findBySlug($request->input('role'));
            if (!$role)
                throw new Exception('Role not found.', 400);

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

            // Attach role
            $user->roles()->attach($role);

            return new UserResource($user);

        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Update an user
     * - - -
     * @param UserRequest $request -> Validate request & permission
     * @param $id
     * @return UserResource|JsonResponse
     */
    public function update(UserRequest $request, $id)
    {
        DB::beginTransaction();
        try {
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

            // Attach role
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
     * @param UserRequest $request -> Validate request & permission
     * @param $id
     * @return JsonResponse
     */
    public function destroy(UserRequest $request, $id)
    {
        try {
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
