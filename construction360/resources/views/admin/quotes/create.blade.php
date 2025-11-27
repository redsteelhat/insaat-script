@extends('layouts.admin')

@section('title', 'Yeni Teklif Oluştur')

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.quotes.index') }}" class="text-gray-500 hover:text-gray-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Yeni Teklif Oluştur</h1>
    </div>

    <form method="POST" action="{{ route('admin.quotes.store') }}" id="quote-form" class="bg-white rounded-lg shadow p-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Lead Selection -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Lead Seçin</label>
                <select name="lead_id" id="lead_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    <option value="">Lead seçiniz (opsiyonel)</option>
                    @foreach($leads as $l)
                        <option value="{{ $l->id }}" {{ ($lead && $lead->id == $l->id) ? 'selected' : '' }}>
                            {{ $l->lead_number }} - {{ $l->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Title -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Teklif Başlığı *</label>
                <input type="text" name="title" value="{{ old('title', $lead ? 'Teklif - ' . $lead->name : '') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <!-- Client Info -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Müşteri Adı *</label>
                <input type="text" name="client_name" value="{{ old('client_name', $lead ? $lead->name : '') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                @error('client_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Müşteri Telefonu</label>
                <input type="text" name="client_phone" value="{{ old('client_phone', $lead ? $lead->phone : '') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Müşteri E-posta</label>
                <input type="email" name="client_email" value="{{ old('client_email', $lead ? $lead->email : '') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Geçerlilik Tarihi</label>
                <input type="date" name="valid_until" value="{{ old('valid_until') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">İskonto (%)</label>
                <input type="number" name="discount_percentage" value="{{ old('discount_percentage', 0) }}" step="0.01" min="0" max="100"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">KDV (%)</label>
                <input type="number" name="tax_percentage" value="{{ old('tax_percentage', 18) }}" step="0.01" min="0" max="100"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
            </div>
        </div>

        <!-- Quote Items -->
        <div class="mb-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">Teklif Kalemleri (BOQ)</h2>
                <button type="button" onclick="addQuoteItem()" class="bg-teal-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-teal-700 transition">
                    + Kalem Ekle
                </button>
            </div>

            <div id="quote-items" class="space-y-4">
                <!-- Items will be added here dynamically -->
            </div>
        </div>

        <!-- Description and Terms -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Açıklama</label>
                <textarea name="description" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">{{ old('description') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Şartlar ve Koşullar</label>
                <textarea name="terms" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">{{ old('terms') }}</textarea>
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">İç Notlar</label>
            <textarea name="notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">{{ old('notes') }}</textarea>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.quotes.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                İptal
            </a>
            <button type="submit" class="bg-teal-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-teal-700 transition">
                Kaydet
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
let itemIndex = 0;

function addQuoteItem(item = null) {
    const container = document.getElementById('quote-items');
    const itemHtml = `
        <div class="quote-item bg-gray-50 p-4 rounded-lg border border-gray-200" data-index="${itemIndex}">
            <div class="flex justify-between items-start mb-3">
                <h3 class="font-semibold text-gray-700">Kalem #${itemIndex + 1}</h3>
                <button type="button" onclick="removeQuoteItem(${itemIndex})" class="text-red-600 hover:text-red-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-6 gap-3">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Kod</label>
                    <input type="text" name="items[${itemIndex}][code]" value="${item?.code || ''}"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-600 mb-1">Açıklama *</label>
                    <input type="text" name="items[${itemIndex}][description]" value="${item?.description || ''}" required
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Birim</label>
                    <input type="text" name="items[${itemIndex}][unit]" value="${item?.unit || ''}" placeholder="m², adet, kg"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Miktar *</label>
                    <input type="number" name="items[${itemIndex}][quantity]" value="${item?.quantity || 1}" step="0.001" min="0.001" required
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Birim Fiyat *</label>
                    <input type="number" name="items[${itemIndex}][unit_price]" value="${item?.unit_price || 0}" step="0.01" min="0" required
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                </div>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', itemHtml);
    itemIndex++;
}

function removeQuoteItem(index) {
    document.querySelector(`.quote-item[data-index="${index}"]`).remove();
    updateItemNumbers();
}

function updateItemNumbers() {
    const items = document.querySelectorAll('.quote-item');
    items.forEach((item, index) => {
        item.querySelector('h3').textContent = `Kalem #${index + 1}`;
        item.setAttribute('data-index', index);
    });
}

// Add first item on load
document.addEventListener('DOMContentLoaded', function() {
    addQuoteItem();
});
</script>
@endpush
@endsection
