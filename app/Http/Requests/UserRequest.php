<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Urameshibr\Requests\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize(Request $request)
    {
        // Get route name
        $routeName = $this->getRouteName($request);

        // Return true if there is no route name
        if (!$routeName)
            return true;

        // Otherwise, check user permission
        return Auth::user()->hasAccess($routeName);
    }

    public function rules(Request $request)
    {
        $rules = [
            'fullname'  => 'required|string|max:70',
            'gender'    => 'nullable|in:male,female',
            'role'      => 'required|exists:roles,slug'
        ];

        // Get route name
        $routeName = $this->getRouteName($request);

        // Handle create or update request, else no rules
        if ($routeName === 'users.create') {
            $rules['email']     = 'required|email|unique:users';
            $rules['password']  = 'required|min:6|confirmed';
        } elseif ($routeName === 'users.update') {
            $rules['email']     = 'required|email|unique:users,email,' . $request->route('id');
            $rules['password']  = 'nullable|min:6|confirmed';
        } else {
            $rules = [];
        }

        return $rules;
    }

    private function getRouteName($request)
    {
        $routeAction = $request->route()[1];
        return isset($routeAction['as']) ? $routeAction['as'] : null;
    }
}
