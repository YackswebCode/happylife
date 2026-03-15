@extends('layouts.admin')

@section('title', 'Announcement Details')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-dark-gray fw-bold">{{ $announcement->title }}</h2>
        <div>
            <a href="{{ route('admin.announcements.edit', $announcement) }}" class="btn btn-primary">
                <i class="bi bi-pencil me-2"></i>Edit
            </a>
            <a href="{{ route('admin.announcements.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="fw-bold mb-0">Announcement Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th style="width: 120px;">Title:</th>
                            <td>{{ $announcement->title }}</td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                @if($announcement->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Created:</th>
                            <td>{{ $announcement->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Last Updated:</th>
                            <td>{{ $announcement->updated_at->format('d M Y, H:i') }}</td>
                        </tr>
                    </table>

                    <hr>

                    <h6 class="fw-bold">Content:</h6>
                    <div class="p-3 bg-light rounded">
                        {!! nl2br(e($announcement->content)) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection