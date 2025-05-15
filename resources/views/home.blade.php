@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="py-2">
            <h3>Dashboard</h3>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row py-2">
            <div class="col-md-4 mb-4">
                <div class="card bg-white text-dark">
                    <div class="card-body">
                        <h1 class="card-title text-center">{{ $projectCount ?? 0 }}</h1>
                        <p class="card-text text-center">Total Projects</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card bg-white text-dark">
                    <div class="card-body">
                        <h1 class="card-title text-center">{{ $activeProjectsCount ?? 0 }}</h1>
                        <p class="card-text text-center">Active Projects</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card bg-white text-dark">
                    <div class="card-body">
                        <h1 class="card-title text-center">{{ $inactiveProjectsCount ?? 0 }}</h1>
                        <p class="card-text text-center">Inactive Projects</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row py-2">
            <div class="col-12">
                <div class="card bg-white">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">My Projects</h4>
                        <div class="card-toolbar">
                            <a href="{{ route('projects.create') }}" class="btn btn-primary">Create Project</a>
                        </div>
                    </div>
                    <div class="card-body">

                        <table class="table table-bordered table-striped table-hover " id="projectsTable">
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            oTable = $('#projectsTable').DataTable({
                responsive: true,
                searchDelay: 500,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('projects.index') }}",
                    data: {
                        columnsDef: [
                            'created_at',
                            'name',
                            'description',
                            'priority',
                            'status',
                            'start_date',
                            'end_date',
                            'action'
                        ],
                    },
                },
                columns: [{
                        data: 'created_at',
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'description'
                    },

                    {
                        data: 'priority'
                    },
                    {
                        data: 'status'
                    },
                    {
                        data: 'start_date'
                    },
                    {
                        data: 'end_date'
                    },
                    {
                        data: 'action',
                        responsivePriority: -1
                    },
                ],
                columnDefs: [
                    // Specify columns titles here...
                    {
                        targets: 0,
                        title: "Created At",
                        visible: false,
                        orderable: false
                    },
                    {
                        targets: 1,
                        title: "Project Name",
                        orderable: false
                    },
                    {
                        targets: 2,
                        title: 'Description',
                        orderable: true
                    },

                    {
                        targets: 3,
                        title: 'Priority',
                        orderable: false
                    },
                    {
                        targets: 4,
                        title: 'Status',
                        orderable: false
                    },
                    {
                        targets: 5,
                        title: 'Start Date',
                        orderable: false
                    },
                    {
                        targets: 6,
                        title: 'End Date',
                        orderable: false
                    },

                    // Action buttons
                    {
                        targets: -1,
                        title: 'Action',
                        orderable: false
                    },
                ],
                order: [
                    [1, 'asc']
                ],
                lengthMenu: [
                    [10, 20, 50, 100],
                    [10, 20, 50, 100]
                ],
                pageLength: 10,
            });
        });
    </script>
@endpush
