<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepositoryInterface;
use App\User;
use App\Http\Resources\User as UserResource;
use App\Http\Resources\UserCollection;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    private $userRepository;
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Show all users
     * - - -
     * @return UserCollection
     */
    public function index()
    {
        $users = $this->userRepository->all();
        return new UserCollection($users);
    }

    /**
     * Find an user
     * - - -
     * @param $id
     * @return UserResource
     */
    public function show($id)
    {
        $user = $this->userRepository->find($id);
        return new UserResource($user);
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
                'email'         => $request->email,
                'fullname'      => $request->fullname,
                'gender'        => $request->gender,
                'password'      => Hash::make($request->password),
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
        try {
            // Validation roles
            $validator = Validator::make($request->all(), [
                'email'     => 'required|email|unique:users,email,' . $id,
                'fullname'  => 'required|string|max:70',
                'gender'    => 'nullable|in:male,female',
                'password'  => 'nullable|min:6|confirmed'
            ]);

            // Throw on validator fails
            if ($validator->fails())
                throw new Exception($validator->errors()->first(), 400);

            // Check user data
            $userExist = $this->userRepository->find($id);
            if (!$userExist)
                throw new Exception('User not found.', 400);

            // Set user data
            $userData = [
                'email'         => $request->email,
                'fullname'      => $request->fullname,
                'gender'        => $request->gender,
                'updated_by'    => Auth::user() ? Auth::user()->id : null
            ];

            // Has password?
            if ($request->has('password'))
                $userData['password'] = Hash::make($request->password);

            // Save data
            $user = $this->userRepository->update($id, $userData);
            if (!$user)
                throw new Exception('Failed to save user data.', 500);

            return new UserResource($user);

        } catch (Exception $e) {
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
            // Check user data
            $user = User::find($id);
            if (!$user)
                throw new Exception('User not found.', 400);

            if (!$user->delete())
                throw new Exception('Failed to remove data.', 500);

            $this->success('The data has been removed successfully.', 200);

        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

}
