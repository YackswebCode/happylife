@extends('layouts.app')

@section('title', 'Contact Us - Happylife Multipurpose Int\'l')

@section('content')
<!-- Hero Section -->
<section class="position-relative overflow-hidden" style="background: linear-gradient(135deg, #1FA3C4 0%, #3DB7D6 100%);">
    <!-- SVG Background Pattern -->
    <div class="position-absolute top-0 start-0 w-100 h-100 opacity-10">
        <svg class="w-100 h-100" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
            <path fill="#1FA3C4" fill-opacity="1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
        </svg>
    </div>
    
    <div class="container position-relative py-5 py-lg-6">
        <div class="row justify-content-center text-center">
            <div class="col-lg-10">
                   <h1 class="display-4 fw-bold text-white mb-4">Get In <span class="text-warning">Touch</span></h1>
                 <p class="lead text-white opacity-75 mb-0">
                    Our support team is ready to help you with any questions about our platform
                </p>
            </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="py-6 bg-white">
    <div class="container">
        <div class="row g-5">
            <!-- Contact Information -->
            <div class="col-lg-5">
                <div class="mb-5">
                    <span class="badge bg-danger bg-opacity-10 text-danger px-4 py-2 rounded-pill fw-semibold mb-3">
                        Contact Information
                    </span>
                    <h2 class="display-5 fw-bold mb-4">
                        Let's <span class="text-danger">Connect</span>
                    </h2>
                    <p class="text-muted mb-5">
                        Have questions about our platform? Our dedicated support team is here to help you 
                        every step of the way. Reach out to us through any of the channels below.
                    </p>
                </div>

                <!-- Contact Cards -->
                <div class="row g-4">
                    <!-- Email Card -->
                    <div class="col-md-6">
                        <div class="contact-card p-4 rounded-4 border-0 shadow-sm h-100">
                            <div class="contact-icon mb-4">
                                <div class="rounded-circle bg-danger bg-opacity-10 text-danger p-3 d-inline-flex align-items-center justify-content-center">
                                    <i class="bi bi-envelope-fill fs-2"></i>
                                </div>
                            </div>
                            <h4 class="fw-bold mb-3">Email Support</h4>
                            <p class="text-muted small mb-3">For general inquiries and support</p>
                            <a href="mailto:support@happylife.com" class="text-danger fw-semibold text-decoration-none">
                                support@happylife.com
                            </a>
                            <div class="mt-3">
                                <small class="text-muted">Response time: Within 24 hours</small>
                            </div>
                        </div>
                    </div>

                    <!-- Phone Card -->
                    <div class="col-md-6">
                        <div class="contact-card p-4 rounded-4 border-0 shadow-sm h-100">
                            <div class="contact-icon mb-4">
                                <div class="rounded-circle bg-primary bg-opacity-10 text-primary p-3 d-inline-flex align-items-center justify-content-center">
                                    <i class="bi bi-telephone-fill fs-2"></i>
                                </div>
                            </div>
                            <h4 class="fw-bold mb-3">Phone Support</h4>
                            <p class="text-muted small mb-3">For urgent matters and live assistance</p>
                            <a href="tel:+2348000000000" class="text-primary fw-semibold text-decoration-none">
                                +234 800 000 0000
                            </a>
                            <div class="mt-3">
                                <small class="text-muted">Available during business hours</small>
                            </div>
                        </div>
                    </div>

                    <!-- Address Card -->
                    <div class="col-12">
                        <div class="contact-card p-4 rounded-4 border-0 shadow-sm">
                            <div class="contact-icon mb-4">
                                <div class="rounded-circle bg-success bg-opacity-10 text-success p-3 d-inline-flex align-items-center justify-content-center">
                                    <i class="bi bi-geo-alt-fill fs-2"></i>
                                </div>
                            </div>
                            <h4 class="fw-bold mb-3">Office Location</h4>
                            <p class="text-muted mb-2">123 Business Avenue, Victoria Island</p>
                            <p class="text-muted mb-0">Lagos, Nigeria</p>
                            <div class="mt-3">
                                <small class="text-muted">Visits by appointment only</small>
                            </div>
                        </div>
                    </div>

                    <!-- Support Hours -->
                    <div class="col-12">
                        <div class="support-hours p-4 rounded-4 border border-primary border-2">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary p-3 me-3">
                                        <i class="bi bi-clock-fill fs-4"></i>
                                    </div>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-3">Support Hours</h5>
                                    <div class="row g-3">
                                        <div class="col-6">
                                            <div class="d-flex justify-content-between">
                                                <span class="text-muted">Mon - Fri:</span>
                                                <span class="fw-semibold">8:00 AM - 6:00 PM</span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="d-flex justify-content-between">
                                                <span class="text-muted">Saturday:</span>
                                                <span class="fw-semibold">9:00 AM - 4:00 PM</span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="d-flex justify-content-between">
                                                <span class="text-muted">Sunday:</span>
                                                <span class="fw-semibold">10:00 AM - 2:00 PM</span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="d-flex justify-content-between">
                                                <span class="text-muted">Emergency:</span>
                                                <span class="fw-semibold text-danger">24/7</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Social Media -->
                    <div class="col-12">
                        <h5 class="fw-bold mb-4">Connect With Us</h5>
                        <div class="d-flex gap-3">
                            <a href="#" class="social-icon rounded-circle bg-light text-dark d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <i class="bi bi-facebook fs-5"></i>
                            </a>
                            <a href="#" class="social-icon rounded-circle bg-light text-dark d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <i class="bi bi-twitter fs-5"></i>
                            </a>
                            <a href="#" class="social-icon rounded-circle bg-light text-dark d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <i class="bi bi-instagram fs-5"></i>
                            </a>
                            <a href="#" class="social-icon rounded-circle bg-light text-dark d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <i class="bi bi-linkedin fs-5"></i>
                            </a>
                            <a href="#" class="social-icon rounded-circle bg-light text-dark d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <i class="bi bi-youtube fs-5"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="col-lg-7">
                <div class="contact-form-wrapper p-4 p-lg-5 rounded-4 shadow-lg">
                    <div class="text-center mb-5">
                        <h2 class="display-5 fw-bold mb-3">Send us a <span class="text-danger">Message</span></h2>
                        <p class="text-muted">Fill out the form below and we'll get back to you as soon as possible</p>
                    </div>

                    <!-- Contact Form SVG Illustration -->
                    <div class="text-center mb-4">
                        <svg width="200" height="120" viewBox="0 0 200 120" fill="none" xmlns="http://www.w3.org/2000/svg" class="mb-4">
                            <!-- Envelope -->
                            <rect x="30" y="40" width="140" height="60" rx="5" fill="url(#envelopeGradient)" stroke="#E63323" stroke-width="2"/>
                            <path d="M30 40L100 80L170 40" stroke="#1FA3C4" stroke-width="2" stroke-linecap="round"/>
                            
                            <!-- Mail Lines -->
                            <line x1="40" y1="60" x2="160" y2="60" stroke="#3DB7D6" stroke-width="2" stroke-linecap="round" stroke-dasharray="4 4"/>
                            <line x1="40" y1="75" x2="140" y2="75" stroke="#3DB7D6" stroke-width="2" stroke-linecap="round" stroke-dasharray="4 4"/>
                            <line x1="40" y1="90" x2="120" y2="90" stroke="#3DB7D6" stroke-width="2" stroke-linecap="round" stroke-dasharray="4 4"/>
                            
                            <!-- Floating Icons -->
                            <circle cx="50" cy="25" r="8" fill="#E63323" fill-opacity="0.2">
                                <animate attributeName="cy" values="25;20;25" dur="3s" repeatCount="indefinite"/>
                            </circle>
                            <circle cx="150" cy="25" r="6" fill="#1FA3C4" fill-opacity="0.2">
                                <animate attributeName="cx" values="150;155;150" dur="4s" repeatCount="indefinite"/>
                            </circle>
                            
                            <!-- Gradient Definition -->
                            <defs>
                                <linearGradient id="envelopeGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" stop-color="#f8f9fa"/>
                                    <stop offset="100%" stop-color="#e9ecef"/>
                                </linearGradient>
                            </defs>
                        </svg>
                    </div>

                    <form action="{{ route('contact') }}" method="POST" class="needs-validation" novalidate>
                        @csrf
                        
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Full Name" required>
                                    <label for="name">Full Name <span class="text-danger">*</span></label>
                                    <div class="invalid-feedback">
                                        Please enter your full name.
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Email Address" required>
                                    <label for="email">Email Address <span class="text-danger">*</span></label>
                                    <div class="invalid-feedback">
                                        Please enter a valid email address.
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="tel" class="form-control" id="phone" name="phone" placeholder="Phone Number">
                                    <label for="phone">Phone Number</label>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select class="form-select" id="subject" name="subject" required>
                                        <option value="" selected disabled>Select a subject</option>
                                        <option value="registration">Registration Issues</option>
                                        <option value="withdrawal">Withdrawal Issues</option>
                                        <option value="technical">Technical Support</option>
                                        <option value="package">Package Inquiry</option>
                                        <option value="partnership">Business Partnership</option>
                                        <option value="other">Other Inquiry</option>
                                    </select>
                                    <label for="subject">Subject <span class="text-danger">*</span></label>
                                    <div class="invalid-feedback">
                                        Please select a subject.
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea class="form-control" id="message" name="message" placeholder="Your Message" style="height: 150px" required></textarea>
                                    <label for="message">Your Message <span class="text-danger">*</span></label>
                                    <div class="invalid-feedback">
                                        Please enter your message.
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="privacyPolicy" required>
                                    <label class="form-check-label text-muted small" for="privacyPolicy">
                                        I agree to the <a href="#" class="text-danger text-decoration-none">Privacy Policy</a> and consent to 
                                        Happylife contacting me regarding my inquiry.
                                    </label>
                                    <div class="invalid-feedback">
                                        You must agree before submitting.
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <button type="submit" class="btn btn-danger btn-lg w-100 py-3 fw-bold rounded-3">
                                    <i class="bi bi-send-fill me-2"></i> Send Message
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Link Section -->
<section class="py-6 bg-light">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <!-- FAQ Illustration -->
                <div class="text-center">
                    <svg width="300" height="200" viewBox="0 0 300 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <!-- Speech Bubble -->
                        <path d="M150 50C200 50 240 90 240 140C240 190 200 230 150 230C100 230 60 190 60 140C60 90 100 50 150 50Z" fill="url(#speechGradient)" stroke="#1FA3C4" stroke-width="2"/>
                        
                        <!-- Question Mark -->
                        <path d="M150 90C155.522 90 160 94.4772 160 100C160 105.523 155.522 110 150 110C144.477 110 140 105.523 140 100C140 94.4772 144.477 90 150 90Z" fill="#E63323"/>
                        <path d="M150 120V160" stroke="#E63323" stroke-width="4" stroke-linecap="round"/>
                        
                        <!-- Small Dots -->
                        <circle cx="80" cy="100" r="3" fill="#3DB7D6" fill-opacity="0.6">
                            <animate attributeName="cy" values="100;90;100" dur="2s" repeatCount="indefinite"/>
                        </circle>
                        <circle cx="220" cy="100" r="3" fill="#E63323" fill-opacity="0.6">
                            <animate attributeName="cx" values="220;230;220" dur="2.5s" repeatCount="indefinite"/>
                        </circle>
                        
                        <!-- Gradient Definition -->
                        <defs>
                            <linearGradient id="speechGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="#f8f9fa"/>
                                <stop offset="100%" stop-color="#e9ecef"/>
                            </linearGradient>
                        </defs>
                    </svg>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="text-center text-lg-start">
                    <span class="badge bg-danger bg-opacity-10 text-danger px-4 py-2 rounded-pill fw-semibold mb-3">
                        Quick Answers
                    </span>
                    <h2 class="display-5 fw-bold mb-4">Frequently Asked <span class="text-danger">Questions</span></h2>
                    <p class="lead text-muted mb-5">
                        Find quick answers to common questions in our comprehensive FAQ section. 
                        Save time by checking our knowledge base first.
                    </p>
                    <a href="{{ route('faq') }}" class="btn btn-outline-danger btn-lg px-5 py-3 fw-bold rounded-3">
                        <i class="bi bi-question-circle me-2"></i> Visit FAQ
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->
<section class="py-6 bg-white">
    <div class="container">
        <div class="text-center mb-6">
            <h2 class="display-5 fw-bold mb-3">Our <span class="text-danger">Location</span></h2>
            <p class="text-muted mx-auto" style="max-width: 700px;">
                Find us at our corporate headquarters in the heart of Lagos
            </p>
        </div>
        
        <div class="map-wrapper rounded-4 overflow-hidden shadow-lg">
            <!-- Map Placeholder with SVG -->
            <div class="position-relative" style="height: 400px; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                <!-- Map SVG -->
                <svg width="100%" height="100%" viewBox="0 0 800 400" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <!-- Map Background -->
                    <rect width="800" height="400" fill="#f8f9fa"/>
                    
                    <!-- Rivers -->
                    <path d="M100 200L200 180L300 190L400 210L500 200L600 190L700 180" stroke="#3DB7D6" stroke-width="4" stroke-linecap="round" stroke-opacity="0.3"/>
                    <path d="M150 250L250 230L350 240L450 260L550 250L650 240" stroke="#1FA3C4" stroke-width="3" stroke-linecap="round" stroke-opacity="0.4"/>
                    
                    <!-- Roads -->
                    <path d="M50 150L200 130L400 150L600 130L750 150" stroke="#6c757d" stroke-width="2" stroke-linecap="round" stroke-dasharray="4 4" stroke-opacity="0.3"/>
                    <path d="M100 300L300 280L500 300L700 280" stroke="#6c757d" stroke-width="2" stroke-linecap="round" stroke-dasharray="4 4" stroke-opacity="0.3"/>
                    
                    <!-- Building Blocks -->
                    <rect x="200" y="100" width="80" height="60" rx="5" fill="#dee2e6"/>
                    <rect x="300" y="80" width="100" height="80" rx="5" fill="#ced4da"/>
                    <rect x="450" y="120" width="120" height="60" rx="5" fill="#adb5bd"/>
                    <rect x="100" y="220" width="90" height="70" rx="5" fill="#6c757d"/>
                    <rect x="250" y="200" width="70" height="90" rx="5" fill="#495057"/>
                    <rect x="400" y="220" width="80" height="70" rx="5" fill="#343a40"/>
                    
                    <!-- Location Marker -->
                    <circle cx="350" cy="180" r="15" fill="#E63323" stroke="#fff" stroke-width="3">
                        <animate attributeName="r" values="15;18;15" dur="2s" repeatCount="indefinite"/>
                    </circle>
                    <circle cx="350" cy="180" r="8" fill="#fff"/>
                    
                    <!-- Floating Elements -->
                    <circle cx="500" cy="100" r="4" fill="#1FA3C4" fill-opacity="0.5">
                        <animate attributeName="cy" values="100;90;100" dur="3s" repeatCount="indefinite"/>
                    </circle>
                    <circle cx="150" cy="150" r="4" fill="#E63323" fill-opacity="0.5">
                        <animate attributeName="cx" values="150;140;150" dur="4s" repeatCount="indefinite"/>
                    </circle>
                </svg>
                
                <!-- Location Info -->
                <div class="position-absolute bottom-0 start-0 end-0 m-4">
                    <div class="bg-white rounded-4 shadow-lg p-4 mx-auto" style="max-width: 400px;">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle bg-danger text-white p-2">
                                    <i class="bi bi-geo-alt-fill"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="fw-bold mb-1">Happylife Headquarters</h6>
                                <p class="text-muted small mb-0">123 Business Avenue, Victoria Island, Lagos</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    /* Custom Styles */
    .py-6 {
        padding-top: 4rem !important;
        padding-bottom: 4rem !important;
    }
    
    .py-lg-6 {
        padding-top: 5rem !important;
        padding-bottom: 5rem !important;
    }
    
    .contact-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .contact-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    
    .contact-form-wrapper {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        border: 1px solid #e9ecef;
    }
    
    .support-hours {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }
    
    .social-icon {
        transition: all 0.3s ease;
        border: 1px solid #dee2e6;
    }
    
    .social-icon:hover {
        background: linear-gradient(135deg, #E63323 0%, #d6281a 100%) !important;
        color: #fff !important;
        transform: translateY(-3px);
        border-color: #E63323;
    }
    
    .form-control, .form-select {
        border: 1px solid #dee2e6;
        transition: all 0.3s ease;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #E63323;
        box-shadow: 0 0 0 0.25rem rgba(230, 51, 35, 0.25);
    }
    
    .map-wrapper {
        border: 2px solid #e9ecef;
    }
    
    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .display-4 {
            font-size: 2.5rem;
        }
        
        .display-5 {
            font-size: 2rem;
        }
        
        .py-6 {
            padding-top: 3rem !important;
            padding-bottom: 3rem !important;
        }
        
        .contact-form-wrapper {
            padding: 2rem !important;
        }
    }
    
    @media (max-width: 576px) {
        .display-4 {
            font-size: 2rem;
        }
        
        .display-5 {
            font-size: 1.75rem;
        }
        
        .btn-lg {
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
        }
        
        .contact-card {
            margin-bottom: 1.5rem;
        }
    }
</style>

<script>
    // Form validation
    document.addEventListener('DOMContentLoaded', function() {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        const forms = document.querySelectorAll('.needs-validation');
        
        // Loop over them and prevent submission
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                
                form.classList.add('was-validated');
            }, false);
        });
    });
</script>
@endsection