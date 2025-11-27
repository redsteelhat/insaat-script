@extends('layouts.admin')

@section('title', 'Sözleşme Yönetimi')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Sözleşme Yönetimi</h1>
            <p class="mt-2 text-gray-600">Tüm sözleşmeleri yönetin</p>
        </div>
        <a href="{{ route('admin.contracts.create') }}" class="bg-teal-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-teal-700 transition">
            + Yeni Sözleşme Oluştur
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="GET" action="{{ route('admin.contracts.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Ara (Sözleşme No, Başlık...)" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
            </div>

            <div>
                <select name="project_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    <option value="">Tüm Projeler</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>
                            {{ $project->project_code }} - {{ $project->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-teal-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-teal-700 transition">
                    Filtrele
                </button>
                <a href="{{ route('admin.contracts.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                    Temizle
                </a>
            </div>
        </form>
    </div>

    <!-- Contracts Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sözleşme No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Başlık</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proje</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sözleşme Bedeli</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sözleşme Tarihi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">İmza Durumu</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($contracts as $contract)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-gray-900">{{ $contract->contract_number }}</span>
                                @if($contract->version > 1)
                                    <span class="text-xs text-gray-500">v{{ $contract->version }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $contract->title }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($contract->project)
                                    <div class="text-sm text-gray-900">{{ $contract->project->project_code }}</div>
                                    <div class="text-sm text-gray-500">{{ $contract->project->name }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-gray-900">
                                    {{ number_format($contract->contract_amount, 2) }} {{ $contract->currency }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $contract->contract_date ? $contract->contract_date->format('d.m.Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($contract->signed_at)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        İmzalı
                                    </span>
                                    <div class="text-xs text-gray-500 mt-1">{{ $contract->signed_at->format('d.m.Y') }}</div>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        İmzasız
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.contracts.show', $contract) }}" class="text-teal-600 hover:text-teal-900 mr-3">Görüntüle</a>
                                <a href="{{ route('admin.contracts.edit', $contract) }}" class="text-blue-600 hover:text-blue-900">Düzenle</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                Henüz sözleşme kaydı bulunmamaktadır.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($contracts->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $contracts->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
