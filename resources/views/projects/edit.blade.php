@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="py-2">
            <h3>Update Project</h3>
        </div>

        <div class="card bg-white">
            <div class="card-body">
                <form method="POST" action="{{ route('projects.update', $project->id) }}" enctype="multipart/form-data"
                    id="project-form">
                    {{-- CSRF Token --}}
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Project Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                value="{{ $project->name }}">
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Priority</label>
                            <select class="form-select" id="priority" name="priority">
                                <option value="" disabled selected>Select Priority</option>
                                @foreach (App\Enums\PriorityEnum::cases() as $item)
                                    <option value="{{ $item->value }}" @selected($item->value == $project->priority->value)>{{ $item->label() }}
                                    </option>
                                @endforeach
                            </select>
                            @error('priority')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="" disabled selected>Select Status</option>
                                    @foreach (App\Enums\ProjectStatusEnum::cases() as $item)
                                        <option value="{{ $item->value }}" @selected($item->value == $project->status->value)>
                                            {{ $item->label() }}</option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="url" class="form-label">Project URL</label>
                            <input type="text" class="form-control" id="url" name="url"
                                placeholder="https://example.com" value="{{ $project->url }}" required>
                            @error('url')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="start_date" name="start_date"
                                placeholder="YYYY-MM-DD" value="{{ $project->start_date->format('Y-m-d') }}">
                            @error('start_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="end_date" name="end_date"
                                placeholder="YYYY-MM-DD" value="{{ $project->end_date->format('Y-m-d') }}">
                            @error('end_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        {{-- Image --}}
                        <div class="col-md-6 mb-3">
                            <label for="image" class="form-label">Project Image</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            {{-- <div id="image-preview" class="mt-2"></div> --}}
                            <!-- Image preview will be shown here -->
                            @if ($project->image)
                                <div class="" id="image-preview" class="mt-2">
                                    <img src="{{ \Storage::url($project->image) }}" alt="Project Image"
                                        class="img-thumbnail mt-2" width="100" height="100">
                                </div>
                            @else
                                <div id="image-preview" class="mt-2"></div>
                            @endif
                            @error('image')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3">{{ $project->description }}</textarea>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-12 text-end">
                                <button type="submit" class="btn btn-primary">Update Project</button>
                                <a href="{{ route('home') }}" class="btn btn-secondary mx-2">Cancel</a>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('image').addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.width = 100;
                    img.height = 100;
                    console.log(document.getElementById('image-preview'));

                    document.getElementById('image-preview').innerHTML = '';
                    document.getElementById('image-preview').appendChild(img);
                };
                reader.readAsDataURL(file);
            }
        });

        $(document).ready(function() {
            $('#project-form').validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 3
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
                    start_date: {
                        required: true
                    },
                    end_date: {
                        required: true,
                        greaterThan: "#start_date"
                    },
                    description: {
                        required: true,
                        maxlength: 500
                    },
                    image: {
                        required: true,
                    }
                },
                messages: {
                    name: {
                        required: "Project name is required",
                        minlength: "Project name must be at least 3 characters"
                    },
                    url: {
                        url: "Please enter a valid URL"
                    },
                    end_date: {
                        greaterThan: "End date must be after start date"
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

            // Custom validator for date comparison
            $.validator.addMethod("greaterThan", function(value, element, param) {
                var startDate = $(param).val();
                if (!startDate || !value) return true;
                return new Date(value) > new Date(startDate);
            }, "End date must be after start date");
        });
    </script>
@endpush
