@extends('layouts.admin')

@section('title', 'Yeni Tedarikçi Ekle')

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.vendors.index') }}" class="text-gray-500 hover:text-gray-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Yeni Tedarikçi Ekle</h1>
    </div>

    <form method="POST" action="{{ route('admin.vendors.store') }}" class="bg-white rounded-lg shadow p-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Basic Info -->
            <div class="md:col-span-2">
                <h2 class="text-xl font-semibold mb-4">Temel Bilgiler</h2>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tedarikçi Kodu</label>
                <input type="text" name="code" value="{{ old('code') }}" placeholder="Otomatik oluşturulacak"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                @error('code') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Firma Adı *</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                <input type="text" name="category" value="{{ old('category') }}" placeholder="beton, demir, seramik, elektrik vb."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Durum</label>
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                    <span class="ml-2 text-sm text-gray-700">Aktif</span>
                </label>
            </div>

            <!-- Tax Info -->
            <div class="md:col-span-2 mt-4">
                <h2 class="text-xl font-semibold mb-4">Vergi Bilgileri</h2>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Vergi No</label>
                <input type="text" name="tax_number" value="{{ old('tax_number') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Vergi Dairesi</label>
                <input type="text" name="tax_office" value="{{ old('tax_office') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
            </div>

            <!-- Contact Info -->
            <div class="md:col-span-2 mt-4">
                <h2 class="text-xl font-semibold mb-4">İletişim Bilgileri</h2>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">İletişim Kişisi</label>
                <input type="text" name="contact_person" value="{{ old('contact_person') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Telefon</label>
                <input type="text" name="phone" value="{{ old('phone') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">E-posta</label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Web Sitesi</label>
                <input type="url" name="website" value="{{ old('website') }}" placeholder="https://..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                @error('website') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <!-- Address -->
            <div class="md:col-span-2 mt-4">
                <h2 class="text-xl font-semibold mb-4">Adres Bilgileri</h2>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Adres</label>
                <textarea name="address" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">{{ old('address') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">İl</label>
                <input type="text" name="city" value="{{ old('city') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">İlçe</label>
                <input type="text" name="district" value="{{ old('district') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Posta Kodu</label>
                <input type="text" name="postal_code" value="{{ old('postal_code') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
            </div>

            <!-- Notes -->
            <div class="md:col-span-2 mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Notlar</label>
                <textarea name="notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">{{ old('notes') }}</textarea>
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ route('admin.vendors.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                İptal
            </a>
            <button type="submit" class="bg-teal-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-teal-700 transition">
                Kaydet
            </button>
        </div>
    </form>
</div>
@endsection
