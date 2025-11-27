@extends('layouts.admin')

@section('title', 'Lead Detayı: ' . $lead->lead_number)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-start">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('admin.leads.index') }}" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <h1 class="text-3xl font-bold text-gray-900">Lead Detayı</h1>
            </div>
            <p class="text-gray-600">Talep No: <span class="font-semibold">{{ $lead->lead_number }}</span></p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.leads.edit', $lead) }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700 transition">
                Düzenle
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Status Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold">Durum</h2>
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
                    <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $statusColors[$lead->status] ?? 'bg-gray-100' }}">
                        {{ $statusLabels[$lead->status] ?? $lead->status }}
                    </span>
                </div>
                
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500">Kaynak:</span>
                        <span class="ml-2 font-medium">{{ ucfirst($lead->source) }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Oluşturulma:</span>
                        <span class="ml-2 font-medium">{{ $lead->created_at->format('d.m.Y H:i') }}</span>
                    </div>
                    @if($lead->assignedUser)
                        <div>
                            <span class="text-gray-500">Atanan:</span>
                            <span class="ml-2 font-medium">{{ $lead->assignedUser->name }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Contact Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">İletişim Bilgileri</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm text-gray-500">Ad Soyad / Firma</label>
                        <p class="font-medium">{{ $lead->name }}</p>
                    </div>
                    @if($lead->company)
                        <div>
                            <label class="text-sm text-gray-500">Firma</label>
                            <p class="font-medium">{{ $lead->company }}</p>
                        </div>
                    @endif
                    <div>
                        <label class="text-sm text-gray-500">Telefon</label>
                        <p class="font-medium">{{ $lead->phone }}</p>
                    </div>
                    @if($lead->email)
                        <div>
                            <label class="text-sm text-gray-500">E-posta</label>
                            <p class="font-medium">{{ $lead->email }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Project Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Proje Bilgileri</h2>
                <div class="grid grid-cols-2 gap-4">
                    @if($lead->project_type)
                        <div>
                            <label class="text-sm text-gray-500">Proje Türü</label>
                            <p class="font-medium">{{ ucfirst($lead->project_type) }}</p>
                        </div>
                    @endif
                    @if($lead->full_location)
                        <div class="col-span-2">
                            <label class="text-sm text-gray-500">Lokasyon</label>
                            <p class="font-medium">{{ $lead->full_location }}</p>
                        </div>
                    @endif
                    @if($lead->area_m2)
                        <div>
                            <label class="text-sm text-gray-500">Alan</label>
                            <p class="font-medium">{{ number_format($lead->area_m2, 2) }} m²</p>
                        </div>
                    @endif
                    @if($lead->room_count)
                        <div>
                            <label class="text-sm text-gray-500">Oda Sayısı</label>
                            <p class="font-medium">{{ $lead->room_count }}</p>
                        </div>
                    @endif
                    @if($lead->requested_services)
                        <div class="col-span-2">
                            <label class="text-sm text-gray-500">İstenen Hizmetler</label>
                            <div class="mt-1">
                                @foreach($lead->requested_services as $service)
                                    <span class="inline-block bg-teal-100 text-teal-800 px-2 py-1 rounded text-sm mr-2 mb-2">
                                        {{ ucfirst(str_replace('_', ' ', $service)) }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            @if($lead->message)
                <!-- Message -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold mb-4">Mesaj / İhtiyaç Detayı</h2>
                    <p class="text-gray-700 whitespace-pre-wrap">{{ $lead->message }}</p>
                </div>
            @endif

            @if($lead->notes)
                <!-- Internal Notes -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold mb-4">İç Notlar</h2>
                    <p class="text-gray-700 whitespace-pre-wrap">{{ $lead->notes }}</p>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Hızlı İşlemler</h2>
                <div class="space-y-2">
                    @if($lead->status != 'won' && $lead->status != 'lost')
                        <a href="{{ route('admin.quotes.create', ['lead_id' => $lead->id]) }}" class="block w-full bg-teal-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-teal-700 transition text-center">
                            Teklif Oluştur
                        </a>
                    @endif
                    @if($lead->quotes->count() > 0)
                        <a href="{{ route('admin.quotes.index', ['lead_id' => $lead->id]) }}" class="block w-full border border-teal-600 text-teal-600 px-4 py-2 rounded-lg font-semibold hover:bg-teal-50 transition text-center">
                            Teklifleri Gör ({{ $lead->quotes->count() }})
                        </a>
                    @endif
                </div>
            </div>

            <!-- Timeline -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Zaman Çizelgesi</h2>
                <div class="space-y-4">
                    <div class="flex gap-3">
                        <div class="flex-shrink-0 w-2 h-2 bg-teal-600 rounded-full mt-2"></div>
                        <div>
                            <p class="font-medium text-sm">Lead oluşturuldu</p>
                            <p class="text-xs text-gray-500">{{ $lead->created_at->format('d.m.Y H:i') }}</p>
                        </div>
                    </div>
                    @if($lead->contacted_at)
                        <div class="flex gap-3">
                            <div class="flex-shrink-0 w-2 h-2 bg-yellow-600 rounded-full mt-2"></div>
                            <div>
                                <p class="font-medium text-sm">İletişime geçildi</p>
                                <p class="text-xs text-gray-500">{{ $lead->contacted_at->format('d.m.Y H:i') }}</p>
                            </div>
                        </div>
                    @endif
                    @if($lead->site_visit_at)
                        <div class="flex gap-3">
                            <div class="flex-shrink-0 w-2 h-2 bg-purple-600 rounded-full mt-2"></div>
                            <div>
                                <p class="font-medium text-sm">Keşif yapıldı</p>
                                <p class="text-xs text-gray-500">{{ $lead->site_visit_at->format('d.m.Y H:i') }}</p>
                            </div>
                        </div>
                    @endif
                    @if($lead->quoted_at)
                        <div class="flex gap-3">
                            <div class="flex-shrink-0 w-2 h-2 bg-indigo-600 rounded-full mt-2"></div>
                            <div>
                                <p class="font-medium text-sm">Teklif verildi</p>
                                <p class="text-xs text-gray-500">{{ $lead->quoted_at->format('d.m.Y H:i') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
