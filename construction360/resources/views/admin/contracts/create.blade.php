@extends('layouts.admin')

@section('title', 'Yeni Sözleşme Oluştur')

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.contracts.index') }}" class="text-gray-500 hover:text-gray-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Yeni Sözleşme Oluştur</h1>
    </div>

    @if($quote)
        <!-- Quote Info Banner -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <h3 class="font-semibold text-blue-900 mb-2">Teklif Bilgileri</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                <div>
                    <span class="text-blue-700">Teklif No:</span>
                    <span class="ml-2 font-medium">{{ $quote->quote_number }}</span>
                </div>
                <div>
                    <span class="text-blue-700">Müşteri:</span>
                    <span class="ml-2 font-medium">{{ $quote->client_name }}</span>
                </div>
                <div>
                    <span class="text-blue-700">Toplam:</span>
                    <span class="ml-2 font-medium">{{ number_format($quote->total_amount, 2) }} {{ $quote->currency }}</span>
                </div>
                @if($project)
                    <div>
                        <span class="text-blue-700">Proje:</span>
                        <span class="ml-2 font-medium">{{ $project->project_code }}</span>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.contracts.store') }}" class="bg-white rounded-lg shadow p-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Basic Info -->
            <div class="md:col-span-2">
                <h2 class="text-xl font-semibold mb-4">Temel Bilgiler</h2>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Proje *</label>
                <select name="project_id" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    @if($project)
                        <option value="{{ $project->id }}" selected>{{ $project->project_code }} - {{ $project->name }}</option>
                    @else
                        <option value="">Proje seçiniz</option>
                        @foreach($projects as $p)
                            <option value="{{ $p->id }}">{{ $p->project_code }} - {{ $p->name }}</option>
                        @endforeach
                    @endif
                </select>
                @error('project_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Sözleşme Başlığı *</label>
                <input type="text" name="title" value="{{ old('title', $project ? 'Sözleşme - ' . $project->name : '') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <!-- Financial Info -->
            <div class="md:col-span-2 mt-4">
                <h2 class="text-xl font-semibold mb-4">Finansal Bilgiler</h2>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Sözleşme Bedeli *</label>
                <input type="number" name="contract_amount" value="{{ old('contract_amount', $quote ? $quote->total_amount : '') }}" step="0.01" min="0" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                @error('contract_amount') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Para Birimi</label>
                <select name="currency" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    <option value="TRY" {{ old('currency', $quote ? $quote->currency : 'TRY') == 'TRY' ? 'selected' : '' }}>TRY</option>
                    <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>USD</option>
                    <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>EUR</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Avans Tutarı</label>
                <input type="number" name="advance_amount" value="{{ old('advance_amount', 0) }}" step="0.01" min="0"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Teminat Tutarı</label>
                <input type="number" name="retention_amount" value="{{ old('retention_amount', 0) }}" step="0.01" min="0"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
            </div>

            <!-- Dates -->
            <div class="md:col-span-2 mt-4">
                <h2 class="text-xl font-semibold mb-4">Tarih Bilgileri</h2>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Sözleşme Tarihi</label>
                <input type="date" name="contract_date" value="{{ old('contract_date', today()->format('Y-m-d')) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Başlangıç Tarihi</label>
                <input type="date" name="start_date" value="{{ old('start_date', $project ? $project->start_date?->format('Y-m-d') : '') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Bitiş Tarihi</label>
                <input type="date" name="end_date" value="{{ old('end_date', $project ? $project->planned_end_date?->format('Y-m-d') : '') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
            </div>

            <!-- Terms -->
            <div class="md:col-span-2 mt-4">
                <h2 class="text-xl font-semibold mb-4">Şartlar ve Koşullar</h2>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Sözleşme Şartları</label>
                <textarea name="terms" rows="8" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">{{ old('terms', $quote ? $quote->terms : '') }}</textarea>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Notlar</label>
                <textarea name="notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">{{ old('notes') }}</textarea>
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ route('admin.contracts.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                İptal
            </a>
            <button type="submit" class="bg-teal-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-teal-700 transition">
                Kaydet
            </button>
        </div>
    </form>
</div>
@endsection
