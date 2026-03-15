@extends('layouts.admin')

@section('title', 'VTU API Settings')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-dark-gray fw-bold">VTU API Settings</h2>
        <a href="{{ route('admin.vtu.api-settings.create') }}" class="btn btn-red">
            <i class="bi bi-plus-circle me-2"></i>Add Setting
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Base URL</th>
                        <th>API Key (masked)</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($settings as $setting)
                    <tr>
                        <td>#{{ $setting->id }}</td>
                        <td>{{ $setting->name }}</td>
                        <td><code>{{ $setting->base_url }}</code></td>
                        <td>••••••{{ substr($setting->api_key, -4) }}</td>
                        <td>
                            @if($setting->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.vtu.api-settings.edit', $setting) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('admin.vtu.api-settings.destroy', $setting) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this API setting?');">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-4 text-muted">No API settings found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($settings->hasPages())
        <div class="card-footer bg-white border-0">
            {{ $settings->links() }}
        </div>
        @endif
    </div>
</div>
@endsection