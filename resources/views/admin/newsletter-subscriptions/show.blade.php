@extends('admin.layout')

@section('title', 'Newsletter Subscription Details')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Subscription Details</h1>
            <p class="text-gray-600">View newsletter subscription information</p>
        </div>
        <div class="flex gap-3 mt-4 md:mt-0">
            <a href="{{ route('admin.newsletter-subscriptions.edit', $newsletterSubscription) }}" 
               class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center">
                <i class="fas fa-edit mr-2"></i>
                Edit
            </a>
            <a href="{{ route('admin.newsletter-subscriptions.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to List
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Information -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Subscription Information</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <p class="text-lg font-medium text-gray-900">{{ $newsletterSubscription->email }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <p class="text-lg text-gray-900">{{ $newsletterSubscription->name ?? 'Not provided' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $newsletterSubscription->status_badge_class }}">
                            {{ $newsletterSubscription->status_text }}
                        </span>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Subscription Source</label>
                        <span class="inline-flex px-3 py-1 text-sm font-medium bg-gray-100 text-gray-800 rounded-full">
                            {{ ucfirst($newsletterSubscription->subscription_source) }}
                        </span>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Subscribed Date</label>
                        <p class="text-gray-900">{{ $newsletterSubscription->subscribed_at->format('F d, Y \a\t g:i A') }}</p>
                    </div>
                    
                    @if($newsletterSubscription->unsubscribed_at)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Unsubscribed Date</label>
                            <p class="text-gray-900">{{ $newsletterSubscription->unsubscribed_at->format('F d, Y \a\t g:i A') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Actions & Metadata -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                
                <div class="space-y-3">
                    <form method="POST" action="{{ route('admin.newsletter-subscriptions.toggle-status', $newsletterSubscription) }}">
                        @csrf
                        <button type="submit" 
                                class="w-full bg-{{ $newsletterSubscription->is_active ? 'red' : 'green' }}-600 hover:bg-{{ $newsletterSubscription->is_active ? 'red' : 'green' }}-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center"
                                onclick="return confirm('Are you sure you want to {{ $newsletterSubscription->is_active ? 'deactivate' : 'activate' }} this subscription?')">
                            <i class="fas fa-{{ $newsletterSubscription->is_active ? 'ban' : 'check' }} mr-2"></i>
                            {{ $newsletterSubscription->is_active ? 'Deactivate' : 'Activate' }} Subscription
                        </button>
                    </form>
                    
                    <form method="POST" action="{{ route('admin.newsletter-subscriptions.destroy', $newsletterSubscription) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center"
                                onclick="return confirm('Are you sure you want to delete this subscription? This action cannot be undone.')">
                            <i class="fas fa-trash mr-2"></i>
                            Delete Subscription
                        </button>
                    </form>
                </div>
            </div>

            <!-- Technical Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Technical Information</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Subscription ID</label>
                        <p class="text-sm text-gray-600 font-mono">{{ $newsletterSubscription->id }}</p>
                    </div>
                    
                    @if($newsletterSubscription->ip_address)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">IP Address</label>
                            <p class="text-sm text-gray-600 font-mono">{{ $newsletterSubscription->ip_address }}</p>
                        </div>
                    @endif
                    
                    @if($newsletterSubscription->user_agent)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">User Agent</label>
                            <p class="text-xs text-gray-600 break-all">{{ $newsletterSubscription->user_agent }}</p>
                        </div>
                    @endif
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Created</label>
                        <p class="text-sm text-gray-600">{{ $newsletterSubscription->created_at->format('F d, Y \a\t g:i A') }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Last Updated</label>
                        <p class="text-sm text-gray-600">{{ $newsletterSubscription->updated_at->format('F d, Y \a\t g:i A') }}</p>
                    </div>
                </div>
            </div>

            <!-- Subscription Timeline -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Timeline</h3>
                
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-plus text-green-600 text-sm"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Subscribed</p>
                            <p class="text-xs text-gray-600">{{ $newsletterSubscription->subscribed_at->format('F d, Y \a\t g:i A') }}</p>
                            <p class="text-xs text-gray-500">via {{ ucfirst($newsletterSubscription->subscription_source) }}</p>
                        </div>
                    </div>
                    
                    @if($newsletterSubscription->unsubscribed_at)
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-minus text-red-600 text-sm"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">Unsubscribed</p>
                                <p class="text-xs text-gray-600">{{ $newsletterSubscription->unsubscribed_at->format('F d, Y \a\t g:i A') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
