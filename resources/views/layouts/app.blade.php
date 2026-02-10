<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Happylife Multipurpose Int\'l')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        :root {
            --color-red: #E63323;
            --color-teal-blue: #1FA3C4;
            --color-dark-gray: #333333;
            --color-light-gray: #E6E6E6;
            --color-soft-cyan: #3DB7D6;
        }

        .bg-red-happylife { background-color: var(--color-red); }
        .text-red-happylife { color: var(--color-red); }
        .border-red-happylife { border-color: var(--color-red); }

        .bg-teal-blue { background-color: var(--color-teal-blue); }
        .text-teal-blue { color: var(--color-teal-blue); }

        .bg-soft-cyan { background-color: var(--color-soft-cyan); }
    </style>
</head>
<body class="font-inter">

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <div class="bg-red-happylife rounded-circle d-flex justify-content-center align-items-center me-2" style="width:40px; height:40px;">
                    <span class="text-white fw-bold fs-5">H</span>
                </div>
                <span class="fs-4 fw-bold text-dark">Happylife<span class="text-red-happylife">.</span></span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center">
                    <li class="nav-item">
                        <a class="nav-link text-secondary" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-secondary" href="{{ route('about') }}">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-secondary" href="{{ route('packages') }}">Packages</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-secondary" href="{{ route('faq') }}">FAQ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-secondary" href="{{ route('contact') }}">Contact</a>
                    </li>
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-outline-secondary me-2" href="{{ route('login') }}">Login</a>
                        <a class="btn bg-red-happylife text-white" href="{{ route('register') }}">Get Started</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <main class="py-4">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white pt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-3 mb-4">
                    <a href="{{ route('home') }}" class="d-flex align-items-center mb-3 text-decoration-none">
                        <div class="bg-red-happylife rounded-circle d-flex justify-content-center align-items-center me-2" style="width:40px; height:40px;">
                            <span class="text-white fw-bold fs-5">H</span>
                        </div>
                        <span class="fs-5 fw-bold text-white">Happylife<span class="text-red-happylife">.</span></span>
                    </a>
                    <p class="text-secondary small">Hybrid MLM + E-Commerce + Reward + VTU Services Platform</p>
                </div>

                <div class="col-md-3 mb-4">
                    <h5 class="fw-semibold mb-3">Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a class="text-secondary text-decoration-none d-block mb-1" href="{{ route('home') }}">Home</a></li>
                        <li><a class="text-secondary text-decoration-none d-block mb-1" href="{{ route('about') }}">About Us</a></li>
                        <li><a class="text-secondary text-decoration-none d-block mb-1" href="{{ route('packages') }}">Packages</a></li>
                        <li><a class="text-secondary text-decoration-none d-block mb-1" href="{{ route('faq') }}">FAQ</a></li>
                        <li><a class="text-secondary text-decoration-none d-block" href="{{ route('contact') }}">Contact</a></li>
                    </ul>
                </div>

                <div class="col-md-3 mb-4">
                    <h5 class="fw-semibold mb-3">Services</h5>
                    <ul class="list-unstyled text-secondary">
                        <li class="mb-1">MLM Network</li>
                        <li class="mb-1">E-Commerce Mall</li>
                        <li class="mb-1">Reward System</li>
                        <li class="mb-1">VTU Services</li>
                        <li>Rank Achievements</li>
                    </ul>
                </div>

                <div class="col-md-3 mb-4">
                    <h5 class="fw-semibold mb-3">Contact Info</h5>
                    <ul class="list-unstyled text-secondary">
                        <li class="mb-2 d-flex align-items-center">
                            <svg class="me-2" width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                            </svg>
                            info@happylife.com
                        </li>
                        <li class="d-flex align-items-center">
                            <svg class="me-2" width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                            </svg>
                            +234 800 000 0000
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-top border-secondary mt-4 pt-3 text-center text-secondary small">
                &copy; {{ date('Y') }} Happylife Multipurpose Int'l. All rights reserved.
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>
