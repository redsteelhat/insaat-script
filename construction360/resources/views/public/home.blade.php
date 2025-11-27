@extends('layouts.public')

@section('title', 'Ana Sayfa')
@section('description', 'İnşaat ve mimarlık alanında profesyonel hizmetler')

@section('content')
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold mb-6">
                    Hayallerinizi Gerçeğe Dönüştürüyoruz
                </h1>
                <p class="text-xl md:text-2xl mb-8 text-blue-100">
                    İnşaat ve mimarlık alanında profesyonel hizmetler
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('public.quote-request') }}" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-blue-50 transition">
                        Teklif Al
                    </a>
                    <a href="{{ route('public.portfolio') }}" class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition">
                        Projelerimiz
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Services Section -->
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-12">Hizmetlerimiz</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center p-6 border rounded-lg hover:shadow-lg transition">
                    <div class="text-4xl mb-4">🏗️</div>
                    <h3 class="text-xl font-semibold mb-2">Mimari Proje</h3>
                    <p class="text-gray-600">Modern ve fonksiyonel mimari tasarımlar</p>
                </div>
                <div class="text-center p-6 border rounded-lg hover:shadow-lg transition">
                    <div class="text-4xl mb-4">🏠</div>
                    <h3 class="text-xl font-semibold mb-2">İnşaat Taahhüt</h3>
                    <p class="text-gray-600">Anahtar teslim inşaat hizmetleri</p>
                </div>
                <div class="text-center p-6 border rounded-lg hover:shadow-lg transition">
                    <div class="text-4xl mb-4">🔧</div>
                    <h3 class="text-xl font-semibold mb-2">Tadilat</h3>
                    <p class="text-gray-600">Kapsamlı tadilat ve yenileme hizmetleri</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Projects Section -->
    <div class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-12">Öne Çıkan Projeler</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @for($i = 1; $i <= 3; $i++)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
                        <div class="h-48 bg-gray-200"></div>
                        <div class="p-6">
                            <h3 class="text-xl font-semibold mb-2">Proje {{ $i }}</h3>
                            <p class="text-gray-600 mb-4">Proje açıklaması buraya gelecek</p>
                            <a href="#" class="text-blue-600 hover:text-blue-800 font-semibold">Detayları Gör →</a>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="py-16 bg-blue-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold mb-4">Projenizi Birlikte Gerçekleştirelim</h2>
            <p class="text-xl mb-8 text-blue-100">Size özel çözümler için bizimle iletişime geçin</p>
            <a href="{{ route('public.quote-request') }}" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-blue-50 transition inline-block">
                Hemen Teklif Alın
            </a>
        </div>
    </div>
@endsection
