@extends('layouts.admin')

@section('title', 'Issue Düzenle: ' . $issue->issue_number)

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.issues.show', $issue) }}" class="text-gray-500 hover:text-gray-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Issue Düzenle</h1>
    </div>

    <form method="POST" action="{{ route('admin.issues.update', $issue) }}" enctype="multipart/form-data" class="bg-white rounded-lg shadow p-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Basic Info -->
            <div class="md:col-span-2">
                <h2 class="text-xl font-semibold mb-4">Temel Bilgiler</h2>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Proje</label>
                <select name="project_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500" disabled>
                    <option value="{{ $issue->project_id }}" selected>{{ $issue->project->project_code }} - {{ $issue->project->name }}</option>
                </select>
                <input type="hidden" name="project_id" value="{{ $issue->project_id }}">
                <p class="mt-1 text-xs text-gray-500">Proje değiştirilemez</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kategori *</label>
                <select name="category" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    <option value="kalite" {{ old('category', $issue->category) == 'kalite' ? 'selected' : '' }}>Kalite</option>
                    <option value="is_guvenligi" {{ old('category', $issue->category) == 'is_guvenligi' ? 'selected' : '' }}>İş Güvenliği</option>
                    <option value="tedarik" {{ old('category', $issue->category) == 'tedarik' ? 'selected' : '' }}>Tedarik</option>
                    <option value="tasarim" {{ old('category', $issue->category) == 'tasarim' ? 'selected' : '' }}>Tasarım</option>
                    <option value="musteri" {{ old('category', $issue->category) == 'musteri' ? 'selected' : '' }}>Müşteri</option>
                    <option value="diger" {{ old('category', $issue->category) == 'diger' ? 'selected' : '' }}>Diğer</option>
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Başlık *</label>
                <input type="text" name="title" value="{{ old('title', $issue->title) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Açıklama *</label>
                <textarea name="description" rows="4" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">{{ old('description', $issue->description) }}</textarea>
                @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <!-- Priority and Status -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Öncelik *</label>
                <select name="priority" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    <option value="low" {{ old('priority', $issue->priority) == 'low' ? 'selected' : '' }}>Düşük</option>
                    <option value="medium" {{ old('priority', $issue->priority) == 'medium' ? 'selected' : '' }}>Orta</option>
                    <option value="high" {{ old('priority', $issue->priority) == 'high' ? 'selected' : '' }}>Yüksek</option>
                    <option value="critical" {{ old('priority', $issue->priority) == 'critical' ? 'selected' : '' }}>Kritik</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Durum *</label>
                <select name="status" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    <option value="open" {{ old('status', $issue->status) == 'open' ? 'selected' : '' }}>Açık</option>
                    <option value="in_progress" {{ old('status', $issue->status) == 'in_progress' ? 'selected' : '' }}>İşlemde</option>
                    <option value="resolved" {{ old('status', $issue->status) == 'resolved' ? 'selected' : '' }}>Çözüldü</option>
                    <option value="closed" {{ old('status', $issue->status) == 'closed' ? 'selected' : '' }}>Kapalı</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Atanan</label>
                <select name="assigned_to" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    <option value="">Atanmadı</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('assigned_to', $issue->assigned_to) == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->role }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Son Tarih (SLA)</label>
                <input type="date" name="due_date" value="{{ old('due_date', $issue->due_date?->format('Y-m-d')) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Lokasyon</label>
                <input type="text" name="location" value="{{ old('location', $issue->location) }}" placeholder="Örn: Zemin +1, Oda 101"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
            </div>

            <!-- Resolution -->
            @if(in_array($issue->status, ['resolved', 'closed']))
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Çözüm</label>
                    <textarea name="resolution" rows="4"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">{{ old('resolution', $issue->resolution) }}</textarea>
                </div>
            @endif

            <!-- Photos -->
            @if($issue->photos && count($issue->photos) > 0)
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mevcut Fotoğraflar</label>
                    <div class="grid grid-cols-4 gap-4 mb-4">
                        @foreach($issue->photos as $photoPath)
                            <div class="relative">
                                <img src="{{ Storage::url($photoPath) }}" alt="Photo" class="w-full h-32 object-cover rounded-lg">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Yeni Fotoğraflar Ekle</label>
                <input type="file" name="photos[]" multiple accept="image/*"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                <p class="mt-1 text-xs text-gray-500">Birden fazla fotoğraf seçebilirsiniz (max 10MB/foto)</p>
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ route('admin.issues.show', $issue) }}" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                İptal
            </a>
            <button type="submit" class="bg-teal-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-teal-700 transition">
                Güncelle
            </button>
        </div>
    </form>
</div>
@endsection
