@extends('layouts.admin')

@section('title', 'Lead & CRM Yönetimi')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Lead & CRM Yönetimi</h1>
            <p class="mt-2 text-gray-600">Müşteri adaylarını ve teklif taleplerini yönetin</p>
        </div>
        <a href="{{ route('admin.leads.create') }}" class="bg-teal-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-teal-700 transition">
            + Yeni Lead Ekle
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="GET" action="{{ route('admin.leads.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Ara (İsim, Telefon, Talep No...)" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
            </div>

            <!-- Status Filter -->
            <div>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    <option value="">Tüm Durumlar</option>
                    <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>Yeni</option>
                    <option value="contacted" {{ request('status') == 'contacted' ? 'selected' : '' }}>İletişime Geçildi</option>
                    <option value="site_visit_planned" {{ request('status') == 'site_visit_planned' ? 'selected' : '' }}>Keşif Planlandı</option>
                    <option value="quoted" {{ request('status') == 'quoted' ? 'selected' : '' }}>Teklif Verildi</option>
                    <option value="won" {{ request('status') == 'won' ? 'selected' : '' }}>Kazanıldı</option>
                    <option value="lost" {{ request('status') == 'lost' ? 'selected' : '' }}>Kaybedildi</option>
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
                <a href="{{ route('admin.leads.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                    Temizle
                </a>
            </div>
        </form>
    </div>

    <!-- Leads Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Talep No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">İsim/Firma</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">İletişim</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proje Türü</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durum</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kaynak</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tarih</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($leads as $lead)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-gray-900">{{ $lead->lead_number }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $lead->name }}</div>
                                @if($lead->company)
                                    <div class="text-sm text-gray-500">{{ $lead->company }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $lead->phone }}</div>
                                @if($lead->email)
                                    <div class="text-sm text-gray-500">{{ $lead->email }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900">
                                    {{ ucfirst($lead->project_type ?? '-') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusColors = [
                                        'new' => 'bg-blue-100 text-blue-800',
                                        'contacted' => 'bg-yellow-100 text-yellow-800',
                                        'site_visit_planned' => 'bg-purple-100 text-purple-800',
                                        'quoted' => 'bg-indigo-100 text-indigo-800',
                                        'won' => 'bg-green-100 text-green-800',
                                        'lost' => 'bg-red-100 text-red-800',
                                    ];
                                    $statusLabels = [
                                        'new' => 'Yeni',
                                        'contacted' => 'İletişime Geçildi',
                                        'site_visit_planned' => 'Keşif Planlandı',
                                        'quoted' => 'Teklif Verildi',
                                        'won' => 'Kazanıldı',
                                        'lost' => 'Kaybedildi',
                                    ];
                                @endphp
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$lead->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $statusLabels[$lead->status] ?? $lead->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900">{{ ucfirst($lead->source) }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $lead->created_at->format('d.m.Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.leads.show', $lead) }}" class="text-teal-600 hover:text-teal-900 mr-3">Görüntüle</a>
                                <a href="{{ route('admin.leads.edit', $lead) }}" class="text-blue-600 hover:text-blue-900">Düzenle</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                Henüz lead kaydı bulunmamaktadır.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($leads->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $leads->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
