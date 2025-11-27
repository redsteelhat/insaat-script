<footer class="bg-gray-800 text-white mt-12">
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
                <h3 class="text-lg font-semibold mb-4">{{ config('app.name', 'Construction360') }}</h3>
                <p class="text-gray-400 text-sm">İnşaat ve mimarlık alanında profesyonel hizmetler.</p>
            </div>
            <div>
                <h4 class="font-semibold mb-4">Hızlı Bağlantılar</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('public.services') }}" class="text-gray-400 hover:text-white">Hizmetler</a></li>
                    <li><a href="{{ route('public.portfolio') }}" class="text-gray-400 hover:text-white">Projeler</a></li>
                    <li><a href="{{ route('public.blog') }}" class="text-gray-400 hover:text-white">Blog</a></li>
                    <li><a href="{{ route('public.contact') }}" class="text-gray-400 hover:text-white">İletişim</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold mb-4">İletişim</h4>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li>Email: info@example.com</li>
                    <li>Telefon: +90 (XXX) XXX XX XX</li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold mb-4">Sosyal Medya</h4>
                <div class="flex space-x-4">
                    <!-- Social media links -->
                </div>
            </div>
        </div>
        <div class="border-t border-gray-700 mt-8 pt-8 text-center text-sm text-gray-400">
            <p>&copy; {{ date('Y') }} {{ config('app.name', 'Construction360') }}. Tüm hakları saklıdır.</p>
        </div>
    </div>
</footer>
