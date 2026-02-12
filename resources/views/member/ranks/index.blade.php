@extends('layouts.member')

@section('title', 'Ranks & Achievements - Happylife Multipurpose Int\'l')

@push('styles')
<style>
    .rank-card {
        border-radius: 16px;
        border: none;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        transition: transform 0.3s, box-shadow 0.3s;
        overflow: hidden;
        position: relative;
    }
    .rank-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.15);
    }
    .rank-card.current-rank {
        border: 2px solid var(--color-red);
        box-shadow: 0 0 0 4px rgba(230,51,35,0.2);
    }
    .rank-header {
        padding: 20px;
        color: white;
        font-weight: 700;
        text-align: center;
    }
    .rank-header.bg-gradient-red {
        background: linear-gradient(135deg, var(--color-red) 0%, #f05c4e 100%);
    }
    .rank-header.bg-gradient-teal {
        background: linear-gradient(135deg, var(--color-teal-blue) 0%, #3DB7D6 100%);
    }
    .rank-header.bg-gradient-cyan {
        background: linear-gradient(135deg, #3DB7D6 0%, #1FA3C4 100%);
    }
    .rank-reward {
        font-size: 1.4rem;
        font-weight: 800;
        color: var(--color-red);
    }
    .rank-pv-requirement {
        display: inline-block;
        padding: 5px 15px;
        background: rgba(230,51,35,0.1);
        border-radius: 30px;
        color: var(--color-red);
        font-weight: 600;
    }
    .rank-locked {
        opacity: 0.7;
        filter: grayscale(0.3);
    }
    .progress-bar-rank {
        background-color: var(--color-teal-blue);
    }
    .badge-current {
        position: absolute;
        top: 15px;
        right: 15px;
        background: var(--color-red);
        color: white;
        padding: 5px 15px;
        border-radius: 30px;
        font-size: 0.75rem;
        font-weight: 600;
        z-index: 2;
    }
    .badge-achieved {
        position: absolute;
        top: 15px;
        right: 15px;
        background: #28a745;
        color: white;
        padding: 5px 15px;
        border-radius: 30px;
        font-size: 0.75rem;
        font-weight: 600;
        z-index: 2;
    }
    .claim-button {
        background: var(--color-red);
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 50px;
        font-weight: 600;
        transition: all 0.3s;
    }
    .claim-button:hover {
        background: #d62a1a;
        transform: scale(1.05);
    }
    .other-reward {
        background: rgba(255,193,7,0.1);
        border-left: 4px solid #ffc107;
        padding: 10px 15px;
        border-radius: 8px;
        font-size: 0.9rem;
    }
</style>
@endpush

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-2 text-dark-gray">Ranks & Achievements</h1>
                <p class="text-muted mb-0">Climb the ranks and earn exclusive rewards.</p>
            </div>
            <div>
                <span class="badge bg-gradient-teal text-white badge-custom">
                    <i class="bi bi-trophy-fill me-1"></i> Current PV: {{ number_format($user->total_pv, 1) }}
                </span>
            </div>
        </div>
    </div>
</div>

<!-- ========== SESSION MESSAGES ========== -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
        <i class="bi bi-check-circle-fill me-2 fs-5"></i> 
        <div class="flex-grow-1">{{ session('success') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i> 
        <div class="flex-grow-1">{{ session('error') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('warning'))
    <div class="alert alert-warning alert-dismissible fade show d-flex align-items-center" role="alert">
        <i class="bi bi-exclamation-circle-fill me-2 fs-5"></i> 
        <div class="flex-grow-1">{{ session('warning') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('info'))
    <div class="alert alert-info alert-dismissible fade show d-flex align-items-center" role="alert">
        <i class="bi bi-info-circle-fill me-2 fs-5"></i> 
        <div class="flex-grow-1">{{ session('info') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
<!-- ========== END SESSION MESSAGES ========== -->

<!-- Current Rank & Progress Card -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <h5 class="text-dark-gray mb-2">
                            <i class="bi bi-award-fill text-red me-2"></i> Your Current Rank
                        </h5>
                        @if($currentRank)
                            <h2 class="display-6 fw-bold text-red">{{ $currentRank->name }}</h2>
                            <p class="text-muted mb-0">Level {{ $currentRank->level }} Achiever</p>
                        @else
                            <h2 class="display-6 fw-bold text-dark-gray">No Rank Yet</h2>
                            <p class="text-muted mb-0">Start building your network to achieve your first rank.</p>
                        @endif
                    </div>
                    <div class="col-lg-6">
                        @if($nextRank)
                            <div class="bg-light p-4 rounded">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-semibold">Next Rank: {{ $nextRank->name }}</span>
                                    <span class="text-red fw-bold">{{ number_format($user->total_pv, 1) }} / {{ number_format($nextRank->required_pv, 1) }} PV</span>
                                </div>
                                <div class="progress" style="height: 12px;">
                                    <div class="progress-bar progress-bar-rank" role="progressbar" 
                                         style="width: {{ $progress }}%;" 
                                         aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <small class="text-muted">Progress: {{ $progress }}%</small>
                                    @if($canClaim)
                                        <form action="{{ route('member.ranks.claim') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-danger claim-button btn-sm">
                                                <i class="bi bi-gem me-1"></i> Claim {{ $nextRank->name }}
                                            </button>
                                        </form>
                                    @elseif($currentRank && $currentRank->level < $nextRank->level)
                                        <small class="text-warning">
                                            <i class="bi bi-hourglass-split me-1"></i> 
                                            {{ number_format($nextRank->required_pv - $user->total_pv, 1) }} PV needed
                                        </small>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="bg-light p-4 rounded text-center">
                                <i class="bi bi-trophy-fill fs-1 text-warning mb-2"></i>
                                <h6 class="mb-0">Congratulations!</h6>
                                <p class="text-muted mb-0">You have achieved the highest rank.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- All Ranks Display -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 text-dark-gray">
                    <i class="bi bi-bar-chart-steps text-red me-2"></i> Career Path
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    @foreach($ranks as $rank)
                        @php
                            $isAchieved = $currentRank && $currentRank->level >= $rank->level;
                            $isCurrent = $currentRank && $currentRank->id == $rank->id;
                            $isLocked = !$isAchieved && !$isCurrent;
                        @endphp
                        <div class="col-xl-3 col-lg-4 col-md-6">
                            <div class="rank-card card h-100 {{ $isCurrent ? 'current-rank' : '' }} {{ $isLocked ? 'rank-locked' : '' }}">
                                @if($isCurrent)
                                    <span class="badge-current">CURRENT</span>
                                @elseif($isAchieved)
                                    <span class="badge-achieved"><i class="bi bi-check-circle-fill me-1"></i> ACHIEVED</span>
                                @endif
                                <div class="rank-header bg-gradient-{{ $rank->level % 2 == 0 ? 'teal' : 'red' }}">
                                    <h4 class="mb-0 text-white">{{ $rank->name }}</h4>
                                    <small class="text-white-50">Level {{ $rank->level }}</small>
                                </div>
                                <div class="card-body">
                                    <div class="text-center mb-3">
                                        <span class="rank-pv-requirement">
                                            <i class="bi bi-lightning-charge-fill me-1"></i> 
                                            {{ number_format($rank->required_pv, 1) }} PV Required
                                        </span>
                                    </div>
                                    <div class="rank-reward text-center mb-3">
                                        ₦{{ number_format($rank->cash_reward, 2) }}
                                        @if($rank->other_reward)
                                            <small class="d-block text-muted mt-1">+ {{ $rank->other_reward }}</small>
                                        @endif
                                    </div>
                                    @if($rank->description)
                                        <p class="text-muted small mb-0">{{ $rank->description }}</p>
                                    @endif
                                    @if($rank->other_reward)
                                        <div class="other-reward mt-3">
                                            <i class="bi bi-gift-fill text-warning me-1"></i> 
                                            {{ $rank->other_reward }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bonus Summary -->
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 text-dark-gray"><i class="bi bi-cash-stack text-success me-2"></i> Total Rank Bonuses Earned</h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="bi bi-wallet2 fs-1 text-teal-blue"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h2 class="text-teal-blue mb-0">₦{{ number_format($user->rank_bonus_total ?? 0, 2) }}</h2>
                        <small class="text-muted">Lifetime rank bonus earnings</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 text-dark-gray"><i class="bi bi-trophy-fill text-warning me-2"></i> Next Rank Achievement</h5>
            </div>
            <div class="card-body">
                @if($nextRank)
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="bi bi-award fs-1 text-warning"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1">{{ $nextRank->name }}</h5>
                            <p class="text-muted mb-0">Need {{ number_format($nextRank->required_pv - $user->total_pv, 1) }} more PV</p>
                        </div>
                    </div>
                @else
                    <p class="text-muted mb-0">You have reached the highest rank. Congratulations!</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Optional: Add Alpine.js for dynamic interactions
    document.addEventListener('alpine:init', function() {
        // Any rank-related Alpine components
    });
</script>
@endpush