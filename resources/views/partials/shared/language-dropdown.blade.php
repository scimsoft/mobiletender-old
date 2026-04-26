<div class="relative" x-data="{ open: false }">
    <button type="button" @click="open = !open" @click.outside="open = false"
            class="flex items-center gap-1 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm text-slate-700 hover:bg-slate-50">
        <span>{{ Config::get('languages')[App::getLocale()] }}</span>
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
    </button>
    <div x-show="open" x-transition
         class="absolute right-0 z-50 mt-1 min-w-[10rem] rounded-lg border border-slate-200 bg-white py-1 shadow-lg"
         style="display: none;">
        @foreach (Config::get('languages') as $lang => $language)
            @if ($lang != App::getLocale())
                <a class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50" href="{{ route('lang.switch', $lang) }}">
                    <img src="/img/{{ $lang }}.svg" height="16" class="mr-2 inline" alt=""> {{ $language }}
                </a>
            @endif
        @endforeach
    </div>
</div>
