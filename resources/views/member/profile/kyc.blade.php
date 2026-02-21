@extends('layouts.member')

@section('title', 'KYC Verification - Happylife Multipurpose Int\'l')

@section('content')
<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-2 text-dark-gray">KYC Verification</h1>
                <p class="text-muted mb-0">Submit your identity documents to verify your account.</p>
            </div>
            <div>
                <a href="{{ route('member.profile.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Back to Profile
                </a>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row">
    <div class="col-lg-8 mx-auto">
        <!-- Current KYC Status Card -->
        @if($kyc)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 text-dark-gray"><i class="bi bi-shield-check text-teal-blue me-2"></i>Current Verification Status</h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        @if($kyc->status == 'approved')
                            <span class="badge bg-success p-3 rounded-circle"><i class="bi bi-check-lg fs-4"></i></span>
                        @elseif($kyc->status == 'pending')
                            <span class="badge bg-warning p-3 rounded-circle"><i class="bi bi-hourglass-split fs-4"></i></span>
                        @elseif($kyc->status == 'rejected')
                            <span class="badge bg-danger p-3 rounded-circle"><i class="bi bi-x-lg fs-4"></i></span>
                        @endif
                    </div>
                    <div>
                        <h4 class="mb-1">
                            @if($kyc->status == 'approved')
                                Verified
                            @elseif($kyc->status == 'pending')
                                Pending Review
                            @elseif($kyc->status == 'rejected')
                                Rejected
                            @endif
                        </h4>
                        <p class="text-muted mb-1">
                            Submitted: {{ $kyc->submitted_at ? $kyc->submitted_at->format('d M Y, h:i A') : 'N/A' }}
                        </p>
                        @if($kyc->status == 'rejected' && $kyc->admin_comment)
                            <div class="alert alert-danger mt-2 mb-0 py-2">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i> 
                                <strong>Reason:</strong> {{ $kyc->admin_comment }}
                            </div>
                        @endif
                        @if($kyc->status == 'approved' && $kyc->verified_at)
                            <small class="text-success">Verified on {{ $kyc->verified_at->format('d M Y') }}</small>
                        @endif
                    </div>
                </div>

                @if($kyc->status == 'approved')
                    <hr>
                    <div class="alert alert-success mb-0">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        Your identity has been verified. You now have full access to all member features.
                    </div>
                @endif
            </div>
        </div>
        @endif

        <!-- KYC Submission Form -->
        <div class="card border-0 shadow-sm">
            <div class="card-header-custom rounded-top">
                <h5 class="mb-0 text-white"><i class="bi bi-file-earmark-person me-2"></i>Identity Verification Form</h5>
            </div>
            <div class="card-body p-4">
                @if($kyc && $kyc->status == 'approved')
                    <div class="text-center py-4">
                        <i class="bi bi-patch-check-fill text-success fs-1 mb-3"></i>
                        <h5>You are already verified</h5>
                        <p class="text-muted">No further action required.</p>
                    </div>
                @else
                    <form method="POST" action="{{ $kyc && $kyc->status == 'rejected' ? route('member.kyc.update', $kyc) : route('member.kyc.store') }}" 
                          enctype="multipart/form-data">
                        @csrf
                        @if($kyc && $kyc->status == 'rejected')
                            @method('PUT')
                        @endif

                        <!-- Document Type -->
                        <div class="mb-3">
                            <label for="document_type" class="form-label fw-semibold">Document Type <span class="text-danger">*</span></label>
                            <select class="form-select @error('document_type') is-invalid @enderror" 
                                    id="document_type" name="document_type" required>
                                <option value="">Select document type</option>
                                <option value="national_id" {{ old('document_type', $kyc->document_type ?? '') == 'national_id' ? 'selected' : '' }}>National ID Card</option>
                                <option value="passport" {{ old('document_type', $kyc->document_type ?? '') == 'passport' ? 'selected' : '' }}>International Passport</option>
                                <option value="driver_license" {{ old('document_type', $kyc->document_type ?? '') == 'driver_license' ? 'selected' : '' }}>Driver's License</option>
                                <option value="voter_id" {{ old('document_type', $kyc->document_type ?? '') == 'voter_id' ? 'selected' : '' }}>Voter's Card</option>
                                <option value="utility_bill" {{ old('document_type', $kyc->document_type ?? '') == 'utility_bill' ? 'selected' : '' }}>Utility Bill (Proof of Address)</option>
                            </select>
                            @error('document_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- ID Number -->
                        <div class="mb-3">
                            <label for="id_number" class="form-label fw-semibold">Document Number / ID</label>
                            <input type="text" class="form-control @error('id_number') is-invalid @enderror" 
                                   id="id_number" name="id_number" value="{{ old('id_number', $kyc->id_number ?? '') }}">
                            @error('id_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Issue & Expiry Dates -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="issue_date" class="form-label fw-semibold">Issue Date</label>
                                <input type="date" class="form-control @error('issue_date') is-invalid @enderror" 
                                       id="issue_date" name="issue_date" value="{{ old('issue_date', $kyc->issue_date ?? '') }}">
                                @error('issue_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="expiry_date" class="form-label fw-semibold">Expiry Date</label>
                                <input type="date" class="form-control @error('expiry_date') is-invalid @enderror" 
                                       id="expiry_date" name="expiry_date" value="{{ old('expiry_date', $kyc->expiry_date ?? '') }}">
                                @error('expiry_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Place of Issue -->
                        <div class="mb-3">
                            <label for="place_of_issue" class="form-label fw-semibold">Place of Issue</label>
                            <input type="text" class="form-control @error('place_of_issue') is-invalid @enderror" 
                                   id="place_of_issue" name="place_of_issue" value="{{ old('place_of_issue', $kyc->place_of_issue ?? '') }}">
                            @error('place_of_issue')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        <!-- File Uploads -->
                        <h6 class="text-dark-gray mb-3"><i class="bi bi-images me-2"></i>Document Images</h6>
                        
                        <!-- Front Image (required) -->
                        <div class="mb-3">
                            <label for="front_image" class="form-label fw-semibold">Front Side of Document <span class="text-danger">*</span></label>
                            <input type="file" class="form-control @error('front_image') is-invalid @enderror" 
                                   id="front_image" name="front_image" accept="image/*,.pdf" {{ $kyc && $kyc->front_image && $kyc->status == 'rejected' ? '' : 'required' }}>
                            <small class="text-muted">Accepted formats: JPG, PNG, PDF. Max 5MB.</small>
                            @error('front_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if($kyc && $kyc->front_image && $kyc->status == 'rejected')
                                <div class="mt-2">
                                    <span class="badge bg-light text-dark">Previously uploaded</span>
                                    <a href="{{ route('member.kyc.document', [$kyc, 'front_image']) }}" target="_blank" class="ms-2">View</a>
                                </div>
                            @endif
                        </div>

                        <!-- Back Image (optional, but recommended) -->
                        <div class="mb-3">
                            <label for="back_image" class="form-label fw-semibold">Back Side of Document</label>
                            <input type="file" class="form-control @error('back_image') is-invalid @enderror" 
                                   id="back_image" name="back_image" accept="image/*,.pdf">
                            <small class="text-muted">Accepted formats: JPG, PNG, PDF. Max 5MB.</small>
                            @error('back_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if($kyc && $kyc->back_image && $kyc->status == 'rejected')
                                <div class="mt-2">
                                    <span class="badge bg-light text-dark">Previously uploaded</span>
                                    <a href="{{ route('member.kyc.document', [$kyc, 'back_image']) }}" target="_blank" class="ms-2">View</a>
                                </div>
                            @endif
                        </div>

                        <!-- Selfie Image (optional, helps verification) -->
                        <div class="mb-4">
                            <label for="selfie_image" class="form-label fw-semibold">Selfie with ID (Optional)</label>
                            <input type="file" class="form-control @error('selfie_image') is-invalid @enderror" 
                                   id="selfie_image" name="selfie_image" accept="image/*">
                            <small class="text-muted">Upload a clear photo of yourself holding the ID. Accepted formats: JPG, PNG. Max 5MB.</small>
                            @error('selfie_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if($kyc && $kyc->selfie_image && $kyc->status == 'rejected')
                                <div class="mt-2">
                                    <span class="badge bg-light text-dark">Previously uploaded</span>
                                    <a href="{{ route('member.kyc.document', [$kyc, 'selfie_image']) }}" target="_blank" class="ms-2">View</a>
                                </div>
                            @endif
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-red px-5">
                                <i class="bi bi-send-check me-1"></i> 
                                {{ $kyc && $kyc->status == 'rejected' ? 'Resubmit' : 'Submit KYC' }}
                            </button>
                        </div>

                        <p class="text-muted small mt-3">
                            <i class="bi bi-shield-lock-fill me-1"></i> 
                            Your documents are securely stored and will only be used for identity verification.
                        </p>
                    </form>
                @endif
            </div>
        </div>

        <!-- Help Card -->
        <div class="card border-0 bg-light-teal mt-4">
            <div class="card-body p-3">
                <div class="d-flex">
                    <i class="bi bi-question-circle-fill text-teal-blue fs-4 me-3"></i>
                    <div>
                        <h6 class="mb-1">Why verify your identity?</h6>
                        <small class="text-muted">KYC verification helps us maintain a secure community and comply with regulations. Once verified, you can access all member benefits, higher commission tiers, and withdrawal privileges.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Additional style for KYC page */
    .badge.p-3 {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endpush