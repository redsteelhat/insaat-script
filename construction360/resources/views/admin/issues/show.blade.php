@extends('layouts.admin')

@section('title', 'Issue Detayı: ' . $issue->issue_number)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-start">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('admin.issues.index') }}" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <h1 class="text-3xl font-bold text-gray-900">Issue Detayı</h1>
            </div>
            <p class="text-gray-600">Issue No: <span class="font-semibold">{{ $issue->issue_number }}</span></p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.issues.edit', $issue) }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700 transition">
                Düzenle
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Issue Info -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold">{{ $issue->title }}</h2>
                    @php
                        $priorityColors = [
                            'low' => 'bg-gray-100 text-gray-800',
                            'medium' => 'bg-blue-100 text-blue-800',
                            'high' => 'bg-orange-100 text-orange-800',
                            'critical' => 'bg-red-100 text-red-800',
                        ];
                        $priorityLabels = [
                            'low' => 'Düşük',
                            'medium' => 'Orta',
                            'high' => 'Yüksek',
                            'critical' => 'Kritik',
                        ];
                    @endphp
                    <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $priorityColors[$issue->priority] ?? 'bg-gray-100' }}">
                        {{ $priorityLabels[$issue->priority] ?? $issue->priority }}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-4 text-sm mb-4">
                    @if($issue->project)
                        <div>
                            <span class="text-gray-500">Proje:</span>
                            <a href="{{ route('admin.projects.show', $issue->project) }}" class="ml-2 font-medium text-teal-600 hover:underline">
                                {{ $issue->project->project_code }}
                            </a>
                        </div>
                    @endif
                    <div>
                        <span class="text-gray-500">Kategori:</span>
                        <span class="ml-2 font-medium">{{ ucfirst(str_replace('_', ' ', $issue->category)) }}</span>
                    </div>
                    @if($issue->location)
                        <div>
                            <span class="text-gray-500">Lokasyon:</span>
                            <span class="ml-2 font-medium">{{ $issue->location }}</span>
                        </div>
                    @endif
                </div>

                <div>
                    <label class="text-sm text-gray-500">Açıklama</label>
                    <p class="mt-2 text-gray-700 whitespace-pre-wrap">{{ $issue->description }}</p>
                </div>
            </div>

            <!-- Photos -->
            @if($issue->photos && count($issue->photos) > 0)
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold mb-4">Fotoğraflar</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($issue->photos as $photoPath)
                            <div class="relative group">
                                <img src="{{ Storage::url($photoPath) }}" alt="Issue Photo" class="w-full h-48 object-cover rounded-lg">
                                <a href="{{ Storage::url($photoPath) }}" target="_blank" class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 flex items-center justify-center rounded-lg transition">
                                    <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                                    </svg>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Resolution -->
            @if($issue->resolution)
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold mb-4">Çözüm</h2>
                    <p class="text-gray-700 whitespace-pre-wrap">{{ $issue->resolution }}</p>
                </div>
            @endif

            <!-- Comments -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Yorumlar</h2>
                <div class="space-y-4">
                    @forelse($issue->comments as $comment)
                        <div class="border-l-4 border-teal-500 pl-4">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $comment->creator->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $comment->created_at->format('d.m.Y H:i') }}</p>
                                </div>
                            </div>
                            <p class="text-gray-700 whitespace-pre-wrap">{{ $comment->comment }}</p>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">Henüz yorum yapılmamış</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold">Durum</h2>
                    @php
                        $statusColors = [
                            'open' => 'bg-yellow-100 text-yellow-800',
                            'in_progress' => 'bg-blue-100 text-blue-800',
                            'resolved' => 'bg-green-100 text-green-800',
                            'closed' => 'bg-gray-100 text-gray-800',
                        ];
                        $statusLabels = [
                            'open' => 'Açık',
                            'in_progress' => 'İşlemde',
                            'resolved' => 'Çözüldü',
                            'closed' => 'Kapalı',
                        ];
                    @endphp
                    <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $statusColors[$issue->status] ?? 'bg-gray-100' }}">
                        {{ $statusLabels[$issue->status] ?? $issue->status }}
                    </span>
                </div>
                
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Oluşturulma:</span>
                        <span class="font-medium">{{ $issue->created_at->format('d.m.Y H:i') }}</span>
                    </div>
                    @if($issue->assignee)
                        <div class="flex justify-between">
                            <span class="text-gray-500">Atanan:</span>
                            <span class="font-medium">{{ $issue->assignee->name }}</span>
                        </div>
                    @endif
                    @if($issue->due_date)
                        <div class="flex justify-between">
                            <span class="text-gray-500">Son Tarih:</span>
                            <span class="font-medium {{ $issue->due_date < now() && in_array($issue->status, ['open', 'in_progress']) ? 'text-red-600' : '' }}">
                                {{ $issue->due_date->format('d.m.Y') }}
                            </span>
                        </div>
                    @endif
                    @if($issue->resolved_at)
                        <div class="flex justify-between">
                            <span class="text-gray-500">Çözülme:</span>
                            <span class="font-medium">{{ $issue->resolved_at->format('d.m.Y H:i') }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Links -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Hızlı İşlemler</h2>
                <div class="space-y-2">
                    @if($issue->project)
                        <a href="{{ route('admin.projects.show', $issue->project) }}" class="block w-full bg-teal-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-teal-700 transition text-center">
                            Projeyi Görüntüle
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
