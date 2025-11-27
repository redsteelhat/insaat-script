@extends('layouts.admin')

@section('title', 'Yeni Proje Oluştur')

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.projects.index') }}" class="text-gray-500 hover:text-gray-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Yeni Proje Oluştur</h1>
    </div>

    <form method="POST" action="{{ route('admin.projects.store') }}" class="bg-white rounded-lg shadow p-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Basic Info -->
            <div class="md:col-span-2">
                <h2 class="text-xl font-semibold mb-4">Temel Bilgiler</h2>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Proje Adı *</label>
                <input type="text" name="name" value="{{ old('name', $quote ? $quote->title : '') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Proje Türü *</label>
                <select name="project_type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    <option value="konut" {{ old('project_type') == 'konut' ? 'selected' : '' }}>Konut</option>
                    <option value="ticari" {{ old('project_type') == 'ticari' ? 'selected' : '' }}>Ticari</option>
                    <option value="endustriyel" {{ old('project_type') == 'endustriyel' ? 'selected' : '' }}>Endüstriyel</option>
                    <option value="tadilat" {{ old('project_type') == 'tadilat' ? 'selected' : '' }}>Tadilat</option>
                    <option value="diger" {{ old('project_type') == 'diger' ? 'selected' : '' }}>Diğer</option>
                </select>
            </div>

            <!-- Quote Selection -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Teklif</label>
                <select name="quote_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    <option value="">Teklif seçiniz (opsiyonel)</option>
                    @foreach($quotes as $q)
                        <option value="{{ $q->id }}" {{ ($quote && $quote->id == $q->id) ? 'selected' : '' }}>
                            {{ $q->quote_number }} - {{ $q->client_name }} ({{ number_format($q->total_amount, 2) }} {{ $q->currency }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Müşteri</label>
                <select name="client_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    <option value="">Müşteri seçiniz</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Location -->
            <div class="md:col-span-2">
                <h2 class="text-xl font-semibold mb-4 mt-4">Lokasyon Bilgileri</h2>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Şantiye</label>
                <select name="site_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    <option value="">Şantiye seçiniz</option>
                    @foreach($sites as $site)
                        <option value="{{ $site->id }}">{{ $site->name }} - {{ $site->city }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">İl</label>
                <input type="text" name="location_city" value="{{ old('location_city') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">İlçe</label>
                <input type="text" name="location_district" value="{{ old('location_district') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Adres</label>
                <textarea name="location_address" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">{{ old('location_address') }}</textarea>
            </div>

            <!-- Project Details -->
            <div class="md:col-span-2">
                <h2 class="text-xl font-semibold mb-4 mt-4">Proje Detayları</h2>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Alan (m²)</label>
                <input type="number" name="area_m2" value="{{ old('area_m2') }}" step="0.01" min="0"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Başlangıç Tarihi</label>
                <input type="date" name="start_date" value="{{ old('start_date') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Planlanan Bitiş Tarihi</label>
                <input type="date" name="planned_end_date" value="{{ old('planned_end_date') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
            </div>

            <!-- Financial -->
            <div class="md:col-span-2">
                <h2 class="text-xl font-semibold mb-4 mt-4">Finansal Bilgiler</h2>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Sözleşme Bedeli</label>
                <input type="number" name="contract_amount" value="{{ old('contract_amount', $quote ? $quote->total_amount : '') }}" step="0.01" min="0"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Bütçe</label>
                <input type="number" name="budget_amount" value="{{ old('budget_amount') }}" step="0.01" min="0"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Açıklama</label>
                <textarea name="description" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">{{ old('description') }}</textarea>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Notlar</label>
                <textarea name="notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">{{ old('notes') }}</textarea>
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ route('admin.projects.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                İptal
            </a>
            <button type="submit" class="bg-teal-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-teal-700 transition">
                Kaydet
            </button>
        </div>
    </form>
</div>
@endsection
