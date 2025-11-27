@extends('layouts.public')

@section('title', 'Teklif Al')
@section('description', 'Ücretsiz keşif ve teklif için form doldurun')

@section('content')
<section class="py-20 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-xl p-8 md:p-12">
            <div class="text-center mb-8">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Ücretsiz Teklif Alın</h1>
                <p class="text-lg text-gray-600">Projeniz için detaylı keşif ve teklif almak için formu doldurun. Size en kısa sürede dönüş yapacağız.</p>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('public.quote-request.store') }}" class="space-y-6">
                @csrf

                <!-- Kişisel Bilgiler -->
                <div class="border-b border-gray-200 pb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Kişisel Bilgiler</h2>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Ad Soyad / Firma <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Telefon <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" name="phone" value="{{ old('phone') }}" required
                                placeholder="0532 123 45 67"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">E-posta</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Firma Adı</label>
                            <input type="text" name="company" value="{{ old('company') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                        </div>
                    </div>
                </div>

                <!-- Proje Bilgileri -->
                <div class="border-b border-gray-200 pb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Proje Bilgileri</h2>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Proje Türü</label>
                            <select name="project_type" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                                <option value="">Seçiniz</option>
                                <option value="konut" {{ old('project_type') == 'konut' ? 'selected' : '' }}>Konut</option>
                                <option value="ticari" {{ old('project_type') == 'ticari' ? 'selected' : '' }}>Ticari</option>
                                <option value="endustriyel" {{ old('project_type') == 'endustriyel' ? 'selected' : '' }}>Endüstriyel</option>
                                <option value="tadilat" {{ old('project_type') == 'tadilat' ? 'selected' : '' }}>Tadilat</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">İl</label>
                            <input type="text" name="location_city" value="{{ old('location_city') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">İlçe</label>
                            <input type="text" name="location_district" value="{{ old('location_district') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Yaklaşık m²</label>
                            <input type="number" name="area_m2" value="{{ old('area_m2') }}" min="0" step="0.01"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Oda Sayısı</label>
                            <input type="number" name="room_count" value="{{ old('room_count') }}" min="0"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Mevcut Durum</label>
                            <select name="current_status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                                <option value="">Seçiniz</option>
                                <option value="arsa_var">Arsa Var</option>
                                <option value="proje_var">Proje Var</option>
                                <option value="kaba_var">Kaba İnşaat Var</option>
                                <option value="tadilat">Tadilat</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Hizmet ve Mesaj -->
                <div class="border-b border-gray-200 pb-6">
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">İstenen Hizmetler</label>
                        <div class="grid md:grid-cols-2 gap-3">
                            <label class="flex items-center">
                                <input type="checkbox" name="requested_services[]" value="mimari_proje" class="mr-2">
                                <span>Mimari Proje</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="requested_services[]" value="ic_mimari" class="mr-2">
                                <span>İç Mimari</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="requested_services[]" value="tahhut" class="mr-2">
                                <span>Taahhüt</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="requested_services[]" value="anahtar_teslim" class="mr-2">
                                <span>Anahtar Teslim</span>
                            </label>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Mesaj / İhtiyaç Detayı</label>
                        <textarea name="message" rows="4" value="{{ old('message') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">{{ old('message') }}</textarea>
                    </div>
                </div>

                <!-- Güvenlik ve KVKK -->
                <div class="pb-6">
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Güvenlik Sorusu <span class="text-red-500">*</span>
                        </label>
                        @php
                            $num1 = rand(1, 10);
                            $num2 = rand(1, 10);
                            session(['captcha_number1' => $num1, 'captcha_number2' => $num2]);
                        @endphp
                        <div class="flex items-center gap-4">
                            <span class="text-lg font-semibold">{{ $num1 }} + {{ $num2 }} = ?</span>
                            <input type="number" name="captcha_answer" required
                                class="w-32 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                        </div>
                        @error('captcha_answer')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <label class="flex items-start">
                        <input type="checkbox" name="kvkk_consent" value="1" required
                            class="mt-1 mr-2">
                        <span class="text-sm text-gray-700">
                            <a href="#" class="text-teal-600 hover:underline">KVKK Aydınlatma Metni</a>'ni okudum ve kabul ediyorum. <span class="text-red-500">*</span>
                        </span>
                    </label>
                    @error('kvkk_consent')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="flex justify-center">
                    <button type="submit" class="bg-teal-600 text-white px-12 py-4 rounded-lg font-semibold hover:bg-teal-700 transition text-lg">
                        Teklif Talep Et
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
