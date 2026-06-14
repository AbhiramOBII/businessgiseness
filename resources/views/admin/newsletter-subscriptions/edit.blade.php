@extends('admin.layout')

@section('title', 'Edit Newsletter Subscription')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Edit Newsletter Subscription</h1>
            <p class="text-gray-600">Update subscription information</p>
        </div>
        <div class="flex gap-3 mt-4 md:mt-0">
            <a href="{{ route('admin.newsletter-subscriptions.show', $newsletterSubscription) }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Details
            </a>
        </div>
    </div>

    <div class="max-w-2xl">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <form method="POST" action="{{ route('admin.newsletter-subscriptions.update', $newsletterSubscription) }}">
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email Address <span class="text-red-500">*</span>
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email', $newsletterSubscription->email) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror"
                               required>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Name
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $newsletterSubscription->name) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                               placeholder="Optional subscriber name">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="is_active" class="block text-sm font-medium text-gray-700 mb-2">
                            Subscription Status
                        </label>
                        <div class="flex items-center space-x-4">
                            <label class="flex items-center">
                                <input type="radio" 
                                       name="is_active" 
                                       value="1" 
                                       {{ old('is_active', $newsletterSubscription->is_active) ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                <span class="ml-2 text-sm text-gray-700">Active</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" 
                                       name="is_active" 
                                       value="0" 
                                       {{ !old('is_active', $newsletterSubscription->is_active) ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                <span class="ml-2 text-sm text-gray-700">Inactive</span>
                            </label>
                        </div>
                        @error('is_active')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Current Information Display -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-gray-700 mb-3">Current Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-600">Subscription Source:</span>
                                <span class="ml-2 font-medium">{{ ucfirst($newsletterSubscription->subscription_source) }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">Subscribed:</span>
                                <span class="ml-2 font-medium">{{ $newsletterSubscription->formatted_subscribed_at }}</span>
                            </div>
                            @if($newsletterSubscription->unsubscribed_at)
                                <div>
                                    <span class="text-gray-600">Unsubscribed:</span>
                                    <span class="ml-2 font-medium">{{ $newsletterSubscription->formatted_unsubscribed_at }}</span>
                                </div>
                            @endif
                            @if($newsletterSubscription->ip_address)
                                <div>
                                    <span class="text-gray-600">IP Address:</span>
                                    <span class="ml-2 font-mono text-xs">{{ $newsletterSubscription->ip_address }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex flex-col sm:flex-row gap-3 pt-4">
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center">
                            <i class="fas fa-save mr-2"></i>
                            Update Subscription
                        </button>
                        
                        <a href="{{ route('admin.newsletter-subscriptions.show', $newsletterSubscription) }}" 
                           class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center">
                            <i class="fas fa-times mr-2"></i>
                            Cancel
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Danger Zone -->
        <div class="bg-white rounded-lg shadow-sm border border-red-200 p-6 mt-6">
            <h3 class="text-lg font-semibold text-red-900 mb-4">Danger Zone</h3>
            <p class="text-sm text-gray-600 mb-4">
                These actions are permanent and cannot be undone. Please proceed with caution.
            </p>
            
            <div class="flex flex-col sm:flex-row gap-3">
                <form method="POST" action="{{ route('admin.newsletter-subscriptions.toggle-status', $newsletterSubscription) }}" class="inline">
                    @csrf
                    <button type="submit" 
                            class="bg-{{ $newsletterSubscription->is_active ? 'orange' : 'green' }}-600 hover:bg-{{ $newsletterSubscription->is_active ? 'orange' : 'green' }}-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center"
                            onclick="return confirm('Are you sure you want to {{ $newsletterSubscription->is_active ? 'deactivate' : 'activate' }} this subscription?')">
                        <i class="fas fa-{{ $newsletterSubscription->is_active ? 'ban' : 'check' }} mr-2"></i>
                        {{ $newsletterSubscription->is_active ? 'Deactivate' : 'Activate' }} Subscription
                    </button>
                </form>
                
                <form method="POST" action="{{ route('admin.newsletter-subscriptions.destroy', $newsletterSubscription) }}" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center"
                            onclick="return confirm('Are you sure you want to delete this subscription? This action cannot be undone.')">
                        <i class="fas fa-trash mr-2"></i>
                        Delete Subscription
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
