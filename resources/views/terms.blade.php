@extends('layouts.app')

@section('title', 'Terms & Conditions — Dehla Pakad')

@section('content')
<!-- Hero Section -->
<section class="py-5 bg-light">
    <div class="container py-4">
        <div class="text-center mb-4">
            <h1 class="display-4 fw-bold mb-3">Terms & Conditions</h1>
            <p class="lead text-muted">Last updated: {{ date('F d, Y') }}</p>
        </div>
    </div>
</section>

<!-- Terms Content -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4 p-md-5">
                        <div class="mb-5">
                            <p class="lead">Welcome to Dehla Pakad! These terms and conditions outline the rules and regulations for the use of our website and services.</p>
                            <p>By accessing this website, we assume you accept these terms and conditions. Do not continue to use Dehla Pakad if you do not agree to all of the terms and conditions stated on this page.</p>
                        </div>
                        
                        <div class="mb-5">
                            <h3 class="h4 fw-bold mb-3">1. Definitions</h3>
                            <p><strong>"Website"</strong> refers to Dehla Pakad, accessible from https://dehla-pakad.com</p>
                            <p><strong>"Service"</strong> refers to the online card game Dehla Pakad provided through the Website.</p>
                            <p><strong>"User"</strong> refers to anyone who accesses or uses the Service.</p>
                            <p><strong>"Content"</strong> refers to all text, images, code, and other materials available on the Website.</p>
                        </div>
                        
                        <div class="mb-5">
                            <h3 class="h4 fw-bold mb-3">2. Use License</h3>
                            <p>Permission is granted to temporarily access and use the Service for personal, non-commercial use only. This is the grant of a license, not a transfer of title, and under this license, you may not:</p>
                            <ul>
                                <li>Modify or copy the materials</li>
                                <li>Use the materials for any commercial purpose</li>
                                <li>Attempt to reverse engineer any software contained on the Website</li>
                                <li>Remove any copyright or other proprietary notations from the materials</li>
                                <li>Transfer the materials to another person or "mirror" the materials on any other server</li>
                            </ul>
                        </div>
                        
                        <div class="mb-5">
                            <h3 class="h4 fw-bold mb-3">3. User Accounts</h3>
                            <p>To access certain features of the Service, you may be required to create an account. You are responsible for maintaining the confidentiality of your account information, including your username and password.</p>
                            <p>You agree to accept responsibility for all activities that occur under your account. You may not use another user's account without their permission.</p>
                        </div>
                        
                        <div class="mb-5">
                            <h3 class="h4 fw-bold mb-3">4. User Conduct</h3>
                            <p>You agree not to use the Service to:</p>
                            <ul>
                                <li>Violate any laws or regulations</li>
                                <li>Infringe on the intellectual property rights of others</li>
                                <li>Harass, abuse, or harm other users</li>
                                <li>Distribute spam or other unsolicited communications</li>
                                <li>Interfere with or disrupt the Service or servers</li>
                                <li>Collect or store personal data about other users</li>
                            </ul>
                        </div>
                        
                        <div class="mb-5">
                            <h3 class="h4 fw-bold mb-3">5. Privacy</h3>
                            <p>Your use of the Service is also governed by our Privacy Policy. Please review our Privacy Policy, which explains how we collect, use, and protect your personal information.</p>
                        </div>
                        
                        <div class="mb-5">
                            <h3 class="h4 fw-bold mb-3">6. Disclaimer</h3>
                            <p>The Service is provided on an "as is" and "as available" basis. We make no warranties, expressed or implied, and hereby disclaim and negate all other warranties including, without limitation, implied warranties or conditions of merchantability, fitness for a particular purpose, or non-infringement of intellectual property or other violation of rights.</p>
                        </div>
                        
                        <div class="mb-5">
                            <h3 class="h4 fw-bold mb-3">7. Limitations</h3>
                            <p>In no event shall Dehla Pakad or its suppliers be liable for any damages (including, without limitation, damages for loss of data or profit, or due to business interruption) arising out of the use or inability to use the materials on the Website, even if Dehla Pakad or a Dehla Pakad authorized representative has been notified orally or in writing of the possibility of such damage.</p>
                        </div>
                        
                        <div class="mb-5">
                            <h3 class="h4 fw-bold mb-3">8. Changes to Terms</h3>
                            <p>We reserve the right to modify these terms at any time. We will provide notice of any changes by updating the "Last updated" date at the top of this page. Your continued use of the Service after any changes constitutes your acceptance of the new terms.</p>
                        </div>
                        
                        <div class="mb-5">
                            <h3 class="h4 fw-bold mb-3">9. Governing Law</h3>
                            <p>These terms and conditions are governed by and construed in accordance with the laws of India, and you irrevocably submit to the exclusive jurisdiction of the courts in that location.</p>
                        </div>
                        
                        <div class="mt-5 pt-4 border-top">
                            <h3 class="h5 fw-bold mb-3">Contact Us</h3>
                            <p>If you have any questions about these Terms & Conditions, please contact us at <a href="mailto:support@dehla-pakad.com">support@dehla-pakad.com</a>.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Custom Styles -->
<style>
    .card {
        border-radius: 0.5rem;
    }
    
    h3 {
        color: var(--primary-color);
    }
    
    ul {
        padding-left: 1.5rem;
    }
    
    li {
        margin-bottom: 0.5rem;
    }
    
    a {
        color: var(--primary-color);
        text-decoration: none;
    }
    
    a:hover {
        text-decoration: underline;
    }
</style>
@endsection
