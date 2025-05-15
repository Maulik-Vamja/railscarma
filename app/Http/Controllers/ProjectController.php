<?php

namespace App\Http\Controllers;

use App\Http\Requests\Project\AddProjectRequest;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (request()->wantsJson()) {
            extract($this->DTFilters($request->all()));
            $records = [];
            $projects = Project::orderBy($sort_column, $sort_order)
                ->with(['tasks', 'user'])
                ->where('user_id', auth()->user()->id)
                ->when($search != '', function ($query) use ($search) {
                    $query->where(function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%");
                        $query->orWhere('description', 'like', "%{$search}%");
                    });
                });

            // Get the total number of records for the datatable processing
            $count = $projects->count();
            $records['recordsTotal'] = $count;
            $records['recordsFiltered'] = $count;
            $records['data'] = [];

            $projects = $projects->offset($offset)->limit($limit)->get();

            foreach ($projects as $project) {

                $records['data'][] = [
                    'created_at' => $project->created_at,
                    'name' => '<a class="text-decoration-none" href="' . $project->url . '">' . $project->name . '</a>',
                    'description' => \Illuminate\Support\Str::limit($project->description, 50),
                    'priority' => $project->priority->label(),
                    'status' => $project->status->label(),
                    'start_date' => $project->start_date->format('d/m/Y'),
                    'end_date' => $project->end_date->format('d/m/Y'),
                    'action' => view('layouts.includes.actions')->with(['custom_title' => 'Projects', 'id' => $project->id, 'routeName' => $this->getCurrentRouteName()], $project)->render(),
                ];
            }
            return $records;
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('projects.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AddProjectRequest $request)
    {

        // Store the project

        $project = DB::transaction(function () use ($request) {

            $project = new Project($request->validated());
            if ($request->hasFile('image')) {
                $project->image = $request->file('image')->store('projects');
            }
            $project->user_id = auth()->user()->id;
            $project->save();

            return $project;
        });

        return redirect()->route('home')->with('success', 'Project created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        return view('projects.show')->with(['project' => $project]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        return view('projects.edit')->with(['project' => $project]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
