@extends('layouts.app')

@section('title', 'Privacy Policy - ' . ($siteName ?? 'Happylife Multipurpose Int\'l'))

@section('content')
<div class="bg-teal-blue text-white py-5">
    <div class="container text-center">
        <h1 class="fw-bold display-5 mb-2">Privacy Policy</h1>
        <p class="lead mb-0 opacity-75">Last updated: {{ date('F d, Y') }}</p>
    </div>
</div>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 p-lg-5">

                    <p class="text-muted">Happylife Multipurpose Int’l (“we,” “our,” or “us”) is committed to protecting your privacy. This Privacy Policy explains how we collect, use, and safeguard your personal information when you visit our website or use our services.</p>

                    <div class="mt-4">
                        <h5 class="fw-bold text-red-happylife">1. Information We Collect</h5>
                        <p>We may collect personal data you provide directly, such as your name, email address, phone number, and payment details when you register, make a purchase, or contact us. We also automatically collect certain device and usage information when you interact with our Site.</p>
                    </div>

                    <div class="mt-4">
                        <h5 class="fw-bold text-red-happylife">2. How We Use Your Information</h5>
                        <p>We use the collected data to:</p>
                        <ul>
                            <li>Process your transactions and deliver our services.</li>
                            <li>Communicate with you about your account, updates, and promotions.</li>
                            <li>Improve our platform, personalise your experience, and troubleshoot issues.</li>
                            <li>Prevent fraud and ensure the security of our network.</li>
                        </ul>
                        <p>We do <strong>not</strong> sell your personal information to third parties.</p>
                    </div>

                    <div class="mt-4">
                        <h5 class="fw-bold text-red-happylife">3. Data Security</h5>
                        <p>We implement industry‑standard security measures (SSL encryption, secure servers, access controls) to protect your data. However, no method of electronic storage or transmission is 100% secure, and we cannot guarantee absolute security.</p>
                    </div>

                    <div class="mt-4">
                        <h5 class="fw-bold text-red-happylife">4. Cookies</h5>
                        <p>Our Site uses cookies and similar tracking technologies to enhance your browsing experience, remember your preferences, and analyse site traffic. You can disable cookies in your browser settings, although some features may not function properly.</p>
                    </div>

                    <div class="mt-4">
                        <h5 class="fw-bold text-red-happylife">5. Third‑Party Services</h5>
                        <p>We may employ third‑party payment gateways (Paystack, Flutterwave) and other services that have their own privacy policies. We encourage you to read their policies. We are not responsible for the privacy practices of those third parties.</p>
                    </div>

                    <div class="mt-4">
                        <h5 class="fw-bold text-red-happylife">6. Data Retention</h5>
                        <p>We retain your personal information only for as long as necessary to fulfil the purposes described in this policy, or as required by law.</p>
                    </div>

                    <div class="mt-4">
                        <h5 class="fw-bold text-red-happylife">7. Your Rights</h5>
                        <p>Depending on your jurisdiction, you may have the right to access, correct, or delete your personal data. To exercise these rights, please contact us at the details below.</p>
                    </div>

                    <div class="mt-4">
                        <h5 class="fw-bold text-red-happylife">8. Changes to This Policy</h5>
                        <p>We reserve the right to update this Privacy Policy at any time. Changes will be posted on this page with an updated revision date. Continued use of our Site after modifications indicates your acceptance of the new terms.</p>
                    </div>

                    <div class="mt-4">
                        <h5 class="fw-bold text-red-happylife">9. Contact Us</h5>
                        <p>If you have any questions about this Privacy Policy, please contact us at <a href="mailto:{{ $support->email ?? 'info@happylife.com' }}" class="text-teal-blue">{{ $support->email ?? 'info@happylife.com' }}</a> or call {{ $support->phone ?? '+234 800 000 0000' }}.</p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection