<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Member Dashboard - Happylife Multipurpose Int\'l')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    
    <style>
        :root {
            --color-red: #E63323;
            --color-teal-blue: #1FA3C4;
            --color-dark-gray: #333333;
            --color-light-gray: #E6E6E6;
            --color-soft-cyan: #3DB7D6;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f8f9fa;
            overflow-x: hidden;
        }
        
        /* Wrapper to push content */
        #wrapper {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar Styles - FIXED WITH SCROLL */
        #sidebar {
            min-height: 100vh;
            height: 100vh; /* ADDED */
            background: linear-gradient(180deg, #222222 0%, #1a1a1a 100%);
            color: white;
            width: 250px;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            box-shadow: 3px 0 15px rgba(0,0,0,0.15);
            display: flex;
            flex-direction: column;
            overflow: hidden; /* Hide scrollbar from main container */
        }
        
        #sidebar .sidebar-header {
            padding: 25px 20px;
            background: rgba(230, 51, 35, 0.1);
            border-bottom: 1px solid rgba(255,255,255,0.1);
            flex-shrink: 0; /* Prevent header from shrinking */
        }
        
        #sidebar .sidebar-header h3 {
            color: white;
            font-weight: 700;
            margin: 0;
            font-size: 1.5rem;
        }
        
        /* Scrollable navigation container */
        .sidebar-nav-container {
            flex: 1;
            overflow-y: auto; /* Enable vertical scrolling */
            overflow-x: hidden; /* Hide horizontal scroll */
            display: flex;
            flex-direction: column;
            min-height: 0;      /* ADDED */
            max-height: 100%;   /* ADDED */
            /* Drag cursor */
            cursor: grab;       /* ADDED: indicates draggable area */
        }
        
        /* show grabbing cursor while dragging */
        .sidebar-nav-container.dragging {
            cursor: grabbing;
            user-select: none;
        }
        
        /* User Info - Fixed at top of scrollable area */
        .user-info {
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            background: rgba(0,0,0,0.2);
            flex-shrink: 0; /* Prevent shrinking */
        }
        
        /* Navigation Menu - Scrollable */
        .sidebar-menu {
            padding: 15px 0;
            flex: 1;
            min-height: 0;
        }


        
        .sidebar-menu ul {
            padding: 0;
            margin: 0;
            list-style: none;
        }
        
        .sidebar-menu ul li a {
            padding: 14px 20px;
            color: #ccc;
            display: block;
            text-decoration: none;
            transition: all 0.3s;
            border-left: 4px solid transparent;
            margin: 2px 0;
        }
        
        .sidebar-menu ul li a:hover {
            color: white;
            background: rgba(230, 51, 35, 0.15);
            border-left: 4px solid var(--color-red);
            padding-left: 16px;
        }
        
        .sidebar-menu ul li.active > a {
            color: white;
            background: rgba(230, 51, 35, 0.25);
            border-left: 4px solid var(--color-red);
        }
        
        .sidebar-menu ul li a i {
            margin-right: 12px;
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
        }
        
        /* Footer Logout - Fixed at bottom */
        .sidebar-footer {
            padding: 20px;
            border-top: 1px solid rgba(255,255,255,0.1);
            background: rgba(0,0,0,0.2);
            flex-shrink: 0; /* Prevent footer from shrinking */
        }
        
        /* Content Wrapper */
        #content {
            flex: 1;
            margin-left: 250px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        /* Navbar */
        .navbar-custom {
            background: white;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            padding: 1rem 2rem;
            position: sticky;
            top: 0;
            z-index: 999;
        }
        
        /* Stat Cards */
        .stat-card {
            border-radius: 12px;
            transition: transform 0.3s, box-shadow 0.3s;
            border: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        
        .stat-card .card-body {
            padding: 1.5rem;
        }
        
        .stat-card .stat-icon {
            font-size: 2.5rem;
            opacity: 0.8;
        }
        
        .stat-card .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
        }
        
        .stat-card .stat-label {
            font-size: 0.9rem;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        /* Color Classes */
        .bg-red { background-color: var(--color-red) !important; }
        .bg-teal-blue { background-color: var(--color-teal-blue) !important; }
        .bg-dark-gray { background-color: var(--color-dark-gray) !important; }
        .bg-light-gray { background-color: var(--color-light-gray) !important; }
        .bg-soft-cyan { background-color: var(--color-soft-cyan) !important; }
        
        .text-red { color: var(--color-red) !important; }
        .text-teal-blue { color: var(--color-teal-blue) !important; }
        .text-dark-gray { color: var(--color-dark-gray) !important; }
        .text-light-gray { color: var(--color-light-gray) !important; }
        .text-soft-cyan { color: var(--color-soft-cyan) !important; }
        
        /* Badge Styles */
        .badge-custom {
            padding: 0.5em 1em;
            border-radius: 20px;
            font-weight: 500;
        }
        
        /* Button Styles */
        .btn-red {
            background-color: var(--color-red);
            color: white;
            border: none;
            padding: 0.5rem 1.5rem;
            font-weight: 500;
        }
        
        .btn-red:hover {
            background-color: #d62a1a;
            color: white;
        }
        
        .btn-outline-red {
            color: var(--color-red);
            border: 2px solid var(--color-red);
            background: transparent;
        }
        
        .btn-outline-red:hover {
            background-color: var(--color-red);
            color: white;
        }
        
        /* Activity Timeline */
        .activity-timeline {
            position: relative;
            padding-left: 30px;
        }
        
        .activity-timeline::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 2px;
            background-color: var(--color-light-gray);
        }
        
        .activity-item {
            position: relative;
            margin-bottom: 20px;
        }
        
        .activity-item::before {
            content: '';
            position: absolute;
            left: -33px;
            top: 5px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: var(--color-red);
            border: 3px solid white;
            box-shadow: 0 0 0 2px var(--color-red);
        }
        
        /* Custom Scrollbar for Sidebar */
        .sidebar-nav-container::-webkit-scrollbar {
            width: 6px;
        }
        
        .sidebar-nav-container::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.05);
            border-radius: 3px;
        }
        
        .sidebar-nav-container::-webkit-scrollbar-thumb {
            background: var(--color-red);
            border-radius: 3px;
        }
        
        .sidebar-nav-container::-webkit-scrollbar-thumb:hover {
            background: #d62a1a;
        }
        
        /* For Firefox */
        .sidebar-nav-container {
            scrollbar-width: thin;
            scrollbar-color: var(--color-red) rgba(255,255,255,0.05);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            #sidebar {
                transform: translateX(-100%);
                width: 280px;
            }
            
            #sidebar.active {
                transform: translateX(0);
            }
            
            #content {
                margin-left: 0;
            }
            
            /* Adjust scrollbar for mobile */
            .sidebar-nav-container::-webkit-scrollbar {
                width: 4px;
            }
        }
        
        /* Card Header Gradient */
        .card-header-gradient {
            background: linear-gradient(135deg, var(--color-red) 0%, #f05c4e 100%) !important;
            color: white;
            border-bottom: none;
        }
        
        /* Gradient Backgrounds */
        .bg-gradient-red {
            background: linear-gradient(135deg, var(--color-red) 0%, #f05c4e 100%) !important;
        }
        
        /* User Avatar */
        .user-avatar {
            width: 40px;
            height: 40px;
            background: var(--color-red);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
        }
        
        /* Notification Badge */
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--color-red);
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Main Content Area */
        .main-content {
            flex: 1;
            padding: 2rem;
            overflow-y: auto;
        }
        
        /* Mobile Toggle Button */
        #sidebarToggle {
            background: var(--color-red);
            color: white;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Dropdown Menu */
        .dropdown-menu {
            border: none;
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
            border-radius: 10px;
        }
        
        .dropdown-item:focus, .dropdown-item:hover {
            background-color: rgba(230, 51, 35, 0.1);
            color: var(--color-red);
        }
        
        /* Scrollbar Styles for main content if needed */
        .main-content::-webkit-scrollbar {
            width: 8px;
        }
        
        .main-content::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }
        
        .main-content::-webkit-scrollbar-thumb {
            background: var(--color-teal-blue);
            border-radius: 4px;
        }
        
        .main-content::-webkit-scrollbar-thumb:hover {
            background: #1a8da8;
        }
        
        /* Scroll indicator for sidebar (optional) */
        .scroll-indicator {
            position: absolute;
            bottom: 80px; /* Above logout button */
            left: 50%;
            transform: translateX(-50%);
            color: rgba(255,255,255,0.5);
            font-size: 0.8rem;
            display: none;
        }
        
        @media (min-height: 700px) {
            .scroll-indicator {
                display: block;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div id="sidebar">
        <!-- Sidebar Header -->
        <div class="sidebar-header">
            <h3 class="text-center">
                <i class="bi bi-house-door-fill text-red"></i>
                <span class="text-red" style="font-weight: 800;">HAPPYLIFE</span>
            </h3>
            <div class="text-center mt-2">
                <small class="text-light-gray" style="font-size: 0.8rem;">MULTIPURPOSE INT'L</small>
            </div>
        </div>
        
        <!-- Scrollable Navigation Container -->
        <div class="sidebar-nav-container">
            <!-- User Info -->
            <div class="user-info">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <div class="user-avatar">
                            <i class="bi bi-person-fill"></i>
                        </div>
                    </div>
                    <div>
                        <h6 class="mb-0 text-white">{{ Auth::user()->name }}</h6>
                        <small class="text-light-gray">{{ Auth::user()->username }}</small>
                    </div>
                </div>
            </div>
            
            <!-- Navigation Menu -->
            <div class="sidebar-menu">
                <ul class="nav flex-column">
                    <li class="nav-item {{ request()->routeIs('member.dashboard') ? 'active' : '' }}">
                        <a href="{{ route('member.dashboard') }}" class="nav-link">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('member.profile*') ? 'active' : '' }}">
                        <a href="{{ route('member.profile') }}" class="nav-link">
                            <i class="bi bi-person-circle"></i> Profile
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('member.genealogy*') ? 'active' : '' }}">
                        <a href="{{ route('member.genealogy') }}" class="nav-link">
                            <i class="bi bi-diagram-3-fill"></i> Genealogy
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('member.wallet*') ? 'active' : '' }}">
                        <a href="{{ route('member.wallet') }}" class="nav-link">
                            <i class="bi bi-wallet2"></i> Wallets
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('member.shopping*') ? 'active' : '' }}">
                        <a href="{{ route('member.shopping') }}" class="nav-link">
                            <i class="bi bi-cart-fill"></i> Shopping Mall
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('member.vtu*') ? 'active' : '' }}">
                        <a href="{{ route('member.vtu') }}" class="nav-link">
                            <i class="bi bi-phone-fill"></i> VTU Services
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('member.referrals*') ? 'active' : '' }}">
                        <a href="{{ route('member.referrals') }}" class="nav-link">
                            <i class="bi bi-people-fill"></i> Referrals
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('member.commissions*') ? 'active' : '' }}">
                        <a href="{{ route('member.commissions') }}" class="nav-link">
                            <i class="bi bi-cash-stack"></i> Commissions
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('member.ranks*') ? 'active' : '' }}">
                        <a href="{{ route('member.ranks') }}" class="nav-link">
                            <i class="bi bi-trophy-fill"></i> Ranks
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('member.withdraw*') ? 'active' : '' }}">
                        <a href="{{ route('member.withdraw') }}" class="nav-link">
                            <i class="bi bi-bank"></i> Withdraw
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('member.settings*') ? 'active' : '' }}">
                        <a href="{{ route('member.settings') }}" class="nav-link">
                            <i class="bi bi-gear-fill"></i> Settings
                        </a>
                    </li>
                    <!-- Additional menu items for testing scroll -->
                    <li class="nav-item">
                        <a href="#" class="nav-link" onclick="alert('Coming soon!')">
                            <i class="bi bi-file-earmark-text"></i> Reports
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link" onclick="alert('Coming soon!')">
                            <i class="bi bi-chat-left-text"></i> Support
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link" onclick="alert('Coming soon!')">
                            <i class="bi bi-question-circle"></i> Help Center
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link" onclick="alert('Coming soon!')">
                            <i class="bi bi-megaphone"></i> Announcements
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Scroll Indicator (optional) -->
            <div class="scroll-indicator">
                <i class="bi bi-chevron-down"></i> Scroll for more
            </div>
        </div>
        
        <!-- Footer Logout -->
        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}" class="w-100">
                @csrf
                <button type="submit" class="btn btn-outline-light w-100 py-2">
                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                </button>
            </form>
        </div>
    </div>
    
    <!-- Page Content -->
    <div id="content">
        <!-- Top Navbar -->
        <nav class="navbar navbar-expand-lg navbar-custom">
            <div class="container-fluid">
                <button class="btn btn-red d-lg-none" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
                
                <div class="d-flex align-items-center ms-auto">
                    <!-- Notifications -->
                    <div class="dropdown me-3">
                        <button class="btn btn-light position-relative rounded-circle" type="button" data-bs-toggle="dropdown" style="width: 40px; height: 40px;">
                            <i class="bi bi-bell"></i>
                            <span class="notification-badge">3</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow" style="min-width: 300px;">
                            <li><h6 class="dropdown-header">Notifications</h6></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-cash-stack text-success me-2"></i> New commission earned</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-person-plus text-primary me-2"></i> Downline registered</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-wallet2 text-warning me-2"></i> Wallet credited</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-center text-red" href="#"><i class="bi bi-bell me-1"></i> View All</a></li>
                        </ul>
                    </div>
                    
                    <!-- User Dropdown -->
                    <div class="dropdown">
                        <button class="btn btn-light d-flex align-items-center rounded-pill" type="button" data-bs-toggle="dropdown">
                            <div class="user-avatar me-2">
                                <i class="bi bi-person-fill"></i>
                            </div>
                            <span class="d-none d-md-inline">{{ Str::limit(Auth::user()->name, 15) }}</span>
                            <i class="bi bi-chevron-down ms-1"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <li><a class="dropdown-item" href="{{ route('member.profile') }}"><i class="bi bi-person me-2"></i> Profile</a></li>
                            <li><a class="dropdown-item" href="{{ route('member.settings') }}"><i class="bi bi-gear me-2"></i> Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
        
        <!-- Main Content -->
        <div class="main-content">
            @yield('content')
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle Sidebar on Mobile
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('active');
            
            // Update icon
            const icon = this.querySelector('i');
            if (sidebar.classList.contains('active')) {
                icon.classList.remove('bi-list');
                icon.classList.add('bi-x');
            } else {
                icon.classList.remove('bi-x');
                icon.classList.add('bi-list');
            }
        });
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.getElementById('sidebarToggle');
            
            if (window.innerWidth < 768 && sidebar.classList.contains('active')) {
                if (!sidebar.contains(event.target) && !toggleBtn.contains(event.target)) {
                    sidebar.classList.remove('active');
                    const icon = toggleBtn.querySelector('i');
                    icon.classList.remove('bi-x');
                    icon.classList.add('bi-list');
                }
            }
        });
        
        // Format currency function
        function formatCurrency(amount) {
            return '₦' + parseFloat(amount).toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }
        
        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            // Auto-hide sidebar on mobile on page load
            if (window.innerWidth < 768) {
                document.getElementById('sidebar').classList.remove('active');
            }
            
            // Add scroll event listener to hide scroll indicator
            const navContainer = document.querySelector('.sidebar-nav-container');
            const scrollIndicator = document.querySelector('.scroll-indicator');
            
            if (navContainer && scrollIndicator) {
                navContainer.addEventListener('scroll', function() {
                    // If user has scrolled down, hide the indicator
                    if (this.scrollTop > 20) {
                        scrollIndicator.style.opacity = '0';
                        scrollIndicator.style.transition = 'opacity 0.3s';
                    } else {
                        scrollIndicator.style.opacity = '1';
                    }
                });
            }
        });
        
        // Update sidebar active state based on current URL
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            const navItems = document.querySelectorAll('#sidebar .nav-item');
            
            navItems.forEach(item => {
                const link = item.querySelector('a');
                if (link && link.getAttribute('href') === currentPath) {
                    item.classList.add('active');
                }
            });
        });
        
        // Smooth scroll for sidebar links
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarLinks = document.querySelectorAll('.sidebar-menu a');
            
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    // If it's a hash link (#), prevent default and just close sidebar on mobile
                    if (this.getAttribute('href') === '#') {
                        e.preventDefault();
                        if (window.innerWidth < 768) {
                            document.getElementById('sidebar').classList.remove('active');
                            const icon = document.getElementById('sidebarToggle').querySelector('i');
                            icon.classList.remove('bi-x');
                            icon.classList.add('bi-list');
                        }
                        return false;
                    }
                    
                    // Close sidebar on mobile after clicking a link
                    if (window.innerWidth < 768) {
                        setTimeout(() => {
                            document.getElementById('sidebar').classList.remove('active');
                            const icon = document.getElementById('sidebarToggle').querySelector('i');
                            icon.classList.remove('bi-x');
                            icon.classList.add('bi-list');
                        }, 300);
                    }
                });
            });
        });
        
        // Add keyboard shortcut for toggling sidebar (Esc to close)
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && window.innerWidth < 768) {
                const sidebar = document.getElementById('sidebar');
                if (sidebar.classList.contains('active')) {
                    sidebar.classList.remove('active');
                    const icon = document.getElementById('sidebarToggle').querySelector('i');
                    icon.classList.remove('bi-x');
                    icon.classList.add('bi-list');
                }
            }
        });

        /* ---------------------------
           Drag-scroll (hover + hold to scroll)
           - allows click+drag on desktop and touch-drag on mobile
           - preserves normal clicks when not dragging
           ----------------------------*/
        (function() {
            const nav = document.querySelector('.sidebar-nav-container');
            if (!nav) return;

            let isDown = false;
            let startY = 0;
            let startScrollTop = 0;
            let preventClick = false;

            // Mouse events
            nav.addEventListener('mousedown', function(e) {
                // Only left-button
                if (e.button !== 0) return;
                isDown = true;
                nav.classList.add('dragging');
                startY = e.pageY - nav.getBoundingClientRect().top;
                startScrollTop = nav.scrollTop;
                preventClick = false;
            });

            nav.addEventListener('mousemove', function(e) {
                if (!isDown) return;
                e.preventDefault();
                const y = e.pageY - nav.getBoundingClientRect().top;
                const walk = (y - startY); // positive when dragging down
                if (Math.abs(walk) > 5) preventClick = true; // threshold so small moves are clicks
                nav.scrollTop = startScrollTop - walk;
            });

            ['mouseup','mouseleave'].forEach(evt => {
                nav.addEventListener(evt, function() {
                    if (!isDown) return;
                    isDown = false;
                    nav.classList.remove('dragging');
                    // small timeout to restore cursor gracefully
                    setTimeout(()=> nav.style.removeProperty('cursor'), 0);
                });
            });

            // Prevent link click after drag
            nav.addEventListener('click', function(e) {
                if (preventClick) {
                    const a = e.target.closest('a');
                    if (a) {
                        e.preventDefault();
                        e.stopPropagation();
                        preventClick = false;
                    }
                }
            }, true); // capture to stop before link handlers

            // Touch events for mobile swiping
            nav.addEventListener('touchstart', function(e) {
                if (e.touches.length !== 1) return;
                startY = e.touches[0].pageY - nav.getBoundingClientRect().top;
                startScrollTop = nav.scrollTop;
            }, {passive: true});

            nav.addEventListener('touchmove', function(e) {
                if (e.touches.length !== 1) return;
                const y = e.touches[0].pageY - nav.getBoundingClientRect().top;
                const walk = (y - startY);
                nav.scrollTop = startScrollTop - walk;
            }, {passive: false});

            // Improve UX: show native scrollbar when hovered (optional)
            nav.addEventListener('mouseenter', function() {
                nav.style.scrollbarWidth = 'auto';
            });
            nav.addEventListener('mouseleave', function() {
                nav.style.scrollbarWidth = ''; // revert
            });
        })();
    </script>
    
    @stack('scripts')
</body>
</html>
