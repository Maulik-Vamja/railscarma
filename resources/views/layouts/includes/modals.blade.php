<!-- Edit Task Modal -->
<div class="modal fade" id="editTaskModal" tabindex="-1" role="dialog" aria-labelledby="editTaskModalLabel"
    aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTaskModalLabel">Edit Task</h5>
                <button type="button" class="close btn-close" data-dismiss="modal" data-bs-dismiss="modal"
                    aria-label="Close">

                </button>
            </div>
            <form id="editTaskForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" id="task_id" name="task_id">

                    <div class="form-group mb-3">
                        <label for="name">Task Name</label>
                        <input type="text" class="form-control" id="task_name" name="name">
                    </div>

                    <div class="form-group mb-3">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="task_description" name="description" rows="3"></textarea>
                    </div>

                    <div class="form-group mb-3">
                        <label for="due_date">Due Date</label>
                        <input type="date" class="form-control" id="task_due_date" name="due_date">
                    </div>

                    <div class="form-group mb-3">
                        <label for="task_priority">Priority</label>
                        <select class="form-select" id="task_priority" name="priority">
                            <option value="" disabled selected>Select Priority</option>
                            @foreach (App\Enums\PriorityEnum::cases() as $item)
                                <option value="{{ $item->value }}">{{ $item->label() }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="status">Status</label>
                        <select class="form-select" id="task_status" name="status">
                            <option value="" disabled selected>Select Status</option>
                            @foreach (App\Enums\TaskStatusEnum::cases() as $item)
                                <option value="{{ $item->value }}">{{ $item->label() }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="task_url">Task URL</label>
                        <input type="url" class="form-control" id="task_url" name="url"
                            placeholder="https://example.com">
                    </div>

                    {{-- Image --}}
                    <div class="form-group mb-3">
                        <label for="task_image">Image</label>
                        <input type="file" class="form-control" id="task_image" name="image">
                        <small class="form-text text-muted">Upload an image for the task.</small>
                    </div>
                    {{-- Preview existing image --}}
                    <div class="form-group mb-3">
                        <img src="#" alt="Existing Image" class="img-thumbnail" id="task_image_preview"
                            height="100" width="100">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
