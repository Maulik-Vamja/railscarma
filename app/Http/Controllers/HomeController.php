<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $projects = auth()->user()->projects();
        $projectCount = $projects->count();
        $activeProjectsCount = $projects->active()->count();
        $inactiveProjectsCount = $projects->inactive()->count();
        return view('home', compact('projectCount', 'activeProjectsCount', 'inactiveProjectsCount'));
    }
}
