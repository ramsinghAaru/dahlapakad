@extends('layouts.app')

@section('title', 'Privacy Policy — Dehla Pakad')

@section('content')
<!-- Hero Section -->
<section class="py-5 bg-light">
    <div class="container py-4">
        <div class="text-center mb-4">
            <h1 class="display-4 fw-bold mb-3">Privacy Policy</h1>
            <p class="lead text-muted">Last updated: {{ date('F d, Y') }}</p>
        </div>
    </div>
</section>

<!-- Privacy Content -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4 p-md-5">
                        <div class="mb-5">
                            <p class="lead">At Dehla Pakad, we respect your privacy and are committed to protecting your personal information. This Privacy Policy explains how we collect, use, and safeguard your information when you use our website and services.</p>
                            <p>By using our Service, you agree to the collection and use of information in accordance with this policy.</p>
                        </div>
                        
                        <div class="mb-5">
                            <h3 class="h4 fw-bold mb-3">1. Information We Collect</h3>
                            <p>We collect several different types of information for various purposes to provide and improve our Service to you.</p>
                            
                            <h4 class="h5 mt-4 mb-3">Personal Data</h4>
                            <p>While using our Service, we may ask you to provide us with certain personally identifiable information that can be used to contact or identify you ("Personal Data"). Personally identifiable information may include, but is not limited to:</p>
                            <ul>
                                <li>Email address</li>
                                <li>First name and last name</li>
                                <li>Username</li>
                                <li>Game statistics and preferences</li>
                                <li>Cookies and Usage Data</li>
                            </ul>
                            
                            <h4 class="h5 mt-4 mb-3">Usage Data</h4>
                            <p>We may also collect information on how the Service is accessed and used ("Usage Data"). This Usage Data may include information such as your computer's Internet Protocol address (e.g., IP address), browser type, browser version, the pages of our Service that you visit, the time and date of your visit, the time spent on those pages, unique device identifiers, and other diagnostic data.</p>
                            
                            <h4 class="h5 mt-4 mb-3">Tracking & Cookies Data</h4>
                            <p>We use cookies and similar tracking technologies to track the activity on our Service and hold certain information.</p>
                            <p>Cookies are files with a small amount of data which may include an anonymous unique identifier. You can instruct your browser to refuse all cookies or to indicate when a cookie is being sent.</p>
                        </div>
                        
                        <div class="mb-5">
                            <h3 class="h4 fw-bold mb-3">2. Use of Data</h3>
                            <p>Dehla Pakad uses the collected data for various purposes:</p>
                            <ul>
                                <li>To provide and maintain our Service</li>
                                <li>To notify you about changes to our Service</li>
                                <li>To allow you to participate in interactive features of our Service when you choose to do so</li>
                                <li>To provide customer support</li>
                                <li>To gather analysis or valuable information so that we can improve our Service</li>
                                <li>To monitor the usage of our Service</li>
                                <li>To detect, prevent and address technical issues</li>
                                <li>To provide you with news, special offers, and general information about other goods, services, and events which we offer</li>
                            </ul>
                        </div>
                        
                        <div class="mb-5">
                            <h3 class="h4 fw-bold mb-3">3. Data Security</h3>
                            <p>The security of your data is important to us, but remember that no method of transmission over the Internet or method of electronic storage is 100% secure. While we strive to use commercially acceptable means to protect your Personal Data, we cannot guarantee its absolute security.</p>
                        </div>
                        
                        <div class="mb-5">
                            <h3 class="h4 fw-bold mb-3">4. Service Providers</h3>
                            <p>We may employ third-party companies and individuals to facilitate our Service ("Service Providers"), to provide the Service on our behalf, to perform Service-related services, or to assist us in analyzing how our Service is used.</p>
                            <p>These third parties have access to your Personal Data only to perform these tasks on our behalf and are obligated not to disclose or use it for any other purpose.</p>
                        </div>
                        
                        <div class="mb-5">
                            <h3 class="h4 fw-bold mb-3">5. Links to Other Sites</h3>
                            <p>Our Service may contain links to other sites that are not operated by us. If you click on a third-party link, you will be directed to that third party's site. We strongly advise you to review the Privacy Policy of every site you visit.</p>
                            <p>We have no control over and assume no responsibility for the content, privacy policies, or practices of any third-party sites or services.</p>
                        </div>
                        
                        <div class="mb-5">
                            <h3 class="h4 fw-bold mb-3">6. Children's Privacy</h3>
                            <p>Our Service does not address anyone under the age of 13 ("Children").</p>
                            <p>We do not knowingly collect personally identifiable information from anyone under the age of 13. If you are a parent or guardian and you are aware that your child has provided us with Personal Data, please contact us. If we become aware that we have collected Personal Data from children without verification of parental consent, we take steps to remove that information from our servers.</p>
                        </div>
                        
                        <div class="mb-5">
                            <h3 class="h4 fw-bold mb-3">7. Changes to This Privacy Policy</h3>
                            <p>We may update our Privacy Policy from time to time. We will notify you of any changes by posting the new Privacy Policy on this page and updating the "Last updated" date at the top of this Privacy Policy.</p>
                            <p>You are advised to review this Privacy Policy periodically for any changes. Changes to this Privacy Policy are effective when they are posted on this page.</p>
                        </div>
                        
                        <div class="mt-5 pt-4 border-top">
                            <h3 class="h5 fw-bold mb-3">Contact Us</h3>
                            <p>If you have any questions about this Privacy Policy, please contact us at <a href="mailto:privacy@dehla-pakad.com">privacy@dehla-pakad.com</a>.</p>
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
    
    h4 {
        color: #495057;
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
