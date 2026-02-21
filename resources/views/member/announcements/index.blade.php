@extends('layouts.member')

@section('title', 'Announcements - Happylife Multipurpose Int\'l')

@section('content')
<div class="container-fluid px-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 text-dark-gray">
                <i class="bi bi-megaphone-fill text-red me-2"></i> Announcements
            </h1>
            <p class="text-muted">Latest updates and company news.</p>
        </div>
    </div>

    @if($announcements->count())
        <div class="row g-4">
            @foreach($announcements as $announcement)
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-header bg-white border-0 py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 text-dark-gray">
                                    {{ $announcement->title }}
                                </h5>
                                <small class="text-muted">
                                    {{ $announcement->created_at->format('M d, Y') }}
                                </small>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <p class="text-muted mb-0">
                                {{ $announcement->content }}
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-5 text-muted">
            <i class="bi bi-megaphone display-4"></i>
            <p class="mt-3">No announcements yet.</p>
        </div>
    @endif

</div>
@endsection