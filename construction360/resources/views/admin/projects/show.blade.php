@extends('layouts.admin')

@section('title', 'Proje Detayı: ' . $project->project_code)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-start">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('admin.projects.index') }}" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <h1 class="text-3xl font-bold text-gray-900">Proje Detayı</h1>
            </div>
            <p class="text-gray-600">Proje Kodu: <span class="font-semibold">{{ $project->project_code }}</span></p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.projects.edit', $project) }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700 transition">
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
                    <h2 class="text-xl font-semibold">Proje Bilgileri</h2>
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
                    <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $statusColors[$project->status] ?? 'bg-gray-100' }}">
                        {{ $statusLabels[$project->status] ?? $project->status }}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500">Proje Adı:</span>
                        <span class="ml-2 font-medium">{{ $project->name }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Proje Türü:</span>
                        <span class="ml-2 font-medium">{{ ucfirst($project->project_type) }}</span>
                    </div>
                    @if($project->client)
                        <div>
                            <span class="text-gray-500">Müşteri:</span>
                            <span class="ml-2 font-medium">{{ $project->client->name }}</span>
                        </div>
                    @endif
                    @if($project->site)
                        <div>
                            <span class="text-gray-500">Şantiye:</span>
                            <span class="ml-2 font-medium">{{ $project->site->name }}</span>
                        </div>
                    @endif
                    @if($project->start_date)
                        <div>
                            <span class="text-gray-500">Başlangıç:</span>
                            <span class="ml-2 font-medium">{{ $project->start_date->format('d.m.Y') }}</span>
                        </div>
                    @endif
                    @if($project->planned_end_date)
                        <div>
                            <span class="text-gray-500">Planlanan Bitiş:</span>
                            <span class="ml-2 font-medium">{{ $project->planned_end_date->format('d.m.Y') }}</span>
                        </div>
                    @endif
                </div>

                @if($project->progress_percentage > 0)
                    <div class="mt-4">
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-gray-600">İlerleme</span>
                            <span class="font-semibold">{{ $project->progress_percentage }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="bg-teal-600 h-3 rounded-full transition-all" style="width: {{ $project->progress_percentage }}%"></div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Location -->
            @if($project->full_location)
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold mb-4">Lokasyon</h2>
                    <p class="text-gray-700">{{ $project->full_location }}</p>
                </div>
            @endif

            <!-- Financial Summary -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Finansal Özet</h2>
                <div class="grid grid-cols-2 gap-4">
                    @if($project->contract_amount > 0)
                        <div>
                            <label class="text-sm text-gray-500">Sözleşme Bedeli</label>
                            <p class="text-lg font-semibold text-gray-900">{{ number_format($project->contract_amount, 2) }} {{ $project->currency }}</p>
                        </div>
                    @endif
                    @if($project->budget_amount > 0)
                        <div>
                            <label class="text-sm text-gray-500">Bütçe</label>
                            <p class="text-lg font-semibold text-gray-900">{{ number_format($project->budget_amount, 2) }} {{ $project->currency }}</p>
                        </div>
                    @endif
                    @if($project->actual_cost > 0)
                        <div>
                            <label class="text-sm text-gray-500">Gerçekleşen Maliyet</label>
                            <p class="text-lg font-semibold text-gray-900">{{ number_format($project->actual_cost, 2) }} {{ $project->currency }}</p>
                        </div>
                    @endif
                    @if($project->budget_amount > 0 && $project->actual_cost > 0)
                        @php
                            $variance = $project->budget_amount - $project->actual_cost;
                            $variancePercent = ($variance / $project->budget_amount) * 100;
                        @endphp
                        <div>
                            <label class="text-sm text-gray-500">Sapma</label>
                            <p class="text-lg font-semibold {{ $variance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $variance >= 0 ? '+' : '' }}{{ number_format($variance, 2) }} {{ $project->currency }}
                                ({{ number_format($variancePercent, 1) }}%)
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            @if($project->description)
                <!-- Description -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold mb-4">Açıklama</h2>
                    <p class="text-gray-700 whitespace-pre-wrap">{{ $project->description }}</p>
                </div>
            @endif

            @if($project->notes)
                <!-- Notes -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold mb-4">Notlar</h2>
                    <p class="text-gray-700 whitespace-pre-wrap">{{ $project->notes }}</p>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Links -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Hızlı İşlemler</h2>
                <div class="space-y-2">
                    <a href="{{ route('admin.site-reports.index', ['project_id' => $project->id]) }}" class="block w-full bg-teal-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-teal-700 transition text-center">
                        Şantiye Raporları
                    </a>
                    <a href="{{ route('admin.issues.index', ['project_id' => $project->id]) }}" class="block w-full border border-gray-300 text-gray-700 px-4 py-2 rounded-lg font-semibold hover:bg-gray-50 transition text-center">
                        Issues / Punch List
                    </a>
                    @if($project->quote)
                        <a href="{{ route('admin.quotes.show', $project->quote) }}" class="block w-full border border-gray-300 text-gray-700 px-4 py-2 rounded-lg font-semibold hover:bg-gray-50 transition text-center">
                            Teklifi Görüntüle
                        </a>
                    @endif
                    @if($project->contract)
                        <a href="{{ route('admin.contracts.show', $project->contract) }}" class="block w-full border border-gray-300 text-gray-700 px-4 py-2 rounded-lg font-semibold hover:bg-gray-50 transition text-center">
                            Sözleşmeyi Görüntüle
                        </a>
                    @endif
                </div>
            </div>

            <!-- Related Info -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">İlişkili Bilgiler</h2>
                <div class="space-y-3 text-sm">
                    @if($project->lead)
                        <div>
                            <span class="text-gray-500">Lead:</span>
                            <a href="{{ route('admin.leads.show', $project->lead) }}" class="ml-2 text-teal-600 hover:underline">
                                {{ $project->lead->lead_number }}
                            </a>
                        </div>
                    @endif
                    @if($project->quote)
                        <div>
                            <span class="text-gray-500">Teklif:</span>
                            <a href="{{ route('admin.quotes.show', $project->quote) }}" class="ml-2 text-teal-600 hover:underline">
                                {{ $project->quote->quote_number }}
                            </a>
                        </div>
                    @endif
                    @if($project->contract)
                        <div>
                            <span class="text-gray-500">Sözleşme:</span>
                            <a href="{{ route('admin.contracts.show', $project->contract) }}" class="ml-2 text-teal-600 hover:underline">
                                {{ $project->contract->contract_number }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
