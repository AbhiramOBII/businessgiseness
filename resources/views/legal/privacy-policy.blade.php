@extends('layouts.app')

@section('title', 'Privacy Policy - Business Giseness')
@section('meta_description', 'Privacy Policy for Business Giseness podcast. Learn how we collect, use, and protect your personal information.')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-brand-dark mb-4">Privacy Policy</h1>
            <p class="text-xl text-gray-600">Last updated: {{ date('F d, Y') }}</p>
        </div>

        <!-- Content -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 md:p-12">
            <div class="prose prose-lg max-w-none">
                <h2 class="text-2xl font-bold text-brand-dark mb-4">1. Information We Collect</h2>
                <p class="mb-6">At Business Giseness, we collect information to provide better services to our users. We collect information in the following ways:</p>
                
                <h3 class="text-xl font-semibold text-brand-dark mb-3">Information You Give Us</h3>
                <ul class="list-disc pl-6 mb-6">
                    <li>Contact information (name, email address, phone number)</li>
                    <li>Newsletter subscription preferences</li>
                    <li>Comments and feedback you provide</li>
                    <li>Information you provide when contacting us</li>
                </ul>

                <h3 class="text-xl font-semibold text-brand-dark mb-3">Information We Get From Your Use of Our Services</h3>
                <ul class="list-disc pl-6 mb-6">
                    <li>Device information (browser type, operating system)</li>
                    <li>Log information (IP address, date and time of visits)</li>
                    <li>Usage data (pages visited, time spent on site)</li>
                    <li>Cookies and similar technologies</li>
                </ul>

                <h2 class="text-2xl font-bold text-brand-dark mb-4">2. How We Use Information</h2>
                <p class="mb-4">We use the information we collect to:</p>
                <ul class="list-disc pl-6 mb-6">
                    <li>Provide, maintain, and improve our services</li>
                    <li>Send you newsletters and updates (with your consent)</li>
                    <li>Respond to your comments and questions</li>
                    <li>Analyze usage patterns to improve user experience</li>
                    <li>Protect against fraud and abuse</li>
                </ul>

                <h2 class="text-2xl font-bold text-brand-dark mb-4">3. Information Sharing</h2>
                <p class="mb-4">We do not sell, trade, or otherwise transfer your personal information to third parties except in the following circumstances:</p>
                <ul class="list-disc pl-6 mb-6">
                    <li>With your explicit consent</li>
                    <li>To trusted service providers who assist us in operating our website</li>
                    <li>When required by law or to protect our rights</li>
                    <li>In connection with a business transfer or merger</li>
                </ul>

                <h2 class="text-2xl font-bold text-brand-dark mb-4">4. Data Security</h2>
                <p class="mb-6">We implement appropriate security measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction. However, no method of transmission over the internet is 100% secure.</p>

                <h2 class="text-2xl font-bold text-brand-dark mb-4">5. Cookies</h2>
                <p class="mb-6">We use cookies to enhance your experience on our website. Cookies are small files stored on your device that help us remember your preferences and understand how you use our site. You can choose to disable cookies through your browser settings.</p>

                <h2 class="text-2xl font-bold text-brand-dark mb-4">6. Third-Party Services</h2>
                <p class="mb-4">Our website may contain links to third-party services such as:</p>
                <ul class="list-disc pl-6 mb-6">
                    <li>YouTube (for podcast episodes)</li>
                    <li>Spotify (for podcast streaming)</li>
                    <li>Social media platforms</li>
                    <li>Analytics services (Google Analytics)</li>
                </ul>
                <p class="mb-6">These services have their own privacy policies, and we encourage you to review them.</p>

                <h2 class="text-2xl font-bold text-brand-dark mb-4">7. Your Rights</h2>
                <p class="mb-4">You have the right to:</p>
                <ul class="list-disc pl-6 mb-6">
                    <li>Access your personal information</li>
                    <li>Correct inaccurate information</li>
                    <li>Request deletion of your information</li>
                    <li>Opt-out of marketing communications</li>
                    <li>Data portability</li>
                </ul>

                <h2 class="text-2xl font-bold text-brand-dark mb-4">8. Children's Privacy</h2>
                <p class="mb-6">Our services are not directed to children under 13. We do not knowingly collect personal information from children under 13. If we become aware that we have collected personal information from a child under 13, we will take steps to delete such information.</p>

                <h2 class="text-2xl font-bold text-brand-dark mb-4">9. Changes to This Policy</h2>
                <p class="mb-6">We may update this Privacy Policy from time to time. We will notify you of any changes by posting the new Privacy Policy on this page and updating the "Last updated" date.</p>

                <h2 class="text-2xl font-bold text-brand-dark mb-4">10. Contact Us</h2>
                <p class="mb-4">If you have any questions about this Privacy Policy, please contact us:</p>
                <div class="bg-gray-50 p-6 rounded-lg">
                    <p class="mb-2"><strong>Email:</strong> privacy@businessgiseness.com</p>
                    <p class="mb-2"><strong>Phone:</strong> +91 9964 102 103</p>
                    <p><strong>Address:</strong> Bangalore, India</p>
                </div>
            </div>
        </div>

        <!-- Back to Home -->
        <div class="text-center mt-8">
            <a href="{{ route('home') }}" class="inline-flex items-center bg-brand-gold hover:bg-yellow-600 text-brand-dark px-6 py-3 rounded-full font-semibold transition-colors duration-300">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Home
            </a>
        </div>
    </div>
</div>
@endsection
