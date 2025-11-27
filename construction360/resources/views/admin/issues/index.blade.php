@extends('layouts.admin')

@section('title', 'Issue / Punch List Yönetimi')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Issue / Punch List Yönetimi</h1>
            <p class="mt-2 text-gray-600">Proje sorunlarını ve punch list'leri yönetin</p>
        </div>
        <a href="{{ route('admin.issues.create') }}" class="bg-teal-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-teal-700 transition">
            + Yeni Issue Oluştur
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="GET" action="{{ route('admin.issues.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Ara (Issue No, Başlık...)" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
            </div>

            <div>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    <option value="">Tüm Durumlar</option>
                    <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Açık</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>İşlemde</option>
                    <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Çözüldü</option>
                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Kapalı</option>
                </select>
            </div>

            <div>
                <select name="priority" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    <option value="">Tüm Öncelikler</option>
                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Düşük</option>
                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Orta</option>
                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>Yüksek</option>
                    <option value="critical" {{ request('priority') == 'critical' ? 'selected' : '' }}>Kritik</option>
                </select>
            </div>

            <div>
                <select name="project_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    <option value="">Tüm Projeler</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>
                            {{ $project->project_code }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-teal-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-teal-700 transition">
                    Filtrele
                </button>
                <a href="{{ route('admin.issues.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                    Temizle
                </a>
            </div>
        </form>
    </div>

    <!-- Issues Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Issue No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Başlık</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proje</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Öncelik</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durum</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Atanan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Son Tarih</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($issues as $issue)
                        <tr class="hover:bg-gray-50 {{ $issue->due_date && $issue->due_date < now() && in_array($issue->status, ['open', 'in_progress']) ? 'bg-red-50' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-gray-900">{{ $issue->issue_number }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $issue->title }}</div>
                                <div class="text-sm text-gray-500">{{ Str::limit($issue->description, 50) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($issue->project)
                                    <div class="text-sm text-gray-900">{{ $issue->project->project_code }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $categoryLabels = [
                                        'kalite' => 'Kalite',
                                        'is_guvenligi' => 'İş Güvenliği',
                                        'tedarik' => 'Tedarik',
                                        'tasarim' => 'Tasarım',
                                        'musteri' => 'Müşteri',
                                        'diger' => 'Diğer',
                                    ];
                                @endphp
                                <span class="text-sm text-gray-900">{{ $categoryLabels[$issue->category] ?? $issue->category }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
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
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $priorityColors[$issue->priority] ?? 'bg-gray-100' }}">
                                    {{ $priorityLabels[$issue->priority] ?? $issue->priority }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
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
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$issue->status] ?? 'bg-gray-100' }}">
                                    {{ $statusLabels[$issue->status] ?? $issue->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $issue->assignee ? $issue->assignee->name : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($issue->due_date)
                                    <span class="{{ $issue->due_date < now() && in_array($issue->status, ['open', 'in_progress']) ? 'text-red-600 font-semibold' : 'text-gray-500' }}">
                                        {{ $issue->due_date->format('d.m.Y') }}
                                    </span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.issues.show', $issue) }}" class="text-teal-600 hover:text-teal-900 mr-3">Görüntüle</a>
                                <a href="{{ route('admin.issues.edit', $issue) }}" class="text-blue-600 hover:text-blue-900">Düzenle</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                                Henüz issue kaydı bulunmamaktadır.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($issues->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $issues->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
