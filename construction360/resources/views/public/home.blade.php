@extends('layouts.public')

@section('title', 'Ana Sayfa')
@section('description', 'İnşaat ve mimarlık alanında profesyonel hizmetler')

@section('content')
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-teal-50 to-blue-50 py-20 lg:py-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Left Content -->
                <div class="text-center lg:text-left">
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 mb-6 leading-tight">
                        Hayallerinizi Gerçeğe Dönüştürüyoruz
                    </h1>
                    <p class="text-lg md:text-xl text-gray-600 mb-8 leading-relaxed">
                        Modern mimari tasarımlar ve kaliteli inşaat hizmetleriyle hayalinizdeki projeyi hayata geçiriyoruz. Profesyonel ekibimizle yanınızdayız.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="{{ route('public.quote-request') }}" class="bg-teal-600 text-white px-8 py-4 rounded-lg font-semibold hover:bg-teal-700 transition inline-block text-center">
                            Teklif Al
                        </a>
                        <a href="{{ route('public.portfolio') }}" class="border-2 border-teal-600 text-teal-600 px-8 py-4 rounded-lg font-semibold hover:bg-teal-50 transition inline-block text-center">
                            Projelerimizi İncele
                        </a>
                    </div>
                </div>

                <!-- Right Image -->
                <div class="relative">
                    <div class="relative rounded-2xl overflow-hidden shadow-2xl">
                        <div class="aspect-w-16 aspect-h-12 bg-gradient-to-br from-teal-400 to-blue-500">
                            <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=800');">
                                <div class="absolute inset-0 bg-black bg-opacity-20"></div>
                            </div>
                        </div>
                    </div>
                    <!-- Decorative Element -->
                    <div class="absolute -bottom-6 -left-6 w-32 h-32 bg-teal-600 opacity-20 rounded-full blur-3xl"></div>
                    <div class="absolute -top-6 -right-6 w-32 h-32 bg-blue-600 opacity-20 rounded-full blur-3xl"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Property Search Bar (Quote Request) -->
    <section class="relative -mt-12 z-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-teal-600 rounded-2xl shadow-2xl p-6 md:p-8">
                <div class="grid md:grid-cols-4 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-white text-sm font-medium mb-2">Proje Türü</label>
                        <select class="w-full px-4 py-3 rounded-lg border-0 focus:ring-2 focus:ring-white">
                            <option>Konut</option>
                            <option>Ticari</option>
                            <option>Endüstriyel</option>
                            <option>Tadilat</option>
                        </select>
                    </div>
                    <div class="md:col-span-1">
                        <label class="block text-white text-sm font-medium mb-2">Lokasyon</label>
                        <input type="text" placeholder="İl / İlçe" class="w-full px-4 py-3 rounded-lg border-0 focus:ring-2 focus:ring-white">
                    </div>
                    <div class="md:col-span-1 flex items-end">
                        <a href="{{ route('public.quote-request') }}" class="w-full bg-blue-900 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-800 transition text-center">
                            Teklif Al
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Property Types / Services Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Hizmet Kategorilerimiz</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Geniş hizmet yelpazemizle inşaat ve mimarlık ihtiyaçlarınızın her aşamasında yanınızdayız.
                </p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-4 gap-6">
                @php
                    $services = [
                        ['icon' => '🏢', 'name' => 'Mimari Proje', 'count' => '50+ Proje'],
                        ['icon' => '🏠', 'name' => 'Konut İnşaatı', 'count' => '120+ Proje'],
                        ['icon' => '🏬', 'name' => 'Ticari İnşaat', 'count' => '80+ Proje'],
                        ['icon' => '🏭', 'name' => 'Endüstriyel', 'count' => '35+ Proje'],
                        ['icon' => '🔧', 'name' => 'Tadilat', 'count' => '200+ Proje'],
                        ['icon' => '🏗️', 'name' => 'Anahtar Teslim', 'count' => '90+ Proje'],
                        ['icon' => '🎨', 'name' => 'İç Mimari', 'count' => '150+ Proje'],
                        ['icon' => '🌳', 'name' => 'Peyzaj', 'count' => '60+ Proje'],
                    ];
                @endphp

                @foreach($services as $service)
                    <div class="bg-white border-2 border-gray-100 rounded-xl p-6 text-center hover:border-teal-600 hover:shadow-lg transition-all cursor-pointer group">
                        <div class="text-5xl mb-4 group-hover:scale-110 transition-transform">{{ $service['icon'] }}</div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $service['name'] }}</h3>
                        <p class="text-sm text-teal-600 font-medium">{{ $service['count'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Featured Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Left Image -->
                <div class="relative">
                    <div class="relative rounded-2xl overflow-hidden shadow-xl">
                        <img src="https://images.unsplash.com/photo-1600585152915-d208bec867a1?w=800" alt="Modern Building" class="w-full h-auto">
                    </div>
                    <!-- L-shaped decorative element -->
                    <div class="absolute -bottom-6 -left-6 w-32 h-32 bg-teal-600 rounded-tl-3xl"></div>
                </div>

                <!-- Right Content -->
                <div>
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">
                        #1 İnşaat ve Mimarlık Hizmetleri
                    </h2>
                    <p class="text-lg text-gray-600 mb-6 leading-relaxed">
                        Yılların deneyimi ve uzman ekibimizle, hayalinizdeki projeyi en yüksek kalite standartlarında gerçekleştiriyoruz. Müşteri memnuniyeti odaklı çalışma prensibimizle sektörde öncü konumdayız.
                    </p>
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-700">Modern ve sürdürülebilir tasarım anlayışı</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-700">Kaliteli malzeme ve işçilik garantisi</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-700">Zamanında teslimat ve bütçe kontrolü</span>
                        </li>
                    </ul>
                    <a href="{{ route('public.services') }}" class="inline-block bg-teal-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-teal-700 transition">
                        Daha Fazla Bilgi
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Property Listing / Portfolio Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Öne Çıkan Projelerimiz</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Tamamladığımız başarılı projelerden bir seçki. Her biri kalite ve mükemmelliğin bir göstergesi.
                </p>
            </div>

            <!-- Filter Tabs -->
            <div class="flex justify-center gap-4 mb-8">
                <button class="bg-teal-600 text-white px-6 py-2 rounded-lg font-semibold">Tümü</button>
                <button class="bg-gray-100 text-gray-700 px-6 py-2 rounded-lg font-semibold hover:bg-gray-200">Konut</button>
                <button class="bg-gray-100 text-gray-700 px-6 py-2 rounded-lg font-semibold hover:bg-gray-200">Ticari</button>
                <button class="bg-gray-100 text-gray-700 px-6 py-2 rounded-lg font-semibold hover:bg-gray-200">Endüstriyel</button>
            </div>

            <!-- Projects Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                @for($i = 1; $i <= 6; $i++)
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all group">
                        <div class="relative overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1600585154526-990dced4db0d?w=600" alt="Project {{ $i }}" class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-500">
                            <div class="absolute top-4 left-4">
                                <span class="bg-teal-600 text-white px-4 py-1 rounded-full text-sm font-semibold">Tamamlandı</span>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-gray-500">Modern Villa</span>
                                <span class="text-2xl font-bold text-teal-600">Proje {{ $i }}</span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Lüks Konut Projesi</h3>
                            <p class="text-gray-600 mb-4">İstanbul, Türkiye</p>
                            <div class="flex items-center justify-between text-sm text-gray-500 border-t pt-4">
                                <span>📍 250 m²</span>
                                <span>🛏️ 3+1</span>
                                <span>🏗️ 2024</span>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>

            <div class="text-center">
                <a href="{{ route('public.portfolio') }}" class="inline-block bg-teal-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-teal-700 transition">
                    Tüm Projeleri Görüntüle
                </a>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-teal-600 to-blue-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">Projenizi Birlikte Gerçekleştirelim</h2>
            <p class="text-xl mb-8 text-teal-50 max-w-2xl mx-auto">
                Size özel çözümler ve profesyonel hizmetler için bizimle iletişime geçin. Hayalinizdeki projeyi birlikte hayata geçirelim.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('public.quote-request') }}" class="bg-white text-teal-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition inline-block">
                    Hemen Teklif Alın
                </a>
                <a href="{{ route('public.contact') }}" class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-teal-600 transition inline-block">
                    İletişime Geçin
                </a>
            </div>
        </div>
    </section>
@endsection