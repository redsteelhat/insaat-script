@extends('layouts.admin')

@section('title', 'Teklif Düzenle: ' . $quote->quote_number)

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.quotes.show', $quote) }}" class="text-gray-500 hover:text-gray-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Teklif Düzenle</h1>
    </div>

    <form method="POST" action="{{ route('admin.quotes.update', $quote) }}" id="quote-form" class="bg-white rounded-lg shadow p-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Lead Selection -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Lead</label>
                <select name="lead_id" id="lead_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500" disabled>
                    @if($quote->lead)
                        <option value="{{ $quote->lead->id }}" selected>{{ $quote->lead->lead_number }} - {{ $quote->lead->name }}</option>
                    @else
                        <option value="">Lead bağlı değil</option>
                    @endif
                </select>
                <p class="mt-1 text-xs text-gray-500">Lead bilgisi teklif oluşturulduktan sonra değiştirilemez</p>
            </div>

            <!-- Title -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Teklif Başlığı *</label>
                <input type="text" name="title" value="{{ old('title', $quote->title) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <!-- Client Info -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Müşteri Adı *</label>
                <input type="text" name="client_name" value="{{ old('client_name', $quote->client_name) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                @error('client_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Müşteri Telefonu</label>
                <input type="text" name="client_phone" value="{{ old('client_phone', $quote->client_phone) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Müşteri E-posta</label>
                <input type="email" name="client_email" value="{{ old('client_email', $quote->client_email) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Durum</label>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    <option value="draft" {{ old('status', $quote->status) == 'draft' ? 'selected' : '' }}>Taslak</option>
                    <option value="sent" {{ old('status', $quote->status) == 'sent' ? 'selected' : '' }}>Gönderildi</option>
                    <option value="approved" {{ old('status', $quote->status) == 'approved' ? 'selected' : '' }}>Onaylandı</option>
                    <option value="rejected" {{ old('status', $quote->status) == 'rejected' ? 'selected' : '' }}>Reddedildi</option>
                    <option value="contracted" {{ old('status', $quote->status) == 'contracted' ? 'selected' : '' }}>Sözleşmeye Dönüştü</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Geçerlilik Tarihi</label>
                <input type="date" name="valid_until" value="{{ old('valid_until', $quote->valid_until?->format('Y-m-d')) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">İskonto (%)</label>
                <input type="number" name="discount_percentage" value="{{ old('discount_percentage', $quote->discount_percentage) }}" step="0.01" min="0" max="100"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">KDV (%)</label>
                <input type="number" name="tax_percentage" value="{{ old('tax_percentage', $quote->tax_percentage) }}" step="0.01" min="0" max="100"
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
                @foreach($quote->items as $item)
                    <div class="quote-item bg-gray-50 p-4 rounded-lg border border-gray-200" data-index="{{ $loop->index }}">
                        <div class="flex justify-between items-start mb-3">
                            <h3 class="font-semibold text-gray-700">Kalem #{{ $loop->iteration }}</h3>
                            <button type="button" onclick="removeQuoteItem({{ $loop->index }})" class="text-red-600 hover:text-red-800">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-6 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Kod</label>
                                <input type="text" name="items[{{ $loop->index }}][code]" value="{{ $item->code }}"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-medium text-gray-600 mb-1">Açıklama *</label>
                                <input type="text" name="items[{{ $loop->index }}][description]" value="{{ $item->description }}" required
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Birim</label>
                                <input type="text" name="items[{{ $loop->index }}][unit]" value="{{ $item->unit }}" placeholder="m², adet, kg"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Miktar *</label>
                                <input type="number" name="items[{{ $loop->index }}][quantity]" value="{{ $item->quantity }}" step="0.001" min="0.001" required
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Birim Fiyat *</label>
                                <input type="number" name="items[{{ $loop->index }}][unit_price]" value="{{ $item->unit_price }}" step="0.01" min="0" required
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Description and Terms -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Açıklama</label>
                <textarea name="description" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">{{ old('description', $quote->description) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Şartlar ve Koşullar</label>
                <textarea name="terms" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">{{ old('terms', $quote->terms) }}</textarea>
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">İç Notlar</label>
            <textarea name="notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">{{ old('notes', $quote->notes) }}</textarea>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.quotes.show', $quote) }}" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                İptal
            </a>
            <button type="submit" class="bg-teal-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-teal-700 transition">
                Güncelle
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
let itemIndex = {{ $quote->items->count() }};

function addQuoteItem() {
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
                    <input type="text" name="items[${itemIndex}][code]"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-600 mb-1">Açıklama *</label>
                    <input type="text" name="items[${itemIndex}][description]" required
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Birim</label>
                    <input type="text" name="items[${itemIndex}][unit]" placeholder="m², adet, kg"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Miktar *</label>
                    <input type="number" name="items[${itemIndex}][quantity]" value="1" step="0.001" min="0.001" required
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Birim Fiyat *</label>
                    <input type="number" name="items[${itemIndex}][unit_price]" value="0" step="0.01" min="0" required
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                </div>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', itemHtml);
    itemIndex++;
}

function removeQuoteItem(index) {
    const item = document.querySelector(`.quote-item[data-index="${index}"]`);
    if (item) {
        item.remove();
        updateItemNumbers();
    }
}

function updateItemNumbers() {
    const items = document.querySelectorAll('.quote-item');
    items.forEach((item, index) => {
        item.querySelector('h3').textContent = `Kalem #${index + 1}`;
        const newIndex = index;
        item.setAttribute('data-index', newIndex);
        // Update input names
        item.querySelectorAll('input').forEach(input => {
            const name = input.name;
            if (name) {
                input.name = name.replace(/items\[\d+\]/, `items[${newIndex}]`);
            }
        });
    });
}
</script>
@endpush
@endsection
