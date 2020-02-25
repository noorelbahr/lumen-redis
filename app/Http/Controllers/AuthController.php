<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Resources\User as UserResource;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Register
     * - - -
     * @return UserCollection
     */
//    public function register(Request $request)
//    {
//        $users = $this->userRepository->all();
//        return new UserCollection($users);
//    }

}
