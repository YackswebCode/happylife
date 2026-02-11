@extends('layouts.member')

@section('title', 'Product Categories - Happylife')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('member.shopping.index') }}" class="btn btn-outline-happylife-teal me-3">
            ← Back to Mall
        </a>
        <h1 class="h2 fw-bold text-happylife-dark mb-0">Product Categories</h1>
    </div>

    <div class="row g-4">
        @forelse($categories ?? [] as $category)
            <div class="col-md-6 col-lg-4">
                <a href="{{ route('member.shopping.index', ['category' => $category->id]) }}" class="text-decoration-none">
                    <div class="card product-card h-100 p-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                @if($category->image)
                                    <img src="{{ asset('storage/'.$category->image) }}" alt="{{ $category->name }}" width="80" height="80" style="object-fit: cover; border-radius: 12px;">
                                @else
                                    <div class="bg-happylife-light d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; border-radius: 12px;">
                                        <span class="fs-1 text-secondary">📦</span>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="fw-bold text-happylife-dark mb-1">{{ $category->name }}</h5>
                                <p class="text-secondary small mb-2">{{ $category->description ?? '' }}</p>
                                <span class="badge bg-happylife-teal">{{ $category->products_count ?? 0 }} products</span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5 text-secondary">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                    </svg>
                    <h4 class="mt-3">No categories yet</h4>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection