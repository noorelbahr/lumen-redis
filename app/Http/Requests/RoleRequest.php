<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Urameshibr\Requests\FormRequest;

class RoleRequest extends FormRequest
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
            'name'          => 'required|string|max:70',
            'permissions'   => 'required|array'
        ];

        // Get route name
        $routeName = $this->getRouteName($request);

        // Handle create or update request, else no rules
        if ($routeName === 'roles.create') {
            $rules['slug'] = 'required|string|max:50|unique:roles';
        } elseif ($routeName === 'roles.update') {
            $rules['slug'] = 'required|string|max:50|unique:roles,slug,' . $request->route('id');
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
