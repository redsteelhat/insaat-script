@extends('layouts.admin')

@section('title', 'Tedarikçi Detayı: ' . $vendor->name)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-start">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('admin.vendors.index') }}" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <h1 class="text-3xl font-bold text-gray-900">Tedarikçi Detayı</h1>
            </div>
            <p class="text-gray-600">Firma: <span class="font-semibold">{{ $vendor->name }}</span></p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.vendors.edit', $vendor) }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700 transition">
                Düzenle
            </a>
            <a href="{{ route('admin.purchase-orders.create', ['vendor_id' => $vendor->id]) }}" class="bg-teal-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-teal-700 transition">
                Satınalma Siparişi Oluştur
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Company Info -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Firma Bilgileri</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm text-gray-500">Firma Adı</label>
                        <p class="font-medium">{{ $vendor->name }}</p>
                    </div>
                    @if($vendor->code)
                        <div>
                            <label class="text-sm text-gray-500">Tedarikçi Kodu</label>
                            <p class="font-medium">{{ $vendor->code }}</p>
                        </div>
                    @endif
                    @if($vendor->category)
                        <div>
                            <label class="text-sm text-gray-500">Kategori</label>
                            <p class="font-medium">{{ ucfirst($vendor->category) }}</p>
                        </div>
                    @endif
                    <div>
                        <label class="text-sm text-gray-500">Durum</label>
                        <p>
                            @if($vendor->is_active)
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Pasif</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Tax Info -->
            @if($vendor->tax_number || $vendor->tax_office)
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold mb-4">Vergi Bilgileri</h2>
                    <div class="grid grid-cols-2 gap-4">
                        @if($vendor->tax_number)
                            <div>
                                <label class="text-sm text-gray-500">Vergi No</label>
                                <p class="font-medium">{{ $vendor->tax_number }}</p>
                            </div>
                        @endif
                        @if($vendor->tax_office)
                            <div>
                                <label class="text-sm text-gray-500">Vergi Dairesi</label>
                                <p class="font-medium">{{ $vendor->tax_office }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Contact Info -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">İletişim Bilgileri</h2>
                <div class="grid grid-cols-2 gap-4">
                    @if($vendor->contact_person)
                        <div>
                            <label class="text-sm text-gray-500">İletişim Kişisi</label>
                            <p class="font-medium">{{ $vendor->contact_person }}</p>
                        </div>
                    @endif
                    @if($vendor->phone)
                        <div>
                            <label class="text-sm text-gray-500">Telefon</label>
                            <p class="font-medium">{{ $vendor->phone }}</p>
                        </div>
                    @endif
                    @if($vendor->email)
                        <div>
                            <label class="text-sm text-gray-500">E-posta</label>
                            <p class="font-medium">{{ $vendor->email }}</p>
                        </div>
                    @endif
                    @if($vendor->website)
                        <div>
                            <label class="text-sm text-gray-500">Web Sitesi</label>
                            <p class="font-medium">
                                <a href="{{ $vendor->website }}" target="_blank" class="text-teal-600 hover:underline">{{ $vendor->website }}</a>
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Address -->
            @if($vendor->full_address)
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold mb-4">Adres</h2>
                    <p class="text-gray-700">{{ $vendor->full_address }}</p>
                </div>
            @endif

            @if($vendor->notes)
                <!-- Notes -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold mb-4">Notlar</h2>
                    <p class="text-gray-700 whitespace-pre-wrap">{{ $vendor->notes }}</p>
                </div>
            @endif

            <!-- Purchase Orders -->
            @if($vendor->purchaseOrders->count() > 0)
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold mb-4">Satınalma Siparişleri ({{ $vendor->purchaseOrders->count() }})</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sipariş No</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Proje</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tarih</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Tutar</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Durum</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($vendor->purchaseOrders->take(10) as $po)
                                    <tr>
                                        <td class="px-4 py-3 text-sm">
                                            <a href="{{ route('admin.purchase-orders.show', $po) }}" class="text-teal-600 hover:underline">
                                                {{ $po->po_number }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900">
                                            {{ $po->project ? $po->project->project_code : '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-500">
                                            {{ $po->order_date->format('d.m.Y') }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-right font-medium">
                                            {{ number_format($po->total_amount, 2) }} {{ $po->currency }}
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                {{ ucfirst($po->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Stats -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">İstatistikler</h2>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Toplam Sipariş:</span>
                        <span class="font-medium">{{ $vendor->purchaseOrders->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Toplam Tutar:</span>
                        <span class="font-medium">{{ number_format($vendor->purchaseOrders->sum('total_amount'), 2) }} TRY</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
