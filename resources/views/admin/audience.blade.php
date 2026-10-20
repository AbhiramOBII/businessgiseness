@extends('admin.layout')

@section('title', 'YouTube Stats')
@section('page-title', 'YouTube Stats')
@section('page-description', 'Subscribers and views for the Kannada and English channels')

@section('content')
<div class="max-w-4xl space-y-6">
    @include('admin.partials.audience-card')

    <p class="text-sm text-gray-500">
        <i class="fas fa-info-circle text-blue-500"></i>
        The channel links come from the YouTube URLs in <a href="{{ route('admin.content') }}#social" class="text-blue-600 hover:underline">Content Management → Social Links</a>.
    </p>
</div>
@endsection
