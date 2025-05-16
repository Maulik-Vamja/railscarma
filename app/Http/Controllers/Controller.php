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
        $sort_column = (isset($request['order'][0]['column']) && isset($request['columns'][$request['order'][0]['column']]['data'])) ? $request['columns'][$request['order'][0]['column']]['data'] : 'created_at';
        $sort_order = isset($request['order'][0]['dir']) ? $request['order'][0]['dir'] : 'DESC';
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
