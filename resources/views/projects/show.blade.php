@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="">
            <a href="{{ $project->url ?? 'javascript::void(0)' }}" class="text-decoration-none">
                <h1>{{ $project->name }}</h1>
            </a>

        </div>
        <p><strong>Created At:</strong> {{ $project->created_at->format('Y-m-d H:i:s') }}</p>

        <p>{{ $project->description }}</p>
        <ul>
            <li><strong>Start Date:</strong> {{ $project->start_date->format('Y-m-d') }}</li>
            <li><strong>End Date:</strong> {{ $project->end_date->format('Y-m-d') }}</li>
            <li><strong>Status:</strong> {{ $project->status->label() }}</li>
        </ul>
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-0">Tasks</h5>
                            <p class="card-text">List of tasks associated with this project.</p>
                        </div>
                        <div class="card-toolbar">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#addTaskModal">
                                <i class="fas fa-plus"></i> Add Task
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-res">
                            <table class="table table-striped" id="tasksTable">
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <a href="{{ route('home') }}">Back to Dashboard</a>
    </div>
    <!-- Add Task Modal -->
    <div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="addTaskModalLabel" aria-hidden="true"
        data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTaskModalLabel">Add New Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('tasks.store') }}" method="POST" enctype="multipart/form-data" id="addTaskForm">
                    {{-- CSRF Token --}}
                    @csrf
                    <input type="hidden" name="project_id" value="{{ $project->id }}">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Task Name</label>
                            <input type="text" class="form-control" id="name" name="name">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="due_date" class="form-label">Due Date</label>
                            <input type="date" class="form-control" id="due_date" name="due_date">
                        </div>
                        <div class="mb-3">
                            <label for="priority" class="form-label">Priority</label>
                            <select class="form-select" id="priority" name="priority">
                                <option value="" disabled selected>Select Priority</option>
                                @foreach (App\Enums\PriorityEnum::cases() as $item)
                                    <option value="{{ $item->value }}">{{ $item->label() }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="" disabled selected>Select Status</option>
                                @foreach (App\Enums\TaskStatusEnum::cases() as $item)
                                    <option value="{{ $item->value }}">{{ $item->label() }}</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- URL --}}
                        <div class="mb-3">
                            <label for="url" class="form-label">Task URL</label>
                            <input type="text" class="form-control" id="url" name="url"
                                placeholder="https://example.com">
                        </div>
                        {{-- File Upload --}}
                        <div class="mb-3">
                            <label for="image" class="form-label">Upload Image</label>
                            <input type="file" class="form-control" id="image" name="image"
                                accept=".jpg,.jpeg,.png">
                            <small class="form-text text-muted">Upload a file (jpg, jpeg, png)</small>
                            <div id="filePreview" class="mt-2"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="saveTaskButton">Save Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#tasksTable').DataTable({
                responsive: true,
                searchDelay: 500,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('tasks.index') }}",
                    data: function(d) {
                        d.project_id = {{ $project->id }};
                    }
                },
                columns: [{
                        data: 'name'
                    },
                    {
                        data: 'status'
                    },
                    {
                        data: 'due_date'
                    },
                    {
                        data: 'priority'
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                columnDefs: [{
                        targets: 0,
                        title: "Task Name",
                        orderable: true,
                        render: function(data, type, row) {
                            return `<a href="${row.url}" target="_blank">${data}</a>`;
                        }
                    }, {
                        targets: 1,
                        title: "Status",
                        orderable: true,
                    }, {
                        targets: 2,
                        title: "Due Date",
                        orderable: true,

                    },
                    {
                        targets: 3,
                        title: "Priority",
                        orderable: true,
                    }
                ],
                order: [
                    [0, 'asc']
                ],
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
            });

            // form validation for the add task modal
            $('#addTaskModal form').validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 2,
                        maxlength: 100
                    },
                    description: {
                        required: true,
                        minlength: 5,
                        maxlength: 1000
                    },
                    due_date: {
                        required: true,
                        date: true
                    },
                    priority: {
                        required: true
                    },
                    status: {
                        required: true
                    },
                    url: {
                        required: true,
                        url: true
                    },
                    file: {
                        required: true,
                    }
                },
                messages: {
                    name: {
                        required: "Please enter a task name",
                        minlength: "Task name must be at least 2 characters long",
                        maxlength: "Task name cannot exceed 100 characters"
                    },
                    description: {
                        required: "Please enter a task description",
                        minlength: "Description must be at least 5 characters long",
                        maxlength: "Description cannot exceed 1000 characters"
                    },
                    due_date: {
                        required: "Please select a due date",
                        date: "Please enter a valid date"
                    },
                    priority: {
                        required: "Please select a priority level"
                    },
                    status: {
                        required: "Please select a status"
                    },
                    url: {
                        required: "Please enter a URL",
                        url: "Please enter a valid URL"
                    },
                    file: {
                        required: "Please upload a file",
                        extension: "File must be in jpg, jpeg, png, or pdf format"
                    }
                },
                errorElement: 'span',
                errorClass: 'invalid-feedback',
                highlight: function(element) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element) {
                    $(element).removeClass('is-invalid');
                },
                errorPlacement: function(error, element) {
                    error.insertAfter(element);
                }
            });

            // File preview
            $('#image').on('change', function() {
                var file = this.files[0];
                if (file) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#filePreview').html('<img src="' + e.target.result +
                            '" class="img-thumbnail" width="100">');
                    }
                    reader.readAsDataURL(file);
                } else {
                    $('#filePreview').html('');
                }
            });
            // Handle Add Task Modal Submit via ajax
            $('#addTaskModal form').on('submit', function(e) {
                e.preventDefault();
                if (!$(this).valid()) {
                    return;
                }
                var formData = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: "{{ route('tasks.store') }}",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        // Handle success response
                        $('#addTaskModal').modal('hide');
                        // Show success message
                        alert('Task added successfully!');
                        location.reload();
                    },
                    error: function(xhr) {
                        // Handle error response
                        console.log(xhr.responseText);
                    }
                });
            });
        });
    </script>
@endpush
