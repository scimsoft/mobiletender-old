<header class="sticky top-0 z-40 flex h-14 shrink-0 items-center justify-between border-b border-slate-200 bg-white px-4 lg:pl-6">
    <div class="flex items-center gap-3">
        <button type="button" class="rounded-lg p-2 text-slate-600 hover:bg-slate-100 lg:hidden" @click="sidebarOpen = !sidebarOpen" aria-label="Menu">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <a href="{{ route('admin') }}" class="text-lg font-semibold text-slate-900">{{ config('app.name') }}</a>
    </div>
    <div class="flex items-center gap-3">
        @include('partials.shared.language-dropdown')
        @auth
            <div class="relative" x-data="{ open: false }">
                <button type="button" @click="open = !open" @click.outside="open = false"
                        class="flex max-w-[12rem] items-center gap-2 truncate rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm text-slate-700 hover:bg-slate-50">
                    <span class="truncate">{{ Auth::user()->name }}</span>
                    <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" x-transition
                     class="absolute right-0 z-50 mt-1 w-48 rounded-lg border border-slate-200 bg-white py-1 shadow-lg"
                     style="display: none;">
                    <a href="{{ route('admin') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">{{ __('Home') }}</a>
                    <a href="{{ route('order') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">{{ __('Pedidos') }}</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 text-left text-sm text-slate-700 hover:bg-slate-50">{{ __('Logout') }}</button>
                    </form>
                </div>
            </div>
        @endauth
    </div>
</header>
