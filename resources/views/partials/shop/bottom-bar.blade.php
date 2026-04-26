@php
    $hasStickyCta = $__env->hasSection('shop_sticky_cta');
    $hasShopBottom = $__env->hasSection('shop_bottom');
@endphp
@if ($hasStickyCta || $hasShopBottom)
    <div
        class="fixed bottom-0 left-0 right-0 z-30 max-h-[45vh] overflow-y-auto border-t border-slate-200 bg-white/95 p-3 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.08)] backdrop-blur sm:hidden"
    >
        @if ($hasStickyCta)
            @yield('shop_sticky_cta')
        @endif
        @if ($hasShopBottom)
            @yield('shop_bottom')
        @endif
    </div>
@endif
