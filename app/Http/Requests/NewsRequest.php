<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Urameshibr\Requests\FormRequest;

class NewsRequest extends FormRequest
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
            'title'     => 'required|string|max:150',
            'content'   => 'required',
            'tags'      => 'nullable|array'
        ];

        // Get route name
        $routeName = $this->getRouteName($request);

        // Handle create or update request, else no rules
        if ($routeName === 'news.create') {
            $rules['heading_image'] = 'required|image|max:2048';
        } elseif ($routeName === 'news.update') {
            $rules['heading_image'] = 'nullable|image|max:2048';
        } elseif ($routeName === 'news.comment') {
            $rules = [
                'comment' => 'required|string'
            ];
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
