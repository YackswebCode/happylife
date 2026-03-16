@extends('layouts.admin')

@section('title', 'Site Settings')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-dark-gray fw-bold">Site Settings</h2>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 pt-4">
            <ul class="nav nav-tabs card-header-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#general">General</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#hero">Hero Section</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#features">Features</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#steps">How It Works</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#faqs">FAQs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#cta">CTA Section</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="tab-content">
                    {{-- General Tab --}}
                    <div class="tab-pane fade show active" id="general">
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="mb-3">
                                    <label for="site_name" class="form-label">Site Name</label>
                                    <input type="text" class="form-control" id="site_name" name="site_name" value="{{ old('site_name', $settings['site_name'] ?? '') }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="logo" class="form-label">Logo</label>
                                    @if(!empty($settings['logo']))
                                        <div class="mb-2">
                                            <img src="{{ asset('storage/'.$settings['logo']) }}" alt="Logo" style="max-height: 60px;">
                                        </div>
                                    @endif
                                    <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                                    <small class="text-muted">Leave blank to keep current.</small>
                                </div>
                                <div class="mb-3">
                                    <label for="favicon" class="form-label">Favicon</label>
                                    @if(!empty($settings['favicon']))
                                        <div class="mb-2">
                                            <img src="{{ asset('storage/'.$settings['favicon']) }}" alt="Favicon" style="max-height: 32px;">
                                        </div>
                                    @endif
                                    <input type="file" class="form-control" id="favicon" name="favicon" accept=".ico,.png">
                                    <small class="text-muted">Recommended: 32x32px .ico or .png.</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Hero Tab --}}
                    <div class="tab-pane fade" id="hero">
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="mb-3">
                                    <label for="hero_badge" class="form-label">Hero Badge</label>
                                    <input type="text" class="form-control" id="hero_badge" name="hero_badge" value="{{ old('hero_badge', $settings['hero_badge'] ?? '') }}">
                                </div>
                                <div class="mb-3">
                                    <label for="hero_title" class="form-label">Hero Title (HTML allowed)</label>
                                    <textarea class="form-control" id="hero_title" name="hero_title" rows="2">{{ old('hero_title', $settings['hero_title'] ?? '') }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="hero_subtitle" class="form-label">Hero Subtitle</label>
                                    <textarea class="form-control" id="hero_subtitle" name="hero_subtitle" rows="3">{{ old('hero_subtitle', $settings['hero_subtitle'] ?? '') }}</textarea>
                                </div>
                                <h5 class="mt-4">Stats</h5>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="stat1_value" class="form-label">Stat 1 Value</label>
                                        <input type="text" class="form-control" id="stat1_value" name="stat1_value" value="{{ old('stat1_value', $settings['stat1_value'] ?? '') }}">
                                        <label for="stat1_label" class="form-label mt-2">Stat 1 Label</label>
                                        <input type="text" class="form-control" id="stat1_label" name="stat1_label" value="{{ old('stat1_label', $settings['stat1_label'] ?? '') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="stat2_value" class="form-label">Stat 2 Value</label>
                                        <input type="text" class="form-control" id="stat2_value" name="stat2_value" value="{{ old('stat2_value', $settings['stat2_value'] ?? '') }}">
                                        <label for="stat2_label" class="form-label mt-2">Stat 2 Label</label>
                                        <input type="text" class="form-control" id="stat2_label" name="stat2_label" value="{{ old('stat2_label', $settings['stat2_label'] ?? '') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="stat3_value" class="form-label">Stat 3 Value</label>
                                        <input type="text" class="form-control" id="stat3_value" name="stat3_value" value="{{ old('stat3_value', $settings['stat3_value'] ?? '') }}">
                                        <label for="stat3_label" class="form-label mt-2">Stat 3 Label</label>
                                        <input type="text" class="form-control" id="stat3_label" name="stat3_label" value="{{ old('stat3_label', $settings['stat3_label'] ?? '') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Features Tab --}}
                    <div class="tab-pane fade" id="features">
                        <p class="text-muted">Edit the four features. Icons are fixed.</p>
                        @php
                            $features = $settings['landing_features'] ?? [];
                        @endphp
                        @for($i = 0; $i < 4; $i++)
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5>Feature {{ $i+1 }}</h5>
                                    <input type="hidden" name="features[{{ $i }}][icon]" value="{{ $features[$i]['icon'] ?? '' }}">
                                    <div class="mb-2">
                                        <label class="form-label">Title</label>
                                        <input type="text" class="form-control" name="features[{{ $i }}][title]" value="{{ old("features.$i.title", $features[$i]['title'] ?? '') }}" required>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label">Description</label>
                                        <textarea class="form-control" name="features[{{ $i }}][description]" rows="2" required>{{ old("features.$i.description", $features[$i]['description'] ?? '') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>

                    {{-- Steps Tab --}}
                    <div class="tab-pane fade" id="steps">
                        <p class="text-muted">Edit the four "How It Works" steps.</p>
                        @php
                            $steps = $settings['landing_steps'] ?? [];
                        @endphp
                        @for($i = 0; $i < 4; $i++)
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5>Step {{ $i+1 }}</h5>
                                    <input type="hidden" name="steps[{{ $i }}][icon]" value="{{ $steps[$i]['icon'] ?? '' }}">
                                    <div class="mb-2">
                                        <label class="form-label">Title</label>
                                        <input type="text" class="form-control" name="steps[{{ $i }}][title]" value="{{ old("steps.$i.title", $steps[$i]['title'] ?? '') }}" required>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label">Description</label>
                                        <textarea class="form-control" name="steps[{{ $i }}][description]" rows="2" required>{{ old("steps.$i.description", $steps[$i]['description'] ?? '') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>

                    {{-- FAQs Tab --}}
                    <div class="tab-pane fade" id="faqs">
                        <p class="text-muted">Manage FAQs. You can add more by copying the structure, but for simplicity we provide 6.</p>
                        @php
                            $faqs = $settings['landing_faqs'] ?? [];
                        @endphp
                        @for($i = 0; $i < 6; $i++)
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5>FAQ {{ $i+1 }}</h5>
                                    <div class="mb-2">
                                        <label class="form-label">Question</label>
                                        <input type="text" class="form-control" name="faqs[{{ $i }}][question]" value="{{ old("faqs.$i.question", $faqs[$i]['question'] ?? '') }}" required>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label">Answer</label>
                                        <textarea class="form-control" name="faqs[{{ $i }}][answer]" rows="3" required>{{ old("faqs.$i.answer", $faqs[$i]['answer'] ?? '') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>

                    {{-- CTA Tab --}}
                    <div class="tab-pane fade" id="cta">
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="mb-3">
                                    <label for="cta_title" class="form-label">CTA Title</label>
                                    <input type="text" class="form-control" id="cta_title" name="cta_title" value="{{ old('cta_title', $settings['cta_title'] ?? '') }}">
                                </div>
                                <div class="mb-3">
                                    <label for="cta_subtitle" class="form-label">CTA Subtitle</label>
                                    <textarea class="form-control" id="cta_subtitle" name="cta_subtitle" rows="2">{{ old('cta_subtitle', $settings['cta_subtitle'] ?? '') }}</textarea>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="cta_button_text" class="form-label">Primary Button Text</label>
                                        <input type="text" class="form-control" id="cta_button_text" name="cta_button_text" value="{{ old('cta_button_text', $settings['cta_button_text'] ?? '') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="cta_button_link" class="form-label">Primary Button Link</label>
                                        <input type="text" class="form-control" id="cta_button_link" name="cta_button_link" value="{{ old('cta_button_link', $settings['cta_button_link'] ?? '') }}">
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <label for="cta_secondary_text" class="form-label">Secondary Button Text</label>
                                        <input type="text" class="form-control" id="cta_secondary_text" name="cta_secondary_text" value="{{ old('cta_secondary_text', $settings['cta_secondary_text'] ?? '') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="cta_secondary_link" class="form-label">Secondary Button Link</label>
                                        <input type="text" class="form-control" id="cta_secondary_link" name="cta_secondary_link" value="{{ old('cta_secondary_link', $settings['cta_secondary_link'] ?? '') }}">
                                    </div>
                                </div>
                                <h5 class="mt-4">CTA Features (list of items)</h5>
                                @php
                                    $ctaFeatures = $settings['cta_features'] ?? ['Secure Platform', '24/7 Support', 'Instant Withdrawals'];
                                @endphp
                                @for($j = 0; $j < 3; $j++)
                                    <div class="mb-2">
                                        <input type="text" class="form-control" name="cta_features[{{ $j }}]" value="{{ old("cta_features.$j", $ctaFeatures[$j] ?? '') }}" placeholder="Feature {{ $j+1 }}">
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-red px-4 py-2">
                        <i class="bi bi-save me-2"></i>Save All Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection