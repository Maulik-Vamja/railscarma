<?php

namespace App\Http\Controllers;

use App\Enums\ProjectStatusEnum;
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
        $user = auth()->user();

        $allProjectCount = $user->projects()->count();
        $pendingProjectsCount = $user->projects()->of(ProjectStatusEnum::PENDING)->count();
        $inProgressProjectsCount = $user->projects()->of(ProjectStatusEnum::IN_PROGRESS)->count();
        $completedProjectsCount = $user->projects()->of(ProjectStatusEnum::COMPLETED)->count();

        return view('home', compact('allProjectCount', 'pendingProjectsCount', 'inProgressProjectsCount', 'completedProjectsCount'));
    }
}
