<nav class="bg-white shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('public.home') }}" class="text-xl font-bold text-gray-900">
                        {{ config('app.name', 'Construction360') }}
                    </a>
                </div>
                <div class="hidden space-x-8 sm:ml-6 sm:flex sm:items-center">
                    <a href="{{ route('public.services') }}" class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Hizmetler</a>
                    <a href="{{ route('public.portfolio') }}" class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Projeler</a>
                    <a href="{{ route('public.blog') }}" class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Blog</a>
                    <a href="{{ route('public.contact') }}" class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">İletişim</a>
                    <a href="{{ route('public.quote-request') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700">Teklif Al</a>
                </div>
            </div>
        </div>
    </div>
</nav>
