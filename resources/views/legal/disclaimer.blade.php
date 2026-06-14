@extends('layouts.app')

@section('title', 'Disclaimer - Business Giseness')
@section('meta_description', 'Disclaimer for Business Giseness podcast. Important legal information about our content and services.')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-brand-dark mb-4">Disclaimer</h1>
            <p class="text-xl text-gray-600">Last updated: {{ date('F d, Y') }}</p>
        </div>

        <!-- Content -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 md:p-12">
            <div class="prose prose-lg max-w-none">
                <h2 class="text-2xl font-bold text-brand-dark mb-4">General Information</h2>
                <p class="mb-6">The information on this website and podcast is provided on an "as is" basis. To the fullest extent permitted by law, Business Giseness excludes all representations, warranties, obligations, and liabilities arising out of or in connection with this website and its contents.</p>

                <h2 class="text-2xl font-bold text-brand-dark mb-4">Educational Content Only</h2>
                <p class="mb-6">The content provided by Business Giseness, including podcast episodes, blog posts, and other materials, is for educational and informational purposes only. It should not be considered as professional advice in business, finance, legal, or any other field.</p>

                <h2 class="text-2xl font-bold text-brand-dark mb-4">No Professional Advice</h2>
                <p class="mb-4">Business Giseness does not provide:</p>
                <ul class="list-disc pl-6 mb-6">
                    <li>Financial or investment advice</li>
                    <li>Legal counsel or recommendations</li>
                    <li>Business consulting services</li>
                    <li>Tax or accounting guidance</li>
                    <li>Medical or health advice</li>
                </ul>
                <p class="mb-6">Always consult with qualified professionals before making important business, financial, or legal decisions.</p>

                <h2 class="text-2xl font-bold text-brand-dark mb-4">Guest Opinions</h2>
                <p class="mb-6">Views and opinions expressed by guests on our podcast are their own and do not necessarily reflect the views of Business Giseness, its host, or affiliates. We do not endorse or guarantee the accuracy of guest statements or recommendations.</p>

                <h2 class="text-2xl font-bold text-brand-dark mb-4">Business Results</h2>
                <p class="mb-6">Any business strategies, tips, or case studies shared on our platform are based on specific circumstances and may not be applicable to your situation. Results may vary significantly based on individual circumstances, market conditions, and implementation.</p>

                <h2 class="text-2xl font-bold text-brand-dark mb-4">External Links</h2>
                <p class="mb-6">Our website and podcast may contain links to external websites and resources. We are not responsible for the content, accuracy, or opinions expressed on these external sites. Inclusion of any linked website does not imply endorsement by Business Giseness.</p>

                <h2 class="text-2xl font-bold text-brand-dark mb-4">Accuracy of Information</h2>
                <p class="mb-6">While we strive to provide accurate and up-to-date information, we make no representations or warranties about the completeness, accuracy, reliability, or suitability of the information contained on our platform.</p>

                <h2 class="text-2xl font-bold text-brand-dark mb-4">Limitation of Liability</h2>
                <p class="mb-4">Business Giseness, its host, guests, and affiliates shall not be liable for:</p>
                <ul class="list-disc pl-6 mb-6">
                    <li>Any direct, indirect, or consequential losses</li>
                    <li>Loss of profits or business opportunities</li>
                    <li>Damages arising from use of our content</li>
                    <li>Errors or omissions in our content</li>
                    <li>Technical issues or website downtime</li>
                </ul>

                <h2 class="text-2xl font-bold text-brand-dark mb-4">Affiliate Relationships</h2>
                <p class="mb-6">Business Giseness may contain affiliate links or sponsored content. We will clearly disclose such relationships where they exist. Any recommendations are based on our genuine opinion, but we may receive compensation for referrals.</p>

                <h2 class="text-2xl font-bold text-brand-dark mb-4">Copyright and Fair Use</h2>
                <p class="mb-6">All content on Business Giseness is protected by copyright. While we may reference or quote other sources under fair use provisions, we respect intellectual property rights and expect others to do the same.</p>

                <h2 class="text-2xl font-bold text-brand-dark mb-4">Changes to Content</h2>
                <p class="mb-6">We reserve the right to modify, update, or remove content at any time without notice. Information may become outdated, and we are not obligated to update previously published content.</p>

                <h2 class="text-2xl font-bold text-brand-dark mb-4">Jurisdiction</h2>
                <p class="mb-6">This disclaimer is governed by the laws of India. Any disputes arising from the use of our content shall be subject to the exclusive jurisdiction of the courts in Bangalore, India.</p>

                <h2 class="text-2xl font-bold text-brand-dark mb-4">Contact Information</h2>
                <p class="mb-4">If you have questions about this disclaimer, please contact us:</p>
                <div class="bg-gray-50 p-6 rounded-lg">
                    <p class="mb-2"><strong>Email:</strong> info@businessgiseness.com</p>
                    <p class="mb-2"><strong>Phone:</strong> +91 9964 102 103</p>
                    <p><strong>Address:</strong> Bangalore, India</p>
                </div>

                <div class="mt-8 p-6 bg-yellow-50 border-l-4 border-yellow-400 rounded-r-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-yellow-400 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                <strong>Important:</strong> By using our website and consuming our content, you acknowledge that you have read, understood, and agree to this disclaimer. If you do not agree with any part of this disclaimer, please discontinue use of our services.
                            </p>
                        </div>
                    </div>
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
