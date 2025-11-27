@extends('layouts.admin')

@section('title', 'Lead Düzenle: ' . $lead->lead_number)

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.leads.show', $lead) }}" class="text-gray-500 hover:text-gray-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Lead Düzenle</h1>
    </div>

    <form method="POST" action="{{ route('admin.leads.update', $lead) }}" class="bg-white rounded-lg shadow p-6">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Kişisel Bilgiler -->
            <div class="md:col-span-2">
                <h2 class="text-xl font-semibold mb-4">Kişisel Bilgiler</h2>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Ad Soyad / Firma *</label>
                <input type="text" name="name" value="{{ old('name', $lead->name) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Telefon *</label>
                <input type="tel" name="phone" value="{{ old('phone', $lead->phone) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                @error('phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">E-posta</label>
                <input type="email" name="email" value="{{ old('email', $lead->email) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Firma</label>
                <input type="text" name="company" value="{{ old('company', $lead->company) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
            </div>

            <!-- Proje Bilgileri -->
            <div class="md:col-span-2 mt-4">
                <h2 class="text-xl font-semibold mb-4">Proje Bilgileri</h2>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Proje Türü</label>
                <select name="project_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    <option value="">Seçiniz</option>
                    <option value="konut" {{ old('project_type', $lead->project_type) == 'konut' ? 'selected' : '' }}>Konut</option>
                    <option value="ticari" {{ old('project_type', $lead->project_type) == 'ticari' ? 'selected' : '' }}>Ticari</option>
                    <option value="endustriyel" {{ old('project_type', $lead->project_type) == 'endustriyel' ? 'selected' : '' }}>Endüstriyel</option>
                    <option value="tadilat" {{ old('project_type', $lead->project_type) == 'tadilat' ? 'selected' : '' }}>Tadilat</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Durum *</label>
                <select name="status" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    <option value="new" {{ old('status', $lead->status) == 'new' ? 'selected' : '' }}>Yeni</option>
                    <option value="contacted" {{ old('status', $lead->status) == 'contacted' ? 'selected' : '' }}>İletişime Geçildi</option>
                    <option value="site_visit_planned" {{ old('status', $lead->status) == 'site_visit_planned' ? 'selected' : '' }}>Keşif Planlandı</option>
                    <option value="quoted" {{ old('status', $lead->status) == 'quoted' ? 'selected' : '' }}>Teklif Verildi</option>
                    <option value="won" {{ old('status', $lead->status) == 'won' ? 'selected' : '' }}>Kazanıldı</option>
                    <option value="lost" {{ old('status', $lead->status) == 'lost' ? 'selected' : '' }}>Kaybedildi</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">İl</label>
                <input type="text" name="location_city" value="{{ old('location_city', $lead->location_city) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">İlçe</label>
                <input type="text" name="location_district" value="{{ old('location_district', $lead->location_district) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Atanan Kişi</label>
                <select name="assigned_to" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    <option value="">Atanmadı</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('assigned_to', $lead->assigned_to) == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kaynak</label>
                <select name="source" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    <option value="web" {{ old('source', $lead->source) == 'web' ? 'selected' : '' }}>Web</option>
                    <option value="telefon" {{ old('source', $lead->source) == 'telefon' ? 'selected' : '' }}>Telefon</option>
                    <option value="referral" {{ old('source', $lead->source) == 'referral' ? 'selected' : '' }}>Referans</option>
                    <option value="sosyal_medya" {{ old('source', $lead->source) == 'sosyal_medya' ? 'selected' : '' }}>Sosyal Medya</option>
                    <option value="diger" {{ old('source', $lead->source) == 'diger' ? 'selected' : '' }}>Diğer</option>
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Mesaj / İhtiyaç Detayı</label>
                <textarea name="message" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">{{ old('message', $lead->message) }}</textarea>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">İç Notlar</label>
                <textarea name="notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">{{ old('notes', $lead->notes) }}</textarea>
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ route('admin.leads.show', $lead) }}" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                İptal
            </a>
            <button type="submit" class="bg-teal-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-teal-700 transition">
                Güncelle
            </button>
        </div>
    </form>
</div>
@endsection
