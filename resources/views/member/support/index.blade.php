@extends('layouts.member')

@section('title', 'Support - Happylife Multipurpose Int\'l')

@section('content')
<div class="container-fluid px-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 text-dark-gray">
                <i class="bi bi-headset text-red me-2"></i> Support Center
            </h1>
            <p class="text-muted">Contact our support team for assistance.</p>
        </div>
    </div>

    @if($supports->count())
        <div class="row g-4">
            @foreach($supports as $support)
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="mb-0 text-dark-gray">
                                <i class="bi bi-info-circle-fill text-teal-blue me-2"></i>
                                {{ $support->title }}
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <p class="text-muted">{{ $support->content }}</p>

                            @if($support->phone)
                                <p class="mb-1">
                                    <i class="bi bi-telephone-fill text-red me-2"></i>
                                    {{ $support->phone }}
                                </p>
                            @endif

                            @if($support->email)
                                <p class="mb-0">
                                    <i class="bi bi-envelope-fill text-teal-blue me-2"></i>
                                    {{ $support->email }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-5 text-muted">
            <i class="bi bi-headset display-4"></i>
            <p class="mt-3">No support information available yet.</p>
        </div>
    @endif

</div>
@endsection