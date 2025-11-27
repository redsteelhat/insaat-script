@extends('layouts.admin')

@section('title', 'Teklif Detayı: ' . $quote->quote_number)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-start">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('admin.quotes.index') }}" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <h1 class="text-3xl font-bold text-gray-900">Teklif Detayı</h1>
            </div>
            <p class="text-gray-600">Teklif No: <span class="font-semibold">{{ $quote->quote_number }} v{{ $quote->version }}</span></p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.quotes.edit', $quote) }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700 transition">
                Düzenle
            </a>
            @if($quote->status == 'draft')
                <form method="POST" action="{{ route('admin.quotes.send', $quote) }}" class="inline">
                    @csrf
                    <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-green-700 transition">
                        Müşteriye Gönder
                    </button>
                </form>
            @endif
            <form method="POST" action="{{ route('admin.quotes.duplicate', $quote) }}" class="inline">
                @csrf
                <button type="submit" class="bg-purple-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-purple-700 transition">
                    Yeni Versiyon
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Client Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Müşteri Bilgileri</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm text-gray-500">Müşteri Adı</label>
                        <p class="font-medium">{{ $quote->client_name }}</p>
                    </div>
                    @if($quote->client_phone)
                        <div>
                            <label class="text-sm text-gray-500">Telefon</label>
                            <p class="font-medium">{{ $quote->client_phone }}</p>
                        </div>
                    @endif
                    @if($quote->client_email)
                        <div>
                            <label class="text-sm text-gray-500">E-posta</label>
                            <p class="font-medium">{{ $quote->client_email }}</p>
                        </div>
                    @endif
                    @if($quote->lead)
                        <div>
                            <label class="text-sm text-gray-500">Lead No</label>
                            <p class="font-medium">
                                <a href="{{ route('admin.leads.show', $quote->lead) }}" class="text-teal-600 hover:underline">
                                    {{ $quote->lead->lead_number }}
                                </a>
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quote Items -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Teklif Kalemleri (BOQ)</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kod</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Açıklama</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Birim</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Miktar</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Birim Fiyat</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Toplam</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($quote->items as $item)
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $item->code ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $item->description }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-500">{{ $item->unit ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm text-right text-gray-900">{{ number_format($item->quantity, 2) }}</td>
                                    <td class="px-4 py-3 text-sm text-right text-gray-900">{{ number_format($item->unit_price, 2) }} {{ $quote->currency }}</td>
                                    <td class="px-4 py-3 text-sm text-right font-medium text-gray-900">{{ number_format($item->total_price, 2) }} {{ $quote->currency }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-gray-500">Henüz kalem eklenmemiş</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="5" class="px-4 py-3 text-right font-medium text-gray-900">Ara Toplam:</td>
                                <td class="px-4 py-3 text-right font-medium text-gray-900">{{ number_format($quote->subtotal, 2) }} {{ $quote->currency }}</td>
                            </tr>
                            @if($quote->discount_amount > 0)
                                <tr>
                                    <td colspan="5" class="px-4 py-3 text-right font-medium text-gray-900">İskonto ({{ $quote->discount_percentage }}%):</td>
                                    <td class="px-4 py-3 text-right font-medium text-red-600">-{{ number_format($quote->discount_amount, 2) }} {{ $quote->currency }}</td>
                                </tr>
                            @endif
                            <tr>
                                <td colspan="5" class="px-4 py-3 text-right font-medium text-gray-900">KDV ({{ $quote->tax_percentage }}%):</td>
                                <td class="px-4 py-3 text-right font-medium text-gray-900">{{ number_format($quote->tax_amount, 2) }} {{ $quote->currency }}</td>
                            </tr>
                            <tr class="border-t-2 border-gray-300">
                                <td colspan="5" class="px-4 py-3 text-right font-bold text-lg text-gray-900">GENEL TOPLAM:</td>
                                <td class="px-4 py-3 text-right font-bold text-lg text-gray-900">{{ number_format($quote->total_amount, 2) }} {{ $quote->currency }}</td>
                            </tr>
                        </tfoot>
                    </table>
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
                            'draft' => 'bg-gray-100 text-gray-800',
                            'sent' => 'bg-blue-100 text-blue-800',
                            'approved' => 'bg-green-100 text-green-800',
                            'rejected' => 'bg-red-100 text-red-800',
                            'contracted' => 'bg-purple-100 text-purple-800',
                        ];
                        $statusLabels = [
                            'draft' => 'Taslak',
                            'sent' => 'Gönderildi',
                            'approved' => 'Onaylandı',
                            'rejected' => 'Reddedildi',
                            'contracted' => 'Sözleşmeye Dönüştü',
                        ];
                    @endphp
                    <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $statusColors[$quote->status] ?? 'bg-gray-100' }}">
                        {{ $statusLabels[$quote->status] ?? $quote->status }}
                    </span>
                </div>
                
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Oluşturulma:</span>
                        <span class="font-medium">{{ $quote->created_at->format('d.m.Y H:i') }}</span>
                    </div>
                    @if($quote->sent_at)
                        <div class="flex justify-between">
                            <span class="text-gray-500">Gönderilme:</span>
                            <span class="font-medium">{{ $quote->sent_at->format('d.m.Y H:i') }}</span>
                        </div>
                    @endif
                    @if($quote->valid_until)
                        <div class="flex justify-between">
                            <span class="text-gray-500">Geçerlilik:</span>
                            <span class="font-medium">{{ $quote->valid_until->format('d.m.Y') }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Summary Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Özet</h2>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Ara Toplam:</span>
                        <span class="font-medium">{{ number_format($quote->subtotal, 2) }} {{ $quote->currency }}</span>
                    </div>
                    @if($quote->discount_amount > 0)
                        <div class="flex justify-between">
                            <span class="text-gray-600">İskonto:</span>
                            <span class="font-medium text-red-600">-{{ number_format($quote->discount_amount, 2) }} {{ $quote->currency }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-gray-600">KDV (%{{ $quote->tax_percentage }}):</span>
                        <span class="font-medium">{{ number_format($quote->tax_amount, 2) }} {{ $quote->currency }}</span>
                    </div>
                    <div class="pt-3 border-t border-gray-200 flex justify-between">
                        <span class="text-lg font-bold text-gray-900">TOPLAM:</span>
                        <span class="text-lg font-bold text-gray-900">{{ number_format($quote->total_amount, 2) }} {{ $quote->currency }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Hızlı İşlemler</h2>
                <div class="space-y-2">
                    <button onclick="window.print()" class="block w-full bg-gray-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-gray-700 transition text-center">
                        PDF İndir
                    </button>
                    @if($quote->status == 'approved' && !$quote->project)
                        <a href="{{ route('admin.contracts.create', ['quote_id' => $quote->id]) }}" class="block w-full bg-teal-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-teal-700 transition text-center">
                            Sözleşme Oluştur
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
