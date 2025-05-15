<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\AddTaskRequest;
use App\Http\Requests\Task\EditTaskRequest;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->wantsJson()) {
            extract($this->DTFilters(request()->all()));
            $records = [];
            $tasks = Task::orderBy($sort_column, $sort_order)
                ->where('project_id', request()->project_id)
                ->with(['project', 'user'])
                ->where('user_id', auth()->user()->id)
                ->when($search != '', function ($query) use ($search) {
                    $query->where(function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%");
                        $query->orWhere('description', 'like', "%{$search}%");
                    });
                });

            // Get the total number of records for the datatable processing
            $count = $tasks->count();
            $records['recordsTotal'] = $count;
            $records['recordsFiltered'] = $count;
            $records['data'] = [];

            $tasks = $tasks->offset($offset)->limit($limit)->get();

            foreach ($tasks as $task) {
                $records['data'][] = [
                    'created_at' => $task->created_at,
                    'name' => '<a class="text-decoration-none" href="' . $task->url . '">' . $task->name . '</a>',
                    'description' => \Illuminate\Support\Str::limit($task->description, 50),
                    'priority' => $task->priority->label(),
                    'status' => $task->status->label(),
                    'due_date' => $task->due_date ? $task->due_date->format('d/m/Y') : '',
                    'action' => view('layouts.includes.actions')->with(['custom_title' => 'Tasks', 'id' => $task->id, 'routeName' => $this->getCurrentRouteName()], $task)->render(),
                ];
            }
            return response()->json($records);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(AddTaskRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $task = new Task($request->validated());
                if ($request->hasFile('image')) {
                    $task->image = $request->file('image')->store('tasks');
                }
                $task->user_id = auth()->user()->id;
                $task->save();
            });

            return response()->json(['success' => true, 'message' => 'Task created successfully'], 201);
        } catch (\Illuminate\Database\QueryException $e) {
            logger()->error('Database error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create task due to database error'
            ], 500);
        } catch (\Illuminate\Validation\ValidationException $e) {
            logger()->error('Validation failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            logger()->error('Error creating task: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the task'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        if (request()->wantsJson()) {
            // Check if the task belongs to the authenticated user
            if ($task->user_id !== auth()->user()->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            // Load related models
            $task->load(['project', 'user']);

            return response()->json([
                'success' => true,
                'task' => $task
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EditTaskRequest $request, Task $task)
    {
        dd('here');
        try {
            DB::transaction(function () use ($request, $task) {

                // Check if the task belongs to the authenticated user
                if ($task->user_id !== auth()->user()->id) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Unauthorized'
                    ], 403);
                }

                $task->update($request->validated());
                if ($request->hasFile('image')) {
                    // Delete the old image if it exists
                    if ($task->image) {
                        Storage::delete($task->getRawOriginal('image'));
                    }
                    $task->image = $request->file('image')->store('tasks');
                }
                $task->save();
            });

            return response()->json(['success' => true, 'message' => 'Task updated successfully'], 200);
        } catch (\Illuminate\Database\QueryException $e) {
            logger()->error('Database error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update task due to database error'
            ], 500);
        } catch (\Illuminate\Validation\ValidationException $e) {
            logger()->error('Validation failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            logger()->error('Error updating task: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the task'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
