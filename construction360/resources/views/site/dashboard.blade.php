@extends('layouts.site')

@section('title', 'Saha Paneli')

@section('content')
<div class="space-y-6 p-4">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Saha Paneli</h1>
        <p class="text-gray-600 mt-1">Hoş geldiniz, {{ Auth::user()->name }}</p>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <a href="#" class="bg-teal-600 text-white p-4 rounded-lg text-center hover:bg-teal-700 transition">
            <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <p class="text-sm font-semibold">Günlük Rapor</p>
        </a>
        <a href="#" class="bg-blue-600 text-white p-4 rounded-lg text-center hover:bg-blue-700 transition">
            <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <p class="text-sm font-semibold">Fotoğraf Ekle</p>
        </a>
        <a href="{{ route('site.material-requests.create') }}" class="bg-green-600 text-white p-4 rounded-lg text-center hover:bg-green-700 transition">
            <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
            <p class="text-sm font-semibold">Malzeme Talebi</p>
        </a>
        <a href="{{ route('site.issues.create') }}" class="bg-red-600 text-white p-4 rounded-lg text-center hover:bg-red-700 transition">
            <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <p class="text-sm font-semibold">Issue Aç</p>
        </a>
    </div>

    <!-- Assigned Projects -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <h2 class="text-lg font-semibold mb-4">Atanan Projeler</h2>
        <div class="space-y-3">
            @forelse($projects as $project)
                <div class="border border-gray-200 rounded-lg p-3 hover:bg-gray-50 transition">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <h3 class="font-semibold text-gray-900">{{ $project->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $project->project_code }}</p>
                        </div>
                        @if($project->progress_percentage > 0)
                            <span class="text-sm font-semibold text-teal-600">{{ $project->progress_percentage }}%</span>
                        @endif
                    </div>
                    @if($project->location_city)
                        <p class="text-sm text-gray-600 mb-2">{{ $project->location_city }}, {{ $project->location_district }}</p>
                    @endif
                    <div class="flex gap-2 mt-3">
                        <a href="{{ route('site.projects.show', $project) }}" class="flex-1 bg-teal-600 text-white px-3 py-2 rounded-lg text-sm font-semibold hover:bg-teal-700 transition text-center">
                            Detay
                        </a>
                        <a href="{{ route('site.daily-report.create', $project->id) }}" class="flex-1 border border-teal-600 text-teal-600 px-3 py-2 rounded-lg text-sm font-semibold hover:bg-teal-50 transition text-center">
                            Rapor
                        </a>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center py-4">Atanan proje bulunmamaktadır.</p>
            @endforelse
        </div>
    </div>

    <!-- Open Issues -->
    @if($openIssues->count() > 0)
        <div class="bg-white rounded-lg shadow p-4">
            <h2 class="text-lg font-semibold mb-4">Açık Issues</h2>
            <div class="space-y-3">
                @foreach($openIssues as $issue)
                    <div class="border-l-4 border-red-500 bg-red-50 p-3 rounded">
                        <div class="flex justify-between items-start mb-1">
                            <h3 class="font-semibold text-gray-900">{{ $issue->title }}</h3>
                            @if($issue->priority == 'critical')
                                <span class="bg-red-600 text-white px-2 py-1 rounded text-xs font-semibold">KRİTİK</span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-600 mb-2">{{ Str::limit($issue->description, 100) }}</p>
                        <div class="flex items-center gap-4 text-xs text-gray-500">
                            <span>{{ $issue->project->name }}</span>
                            @if($issue->due_date)
                                <span class="{{ $issue->due_date < now() ? 'text-red-600 font-semibold' : '' }}">
                                    Son Tarih: {{ $issue->due_date->format('d.m.Y') }}
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
