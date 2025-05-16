<!-- Task View Modal Body -->

<div class="row mb-3">
    <div class="col-6">
        <label class="fw-bold">Task Name:</label>
        {{-- Task title with link --}}
        <a href="{{ $task->url ?? 'javascript:void(0)' }}" class="text-decoration-none">
            <h5 class="fw-bold">{{ $task->title ?? 'Task Title' }}</h5>
        </a>
    </div>
    <div class="col-6">
        <label class="fw-bolder">Created At:</label>
        <div>{{ $task->created_at->format('M d, Y H:i:s') }}</div>
    </div>

</div>

<div class="row mb-3">
    <div class="col-md-6">
        <label class="fw-bold">Status:</label>
        <div class="py-1">
            {{ $task->status->label() }}
        </div>
    </div>
    <div class="col-md-6">
        <label class="fw-bold">Priority:</label>
        <div class="py-1">

            {{ $task->priority->label() }}

        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <label class="fw-bold">Due Date:</label>
        <div>{{ isset($task->due_date) ? date('M d, Y', strtotime($task->due_date)) : 'Not set' }}</div>
    </div>
    <div class="col-md-6">
        <label class="fw-bold">Last Updated:</label>
        <div>{{ isset($task->updated_at) ? $task->updated_at->format('M d, Y H:i') : 'Not updated' }}</div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-12">
        <label class="fw-bold">Description:</label>
        <div class="bg-light rounded">
            {!! nl2br(e($task->description ?? 'No description provided')) !!}
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-12">
        <label class="fw-bold">Attached Image:</label>
        <div>
            @if ($task->image)
                <img src="{{ $task->image }}" alt="Task Image" class="img-thumbnail"
                    style="max-width: auto; height: 200px;">
            @else
                <span class="text-muted">No image attached</span>
            @endif
        </div>
    </div>
</div>
