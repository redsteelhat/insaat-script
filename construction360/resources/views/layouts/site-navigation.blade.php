<nav class="bg-white shadow-sm border-b border-gray-200">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <a href="{{ route('site.dashboard') }}" class="text-lg font-bold text-gray-900">
                    Saha Paneli
                </a>
            </div>
            <div class="flex items-center">
                <span class="text-sm text-gray-600">{{ Auth::user()->name }}</span>
            </div>
        </div>
    </div>
</nav>
