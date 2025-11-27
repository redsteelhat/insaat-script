@extends('layouts.admin')

@section('title', 'Proje Yönetimi')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Proje Yönetimi</h1>
            <p class="mt-2 text-gray-600">Aktif ve tamamlanan projeleri yönetin</p>
        </div>
        <a href="{{ route('admin.projects.create') }}" class="bg-teal-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-teal-700 transition">
            + Yeni Proje Oluştur
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="GET" action="{{ route('admin.projects.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Ara (Proje Kodu, Ad, Lokasyon...)" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
            </div>

            <!-- Status Filter -->
            <div>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    <option value="">Tüm Durumlar</option>
                    <option value="planned" {{ request('status') == 'planned' ? 'selected' : '' }}>Planlandı</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>Devam Ediyor</option>
                    <option value="on_hold" {{ request('status') == 'on_hold' ? 'selected' : '' }}>Beklemede</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Tamamlandı</option>
                    <option value="handed_over" {{ request('status') == 'handed_over' ? 'selected' : '' }}>Teslim Edildi</option>
                </select>
            </div>

            <!-- Project Type Filter -->
            <div>
                <select name="project_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    <option value="">Tüm Proje Türleri</option>
                    <option value="konut" {{ request('project_type') == 'konut' ? 'selected' : '' }}>Konut</option>
                    <option value="ticari" {{ request('project_type') == 'ticari' ? 'selected' : '' }}>Ticari</option>
                    <option value="endustriyel" {{ request('project_type') == 'endustriyel' ? 'selected' : '' }}>Endüstriyel</option>
                    <option value="tadilat" {{ request('project_type') == 'tadilat' ? 'selected' : '' }}>Tadilat</option>
                </select>
            </div>

            <!-- Actions -->
            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-teal-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-teal-700 transition">
                    Filtrele
                </button>
                <a href="{{ route('admin.projects.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                    Temizle
                </a>
            </div>
        </form>
    </div>

    <!-- Projects Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($projects as $project)
            <div class="bg-white rounded-lg shadow hover:shadow-lg transition">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $project->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $project->project_code }}</p>
                        </div>
                        @php
                            $statusColors = [
                                'planned' => 'bg-blue-100 text-blue-800',
                                'in_progress' => 'bg-green-100 text-green-800',
                                'on_hold' => 'bg-yellow-100 text-yellow-800',
                                'completed' => 'bg-purple-100 text-purple-800',
                                'handed_over' => 'bg-gray-100 text-gray-800',
                                'cancelled' => 'bg-red-100 text-red-800',
                            ];
                            $statusLabels = [
                                'planned' => 'Planlandı',
                                'in_progress' => 'Devam Ediyor',
                                'on_hold' => 'Beklemede',
                                'completed' => 'Tamamlandı',
                                'handed_over' => 'Teslim Edildi',
                                'cancelled' => 'İptal',
                            ];
                        @endphp
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$project->status] ?? 'bg-gray-100' }}">
                            {{ $statusLabels[$project->status] ?? $project->status }}
                        </span>
                    </div>

                    <div class="space-y-2 text-sm text-gray-600 mb-4">
                        @if($project->location_city)
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                {{ $project->location_city }}@if($project->location_district), {{ $project->location_district }}@endif
                            </div>
                        @endif
                        @if($project->contract_amount > 0)
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ number_format($project->contract_amount, 2) }} {{ $project->currency }}
                            </div>
                        @endif
                        @if($project->progress_percentage > 0)
                            <div>
                                <div class="flex justify-between text-xs mb-1">
                                    <span>İlerleme</span>
                                    <span>{{ $project->progress_percentage }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-teal-600 h-2 rounded-full" style="width: {{ $project->progress_percentage }}%"></div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="flex gap-2 pt-4 border-t border-gray-200">
                        <a href="{{ route('admin.projects.show', $project) }}" class="flex-1 bg-teal-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-teal-700 transition text-center text-sm">
                            Görüntüle
                        </a>
                        <a href="{{ route('admin.projects.edit', $project) }}" class="flex-1 border border-gray-300 text-gray-700 px-4 py-2 rounded-lg font-semibold hover:bg-gray-50 transition text-center text-sm">
                            Düzenle
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-lg shadow p-12 text-center">
                <p class="text-gray-500">Henüz proje kaydı bulunmamaktadır.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($projects->hasPages())
        <div class="bg-white rounded-lg shadow p-4">
            {{ $projects->links() }}
        </div>
    @endif
</div>
@endsection
