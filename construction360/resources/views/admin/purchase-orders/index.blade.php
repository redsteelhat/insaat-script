@extends('layouts.admin')

@section('title', 'Satınalma Siparişleri')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Satınalma Siparişleri</h1>
            <p class="mt-2 text-gray-600">Tedarikçi siparişlerini yönetin</p>
        </div>
        <a href="{{ route('admin.purchase-orders.create') }}" class="bg-teal-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-teal-700 transition">
            + Yeni Sipariş Oluştur
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="GET" action="{{ route('admin.purchase-orders.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Ara (PO No...)" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
            </div>

            <div>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    <option value="">Tüm Durumlar</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Taslak</option>
                    <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Gönderildi</option>
                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Teslim Edildi</option>
                    <option value="invoiced" {{ request('status') == 'invoiced' ? 'selected' : '' }}>Faturalandı</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Ödendi</option>
                </select>
            </div>

            <div>
                <select name="vendor_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    <option value="">Tüm Tedarikçiler</option>
                    @foreach($vendors as $vendor)
                        <option value="{{ $vendor->id }}" {{ request('vendor_id') == $vendor->id ? 'selected' : '' }}>
                            {{ $vendor->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-teal-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-teal-700 transition">
                    Filtrele
                </button>
                <a href="{{ route('admin.purchase-orders.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                    Temizle
                </a>
            </div>
        </form>
    </div>

    <!-- Purchase Orders Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PO No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tedarikçi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proje</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sipariş Tarihi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teslim Tarihi</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Toplam</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durum</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($purchaseOrders as $po)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-gray-900">{{ $po->po_number }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $po->vendor->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $po->project ? $po->project->project_code : '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $po->order_date->format('d.m.Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $po->delivery_date ? $po->delivery_date->format('d.m.Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <span class="text-sm font-medium text-gray-900">
                                    {{ number_format($po->total_amount, 2) }} {{ $po->currency }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusColors = [
                                        'draft' => 'bg-gray-100 text-gray-800',
                                        'sent' => 'bg-blue-100 text-blue-800',
                                        'delivered' => 'bg-yellow-100 text-yellow-800',
                                        'invoiced' => 'bg-purple-100 text-purple-800',
                                        'paid' => 'bg-green-100 text-green-800',
                                        'cancelled' => 'bg-red-100 text-red-800',
                                    ];
                                    $statusLabels = [
                                        'draft' => 'Taslak',
                                        'sent' => 'Gönderildi',
                                        'delivered' => 'Teslim Edildi',
                                        'invoiced' => 'Faturalandı',
                                        'paid' => 'Ödendi',
                                        'cancelled' => 'İptal',
                                    ];
                                @endphp
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$po->status] ?? 'bg-gray-100' }}">
                                    {{ $statusLabels[$po->status] ?? $po->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.purchase-orders.show', $po) }}" class="text-teal-600 hover:text-teal-900 mr-3">Görüntüle</a>
                                @if($po->status == 'draft')
                                    <a href="{{ route('admin.purchase-orders.edit', $po) }}" class="text-blue-600 hover:text-blue-900">Düzenle</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                Henüz satınalma siparişi bulunmamaktadır.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($purchaseOrders->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $purchaseOrders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
