@extends('layouts.app')

@section('title', 'Terms of Use - Business Giseness')
@section('meta_description', 'Terms of Use for Business Giseness podcast. Read our terms and conditions for using our website and services.')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-brand-dark mb-4">Terms of Use</h1>
            <p class="text-xl text-gray-600">Last updated: {{ date('F d, Y') }}</p>
        </div>

        <!-- Content -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 md:p-12">
            <div class="prose prose-lg max-w-none">
                <h2 class="text-2xl font-bold text-brand-dark mb-4">1. Acceptance of Terms</h2>
                <p class="mb-6">By accessing and using the Business Giseness website and services, you accept and agree to be bound by the terms and provision of this agreement. If you do not agree to abide by the above, please do not use this service.</p>

                <h2 class="text-2xl font-bold text-brand-dark mb-4">2. Description of Service</h2>
                <p class="mb-6">Business Giseness is a podcast platform that provides entrepreneurial content, business insights, and educational resources. Our services include podcast episodes, blog articles, guest interviews, and related content.</p>

                <h2 class="text-2xl font-bold text-brand-dark mb-4">3. User Responsibilities</h2>
                <p class="mb-4">As a user of our services, you agree to:</p>
                <ul class="list-disc pl-6 mb-6">
                    <li>Use our services for lawful purposes only</li>
                    <li>Not attempt to gain unauthorized access to our systems</li>
                    <li>Not distribute malware or harmful code</li>
                    <li>Respect intellectual property rights</li>
                    <li>Provide accurate information when required</li>
                    <li>Not engage in spam or unsolicited communications</li>
                </ul>

                <h2 class="text-2xl font-bold text-brand-dark mb-4">4. Intellectual Property Rights</h2>
                <p class="mb-4">All content on Business Giseness, including but not limited to:</p>
                <ul class="list-disc pl-6 mb-4">
                    <li>Podcast episodes and audio content</li>
                    <li>Written articles and blog posts</li>
                    <li>Images, graphics, and visual content</li>
                    <li>Website design and layout</li>
                    <li>Trademarks and logos</li>
                </ul>
                <p class="mb-6">are owned by Business Giseness or our content creators and are protected by copyright and other intellectual property laws. You may not reproduce, distribute, or create derivative works without explicit permission.</p>

                <h2 class="text-2xl font-bold text-brand-dark mb-4">5. Content Usage Rights</h2>
                <p class="mb-4">You are granted a limited, non-exclusive license to:</p>
                <ul class="list-disc pl-6 mb-6">
                    <li>Access and view content for personal, non-commercial use</li>
                    <li>Share links to our content on social media</li>
                    <li>Quote brief excerpts with proper attribution</li>
                </ul>
                <p class="mb-6">Commercial use of our content requires prior written permission.</p>

                <h2 class="text-2xl font-bold text-brand-dark mb-4">6. User-Generated Content</h2>
                <p class="mb-4">If you submit content to our platform (comments, feedback, etc.), you grant us:</p>
                <ul class="list-disc pl-6 mb-6">
                    <li>A non-exclusive, royalty-free license to use your content</li>
                    <li>The right to moderate and remove inappropriate content</li>
                    <li>The right to use your feedback to improve our services</li>
                </ul>
                <p class="mb-6">You remain responsible for the content you submit and warrant that it does not infringe on third-party rights.</p>

                <h2 class="text-2xl font-bold text-brand-dark mb-4">7. Privacy and Data Protection</h2>
                <p class="mb-6">Your privacy is important to us. Our collection and use of personal information is governed by our Privacy Policy, which is incorporated into these Terms of Use by reference.</p>

                <h2 class="text-2xl font-bold text-brand-dark mb-4">8. Disclaimers</h2>
                <p class="mb-4">Business Giseness content is provided "as is" without warranties of any kind. We disclaim:</p>
                <ul class="list-disc pl-6 mb-6">
                    <li>Accuracy or completeness of information</li>
                    <li>Fitness for a particular purpose</li>
                    <li>Uninterrupted or error-free service</li>
                    <li>Results from using our content</li>
                </ul>

                <h2 class="text-2xl font-bold text-brand-dark mb-4">9. Limitation of Liability</h2>
                <p class="mb-6">Business Giseness shall not be liable for any direct, indirect, incidental, special, or consequential damages resulting from the use or inability to use our services, even if we have been advised of the possibility of such damages.</p>

                <h2 class="text-2xl font-bold text-brand-dark mb-4">10. Third-Party Links and Services</h2>
                <p class="mb-6">Our website may contain links to third-party websites and services. We are not responsible for the content, privacy policies, or practices of these external sites. Use of third-party services is at your own risk.</p>

                <h2 class="text-2xl font-bold text-brand-dark mb-4">11. Termination</h2>
                <p class="mb-6">We reserve the right to terminate or suspend access to our services at any time, without prior notice, for conduct that we believe violates these Terms of Use or is harmful to other users or our business.</p>

                <h2 class="text-2xl font-bold text-brand-dark mb-4">12. Governing Law</h2>
                <p class="mb-6">These Terms of Use shall be governed by and construed in accordance with the laws of India. Any disputes arising under these terms shall be subject to the exclusive jurisdiction of the courts in Bangalore, India.</p>

                <h2 class="text-2xl font-bold text-brand-dark mb-4">13. Changes to Terms</h2>
                <p class="mb-6">We reserve the right to modify these Terms of Use at any time. Changes will be effective immediately upon posting. Your continued use of our services after changes are posted constitutes acceptance of the modified terms.</p>

                <h2 class="text-2xl font-bold text-brand-dark mb-4">14. Contact Information</h2>
                <p class="mb-4">If you have questions about these Terms of Use, please contact us:</p>
                <div class="bg-gray-50 p-6 rounded-lg">
                    <p class="mb-2"><strong>Email:</strong> legal@businessgiseness.com</p>
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
