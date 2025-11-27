@extends('layouts.site')

@section('title', 'Günlük Şantiye Raporu')

@section('content')
<div class="space-y-4 p-4">
    <div class="flex items-center gap-3 mb-4">
        <a href="{{ route('site.dashboard') }}" class="text-gray-500 hover:text-gray-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <h1 class="text-xl font-bold text-gray-900">Günlük Şantiye Raporu</h1>
    </div>

    <div class="bg-white rounded-lg shadow p-4 mb-4">
        <h2 class="font-semibold text-gray-900 mb-1">{{ $project->name }}</h2>
        <p class="text-sm text-gray-500">{{ $project->project_code }}</p>
    </div>

    <form method="POST" action="{{ route('site.daily-report.store', $project->id) }}" enctype="multipart/form-data" class="space-y-4">
        @csrf

        <!-- Date and Weather -->
        <div class="bg-white rounded-lg shadow p-4 space-y-4">
            <h3 class="font-semibold text-gray-900 mb-3">Tarih ve Hava Durumu</h3>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rapor Tarihi *</label>
                    <input type="date" name="report_date" value="{{ old('report_date', today()->format('Y-m-d')) }}" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    @error('report_date') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Hava Durumu</label>
                    <select name="weather" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                        <option value="">Seçiniz</option>
                        <option value="gunesli">Güneşli</option>
                        <option value="bulutlu">Bulutlu</option>
                        <option value="yagmurlu">Yağmurlu</option>
                        <option value="karli">Karlı</option>
                        <option value="ruzgarli">Rüzgarlı</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">İş Başlangıç</label>
                    <input type="time" name="work_start_time" value="{{ old('work_start_time', '08:00') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">İş Bitiş</label>
                    <input type="time" name="work_end_time" value="{{ old('work_end_time', '18:00') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                </div>
            </div>
        </div>

        <!-- Team Information -->
        <div class="bg-white rounded-lg shadow p-4 space-y-4">
            <h3 class="font-semibold text-gray-900 mb-3">Ekip Bilgileri</h3>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ekip Sayısı</label>
                    <input type="number" name="team_count" value="{{ old('team_count', 0) }}" min="0"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Taşeron Sayısı</label>
                    <input type="number" name="subcontractor_count" value="{{ old('subcontractor_count', 0) }}" min="0"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Çalışma Alanları</label>
                <textarea name="work_areas" rows="2" placeholder="Örn: Zemin +1, Çatı, Dış cephe..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">{{ old('work_areas') }}</textarea>
            </div>
        </div>

        <!-- Work Summary -->
        <div class="bg-white rounded-lg shadow p-4 space-y-4">
            <h3 class="font-semibold text-gray-900 mb-3">Günlük Özet</h3>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Yapılan İşler</label>
                <textarea name="summary" rows="4" placeholder="Bugün yapılan işlerin detaylı özeti..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">{{ old('summary') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Risk / Engel / Sorunlar</label>
                <textarea name="obstacles" rows="3" placeholder="Teslimat gecikmesi, malzeme eksikliği vb..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">{{ old('obstacles') }}</textarea>
            </div>
        </div>

        <!-- Photos -->
        <div class="bg-white rounded-lg shadow p-4 space-y-4">
            <h3 class="font-semibold text-gray-900 mb-3">Fotoğraflar</h3>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fotoğraf Yükle</label>
                <input type="file" name="photos[]" multiple accept="image/*"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                <p class="mt-1 text-xs text-gray-500">Birden fazla fotoğraf seçebilirsiniz (max 10MB/foto)</p>
            </div>
        </div>

        <!-- Submit -->
        <div class="flex gap-3">
            <a href="{{ route('site.dashboard') }}" class="flex-1 px-4 py-3 border border-gray-300 rounded-lg text-center font-semibold hover:bg-gray-50">
                İptal
            </a>
            <button type="submit" class="flex-1 bg-teal-600 text-white px-4 py-3 rounded-lg font-semibold hover:bg-teal-700 transition">
                Raporu Gönder
            </button>
        </div>
    </form>
</div>
@endsection
