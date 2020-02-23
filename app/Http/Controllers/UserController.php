<?php

namespace App\Http\Controllers;

use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;

class UserController extends Controller
{
    /**
     * Show all users
     * - - -
     * @return \Illuminate\Http\JsonResponse
     */
    public function index() {
        try {
            return $this->success(User::all());

        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Find an user
     * - - -
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id) {
        try {
            // Check user data
            $user = User::find($id);
            if (!$user)
                throw new Exception('User not found.', 400);

            return $this->success($user);

        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Create an user
     * - - -
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request) {
        try {
            // Validation roles
            $validator = Validator::make($request->all(), [
                'email'     => 'required|email|unique:users',
                'fullname'  => 'required|string|max:70',
                'password'  => 'required|min:6|confirmed',
                'gender'    => 'nullable|in:male,female'
            ]);

            // Throw on validation fails
            if ($validator->fails())
                throw new Exception($validator->errors()->first(), 400);

            // Set user data
            $user = new User();
            $user->email        = $request->email;
            $user->fullname     = $request->fullname;
            $user->password     = Hash::make($request->password);
            $user->gender       = $request->gender;
            $user->created_by   = '';

            // Save data
            if (!$user->save())
                throw new Exception('Failed to create user data.', 500);

            return $this->success($user, 201);

        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Update an user
     * - - -
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id) {
        try {
            // Validation roles
            $validator = Validator::make($request->all(), [
                'email'     => 'required|email|unique:users,email,' . $id,
                'fullname'  => 'required|string|max:70',
                'password'  => 'nullable|min:6|confirmed',
                'gender'    => 'nullable|in:male,female'
            ]);

            // Throw on validator fails
            if ($validator->fails())
                throw new Exception($validator->errors()->first(), 400);

            // Check user data
            $user = User::find($id);
            if (!$user)
                throw new Exception('User not found.', 400);

            // Set user data
            $user->email        = $request->email;
            $user->fullname     = $request->fullname;
            $user->gender       = $request->gender;
            $user->updated_by   = '';

            // Has password?
            if ($request->password)
                $user->password = Hash::make($request->password);

            // Save data
            if (!$user->save())
                throw new Exception('Failed to save user data.', 500);

            return $this->success($user, 200);

        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Delete an user
     * - - -
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id) {
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
