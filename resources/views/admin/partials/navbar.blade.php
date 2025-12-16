{{-- resources/views/admin/partials/navbar.blade.php --}}
<nav
    class="bg-gradient-to-r from-[#a5d6a7] to-[#c8e6c9]
           px-[30px] py-[15px]
           flex items-center justify-between
           shadow-[0_2px_10px_rgba(0,0,0,0.10)]
           sticky top-0 z-[1000]"
>
    <div class="text-[20px] font-black tracking-[0.5px]">
        SNSU LIBRARY E-REQUEST
    </div>

    {{-- Admin dropdown --}}
    <div class="relative">
        <button
            @click="adminMenuOpen = !adminMenuOpen"
            class="flex items-center gap-[8px]
                   px-[16px] py-[8px]
                   rounded-[8px]
                   text-[16px] font-semibold
                   text-[#333]
                   bg-transparent
                   hover:bg-white/30
                   transition-colors"
        >
            <span>{{ auth()->user()->name ?? 'Admin Name' }}</span>
            <span class="text-[12px]">▼</span>
        </button>

        <div
            x-show="adminMenuOpen"
            x-cloak
            @click.away="adminMenuOpen = false"
            x-transition
            class="absolute right-0 mt-[10px]
                   bg-white rounded-[10px]
                   shadow-[0_5px_20px_rgba(0,0,0,0.15)]
                   min-w-[180px]
                   overflow-hidden z-[1001]"
        >
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    type="submit"
                    class="w-full flex items-center gap-[10px]
                           px-[20px] py-[12px]
                           text-[#333] font-medium text-[14px]
                           hover:bg-[#f5f5f5] transition-colors"
                >
                    <span class="text-[16px]">🚪</span>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>
</nav>
