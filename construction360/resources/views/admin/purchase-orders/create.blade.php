@extends('layouts.admin')

@section('title', 'Yeni Satınalma Siparişi Oluştur')

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.purchase-orders.index') }}" class="text-gray-500 hover:text-gray-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Yeni Satınalma Siparişi Oluştur</h1>
    </div>

    <form method="POST" action="{{ route('admin.purchase-orders.store') }}" id="po-form" class="bg-white rounded-lg shadow p-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Basic Info -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tedarikçi *</label>
                <select name="vendor_id" id="vendor_id" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    @if($vendor)
                        <option value="{{ $vendor->id }}" selected>{{ $vendor->name }}</option>
                    @else
                        <option value="">Tedarikçi seçiniz</option>
                        @foreach($vendors as $v)
                            <option value="{{ $v->id }}">{{ $v->name }}</option>
                        @endforeach
                    @endif
                </select>
                @error('vendor_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Proje</label>
                <select name="project_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    <option value="">Proje seçiniz (opsiyonel)</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}">{{ $project->project_code }} - {{ $project->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Sipariş Tarihi *</label>
                <input type="date" name="order_date" value="{{ old('order_date', today()->format('Y-m-d')) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Teslim Tarihi</label>
                <input type="date" name="delivery_date" value="{{ old('delivery_date') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Teslimat Adresi</label>
                <textarea name="delivery_address" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">{{ old('delivery_address') }}</textarea>
            </div>
        </div>

        <!-- PO Items -->
        <div class="mb-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">Sipariş Kalemleri</h2>
                <button type="button" onclick="addPOItem()" class="bg-teal-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-teal-700 transition">
                    + Kalem Ekle
                </button>
            </div>

            <div id="po-items" class="space-y-4">
                <!-- Items will be added here dynamically -->
            </div>
        </div>

        <!-- Terms and Notes -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Şartlar ve Koşullar</label>
                <textarea name="terms" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">{{ old('terms') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Notlar</label>
                <textarea name="notes" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">{{ old('notes') }}</textarea>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.purchase-orders.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
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
const materials = @json($materials);

function addPOItem(item = null) {
    const container = document.getElementById('po-items');
    const itemHtml = `
        <div class="po-item bg-gray-50 p-4 rounded-lg border border-gray-200" data-index="${itemIndex}">
            <div class="flex justify-between items-start mb-3">
                <h3 class="font-semibold text-gray-700">Kalem #${itemIndex + 1}</h3>
                <button type="button" onclick="removePOItem(${itemIndex})" class="text-red-600 hover:text-red-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Malzeme</label>
                    <select name="items[${itemIndex}][material_id]" onchange="fillMaterialInfo(${itemIndex}, this.value)"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                        <option value="">Malzeme seçiniz</option>
                        ${materials.map(m => `<option value="${m.id}" ${item?.material_id == m.id ? 'selected' : ''}>${m.code || ''} - ${m.name}</option>`).join('')}
                    </select>
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
                    <input type="number" name="items[${itemIndex}][quantity]" value="${item?.quantity || 1}" step="0.001" min="0.001" required onchange="calculateItemTotal(${itemIndex})"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Birim Fiyat *</label>
                    <input type="number" name="items[${itemIndex}][unit_price]" value="${item?.unit_price || 0}" step="0.01" min="0" required onchange="calculateItemTotal(${itemIndex})"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                </div>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', itemHtml);
    itemIndex++;
}

function removePOItem(index) {
    document.querySelector(`.po-item[data-index="${index}"]`).remove();
    updateItemNumbers();
}

function updateItemNumbers() {
    const items = document.querySelectorAll('.po-item');
    items.forEach((item, index) => {
        item.querySelector('h3').textContent = `Kalem #${index + 1}`;
        item.setAttribute('data-index', index);
    });
}

function fillMaterialInfo(index, materialId) {
    const material = materials.find(m => m.id == materialId);
    if (material) {
        const itemElement = document.querySelector(`.po-item[data-index="${index}"]`);
        if (itemElement) {
            itemElement.querySelector(`input[name="items[${index}][description]"]`).value = material.name;
            if (material.unit) {
                itemElement.querySelector(`input[name="items[${index}][unit]"]`).value = material.unit;
            }
            if (material.last_purchase_price) {
                itemElement.querySelector(`input[name="items[${index}][unit_price]"]`).value = material.last_purchase_price;
            }
        }
    }
}

function calculateItemTotal(index) {
    // Calculate total for this item if needed
}

// Add first item on load
document.addEventListener('DOMContentLoaded', function() {
    addPOItem();
});
</script>
@endpush
@endsection
