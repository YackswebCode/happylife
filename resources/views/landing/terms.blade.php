@extends('layouts.app')

@section('title', 'Terms & Conditions - ' . ($siteName ?? 'Happylife Multipurpose Int\'l'))

@section('content')
<div class="bg-red-happylife text-white py-5">
    <div class="container text-center">
        <h1 class="fw-bold display-5 mb-2">Terms & Conditions</h1>
        <p class="lead mb-0 opacity-75">Last updated: {{ date('F d, Y') }}</p>
    </div>
</div>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 p-lg-5">

                    <p class="text-muted">Welcome to Happylife Multipurpose Int’l. By accessing or using our website and services, you agree to be bound by these Terms and Conditions. If you do not agree with any part of these terms, you should discontinue use of our platform.</p>

                    <div class="mt-4">
                        <h5 class="fw-bold text-teal-blue">1. Account Registration</h5>
                        <p>You must provide accurate and complete information when creating an account. You are responsible for maintaining the confidentiality of your login credentials and for all activities that occur under your account. We reserve the right to suspend or terminate accounts that violate these terms.</p>
                    </div>

                    <div class="mt-4">
                        <h5 class="fw-bold text-teal-blue">2. Membership Packages</h5>
                        <p>All membership packages are subject to the fees displayed at the time of registration. Payments are <strong>non‑refundable</strong> except where required by law. By purchasing a package, you acknowledge that you have read and understood the compensation plan.</p>
                    </div>

                    <div class="mt-4">
                        <h5 class="fw-bold text-teal-blue">3. Commissions and Earnings</h5>
                        <p>Commissions are calculated based on the binary MLM compensation plan. While we strive to provide accurate and timely payments, actual earnings may vary. We reserve the right to adjust commission structures with reasonable prior notice. Earnings are not guaranteed and depend on individual effort and market factors.</p>
                    </div>

                    <div class="mt-4">
                        <h5 class="fw-bold text-teal-blue">4. Prohibited Activities</h5>
                        <p>You may not use the platform for any illegal or fraudulent activities, including but not limited to:</p>
                        <ul>
                            <li>Spamming or sending unsolicited messages.</li>
                            <li>Misrepresenting your identity or affiliation.</li>
                            <li>Manipulating the compensation plan or engaging in “phantom” accounts.</li>
                            <li>Posting harmful, abusive, or obscene content.</li>
                        </ul>
                        <p>Violation may lead to immediate account suspension and forfeiture of earnings.</p>
                    </div>

                    <div class="mt-4">
                        <h5 class="fw-bold text-teal-blue">5. Intellectual Property</h5>
                        <p>All content, logos, trademarks, and materials on this Site are the exclusive property of Happylife Multipurpose Int’l. Unauthorised use, reproduction, or distribution is strictly prohibited without our prior written consent.</p>
                    </div>

                    <div class="mt-4">
                        <h5 class="fw-bold text-teal-blue">6. Limitation of Liability</h5>
                        <p>Happylife Multipurpose Int’l shall not be liable for any indirect, incidental, special, or consequential damages arising from your use of the platform, including but not limited to loss of profits, data, or business opportunities. Our total liability is limited to the amount of fees you have paid to us in the twelve months preceding the claim.</p>
                    </div>

                    <div class="mt-4">
                        <h5 class="fw-bold text-teal-blue">7. Termination</h5>
                        <p>We may suspend or terminate your account at any time, with or without cause, and without prior notice. Upon termination, you will lose access to your account and any associated benefits. Provisions relating to intellectual property, disclaimers, and indemnification survive termination.</p>
                    </div>

                    <div class="mt-4">
                        <h5 class="fw-bold text-teal-blue">8. Changes to Terms</h5>
                        <p>We reserve the right to modify these Terms & Conditions at any time. We will notify users of material changes via the email address on file or through a notice on our Site. Continued use of the platform after modifications implies your acceptance of the revised terms.</p>
                    </div>

                    <div class="mt-4">
                        <h5 class="fw-bold text-teal-blue">9. Governing Law</h5>
                        <p>These terms shall be governed by and construed in accordance with the laws of the Federal Republic of Nigeria. Any disputes arising under these terms shall be subject to the exclusive jurisdiction of the courts in Nigeria.</p>
                    </div>

                    <div class="mt-4">
                        <h5 class="fw-bold text-teal-blue">10. Contact</h5>
                        <p>For questions or concerns about these Terms, please contact us at <a href="mailto:{{ $support->email ?? 'info@happylife.com' }}" class="text-red-happylife">{{ $support->email ?? 'info@happylife.com' }}</a> or call {{ $support->phone ?? '+234 800 000 0000' }}.</p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection