<nav class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <a href="{{ route('client.dashboard') }}" class="text-lg font-bold text-gray-900">
                    Müşteri Portalı
                </a>
            </div>
            <div class="flex items-center space-x-4">
                <span class="text-sm text-gray-600">{{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-gray-600 hover:text-gray-900">Çıkış</button>
                </form>
            </div>
        </div>
    </div>
</nav>
