<nav class="bg-white shadow-lg sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('public.home') }}" class="flex items-center space-x-2">
                    <svg class="w-10 h-10 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="text-2xl font-bold text-teal-600">{{ config('app.name', 'Construction360') }}</span>
                </a>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('public.home') }}" class="text-gray-700 hover:text-teal-600 font-medium transition">ANA SAYFA</a>
                <a href="{{ route('public.services') }}" class="text-gray-700 hover:text-teal-600 font-medium transition">HİZMETLER</a>
                <a href="{{ route('public.portfolio') }}" class="text-gray-700 hover:text-teal-600 font-medium transition">PROJELER</a>
                <a href="{{ route('public.blog') }}" class="text-gray-700 hover:text-teal-600 font-medium transition">BLOG</a>
                <a href="{{ route('public.contact') }}" class="text-gray-700 hover:text-teal-600 font-medium transition">İLETİŞİM</a>
                <a href="{{ route('public.quote-request') }}" class="bg-teal-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-teal-700 transition">
                    Teklif Al
                </a>
            </div>

            <!-- Mobile Menu Button -->
            <div class="md:hidden">
                <button type="button" class="text-gray-700 hover:text-teal-600" id="mobile-menu-button">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div class="hidden md:hidden bg-white border-t" id="mobile-menu">
        <div class="px-4 py-4 space-y-3">
            <a href="{{ route('public.home') }}" class="block text-gray-700 hover:text-teal-600 font-medium">Ana Sayfa</a>
            <a href="{{ route('public.services') }}" class="block text-gray-700 hover:text-teal-600 font-medium">Hizmetler</a>
            <a href="{{ route('public.portfolio') }}" class="block text-gray-700 hover:text-teal-600 font-medium">Projeler</a>
            <a href="{{ route('public.blog') }}" class="block text-gray-700 hover:text-teal-600 font-medium">Blog</a>
            <a href="{{ route('public.contact') }}" class="block text-gray-700 hover:text-teal-600 font-medium">İletişim</a>
            <a href="{{ route('public.quote-request') }}" class="block bg-teal-600 text-white px-6 py-2 rounded-lg font-semibold text-center">Teklif Al</a>
        </div>
    </div>
</nav>

<script>
    document.getElementById('mobile-menu-button')?.addEventListener('click', function() {
        document.getElementById('mobile-menu')?.classList.toggle('hidden');
    });
</script>