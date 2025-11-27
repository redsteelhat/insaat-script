@extends('layouts.admin')

@section('title', 'Yeni Issue Oluştur')

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.issues.index') }}" class="text-gray-500 hover:text-gray-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Yeni Issue Oluştur</h1>
    </div>

    <form method="POST" action="{{ route('admin.issues.store') }}" enctype="multipart/form-data" class="bg-white rounded-lg shadow p-6">
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
                <label class="block text-sm font-medium text-gray-700 mb-2">Kategori *</label>
                <select name="category" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    <option value="kalite">Kalite</option>
                    <option value="is_guvenligi">İş Güvenliği</option>
                    <option value="tedarik">Tedarik</option>
                    <option value="tasarim">Tasarım</option>
                    <option value="musteri">Müşteri</option>
                    <option value="diger">Diğer</option>
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Başlık *</label>
                <input type="text" name="title" value="{{ old('title') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Açıklama *</label>
                <textarea name="description" rows="4" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">{{ old('description') }}</textarea>
                @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <!-- Priority and Status -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Öncelik *</label>
                <select name="priority" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    <option value="low">Düşük</option>
                    <option value="medium" selected>Orta</option>
                    <option value="high">Yüksek</option>
                    <option value="critical">Kritik</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Atanan</label>
                <select name="assigned_to" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    <option value="">Atanmadı</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->role }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Son Tarih (SLA)</label>
                <input type="date" name="due_date" value="{{ old('due_date') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Lokasyon</label>
                <input type="text" name="location" value="{{ old('location') }}" placeholder="Örn: Zemin +1, Oda 101"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
            </div>

            <!-- Photos -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Fotoğraflar</label>
                <input type="file" name="photos[]" multiple accept="image/*"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                <p class="mt-1 text-xs text-gray-500">Birden fazla fotoğraf seçebilirsiniz (max 10MB/foto)</p>
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ route('admin.issues.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                İptal
            </a>
            <button type="submit" class="bg-teal-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-teal-700 transition">
                Kaydet
            </button>
        </div>
    </form>
</div>
@endsection
