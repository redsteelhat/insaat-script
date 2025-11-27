@extends('layouts.admin')

@section('title', 'Sözleşme Detayı: ' . $contract->contract_number)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-start">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('admin.contracts.index') }}" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <h1 class="text-3xl font-bold text-gray-900">Sözleşme Detayı</h1>
            </div>
            <p class="text-gray-600">Sözleşme No: <span class="font-semibold">{{ $contract->contract_number }} v{{ $contract->version }}</span></p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.contracts.edit', $contract) }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700 transition">
                Düzenle
            </a>
            @if(!$contract->signed_at)
                <button onclick="window.print()" class="bg-gray-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-gray-700 transition">
                    PDF İndir
                </button>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Contract Info -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Sözleşme Bilgileri</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm text-gray-500">Başlık</label>
                        <p class="font-medium">{{ $contract->title }}</p>
                    </div>
                    @if($contract->project)
                        <div>
                            <label class="text-sm text-gray-500">Proje</label>
                            <p class="font-medium">
                                <a href="{{ route('admin.projects.show', $contract->project) }}" class="text-teal-600 hover:underline">
                                    {{ $contract->project->project_code }}
                                </a>
                            </p>
                        </div>
                    @endif
                    <div>
                        <label class="text-sm text-gray-500">Sözleşme Tarihi</label>
                        <p class="font-medium">{{ $contract->contract_date ? $contract->contract_date->format('d.m.Y') : '-' }}</p>
                    </div>
                    @if($contract->start_date)
                        <div>
                            <label class="text-sm text-gray-500">Başlangıç Tarihi</label>
                            <p class="font-medium">{{ $contract->start_date->format('d.m.Y') }}</p>
                        </div>
                    @endif
                    @if($contract->end_date)
                        <div>
                            <label class="text-sm text-gray-500">Bitiş Tarihi</label>
                            <p class="font-medium">{{ $contract->end_date->format('d.m.Y') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Financial Summary -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Finansal Özet</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm text-gray-500">Sözleşme Bedeli</label>
                        <p class="text-lg font-bold text-gray-900">{{ number_format($contract->contract_amount, 2) }} {{ $contract->currency }}</p>
                    </div>
                    @if($contract->advance_amount > 0)
                        <div>
                            <label class="text-sm text-gray-500">Avans Tutarı</label>
                            <p class="text-lg font-medium text-gray-900">{{ number_format($contract->advance_amount, 2) }} {{ $contract->currency }}</p>
                        </div>
                    @endif
                    @if($contract->retention_amount > 0)
                        <div>
                            <label class="text-sm text-gray-500">Teminat Tutarı</label>
                            <p class="text-lg font-medium text-gray-900">{{ number_format($contract->retention_amount, 2) }} {{ $contract->currency }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Terms -->
            @if($contract->terms)
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold mb-4">Sözleşme Şartları</h2>
                    <div class="prose max-w-none">
                        {!! nl2br(e($contract->terms)) !!}
                    </div>
                </div>
            @endif

            @if($contract->notes)
                <!-- Notes -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold mb-4">Notlar</h2>
                    <p class="text-gray-700 whitespace-pre-wrap">{{ $contract->notes }}</p>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Durum</h2>
                <div class="space-y-3">
                    @if($contract->signed_at)
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">İmza Durumu:</span>
                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">İmzalı</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">İmza Tarihi:</span>
                            <span class="font-medium">{{ $contract->signed_at->format('d.m.Y H:i') }}</span>
                        </div>
                    @else
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">İmza Durumu:</span>
                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">İmzasız</span>
                        </div>
                    @endif
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Oluşturulma:</span>
                        <span class="font-medium">{{ $contract->created_at->format('d.m.Y H:i') }}</span>
                    </div>
                    @if($contract->creator)
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Oluşturan:</span>
                            <span class="font-medium">{{ $contract->creator->name }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Links -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Hızlı İşlemler</h2>
                <div class="space-y-2">
                    @if($contract->project)
                        <a href="{{ route('admin.projects.show', $contract->project) }}" class="block w-full bg-teal-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-teal-700 transition text-center">
                            Projeyi Görüntüle
                        </a>
                    @endif
                    @if($contract->project && $contract->project->quote)
                        <a href="{{ route('admin.quotes.show', $contract->project->quote) }}" class="block w-full border border-teal-600 text-teal-600 px-4 py-2 rounded-lg font-semibold hover:bg-teal-50 transition text-center">
                            Teklifi Görüntüle
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
