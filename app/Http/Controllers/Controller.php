<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Route;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function DTFilters($request)
    {
        $sort_column = $request['order'][0]['column'] ?? 'id';
        $sort_order = $request['order'][0]['dir'] ?? 'asc';
        $limit = $request['length'] ?? 10;
        $offset = $request['start'] ?? 0;
        $search = $request['search']['value'] ?? '';

        return compact('sort_column', 'sort_order', 'limit', 'offset', 'search');
    }

    public function getCurrentRouteName()
    {
        $routeName = Route::currentRouteName();
        return substr($routeName, 0, strrpos($routeName, '.'));
    }
}
