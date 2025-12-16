{{-- resources/views/admin/partials/sidebar.blade.php --}}
<aside
    class="w-[200px]
           bg-gradient-to-b from-[#4caf50] to-[#388e3c]
           py-[20px]
           shadow-[2px_0_10px_rgba(0,0,0,0.10)]"
>
    <nav class="flex flex-col gap-[8px] px-[15px] text-[14px] font-semibold">

        {{-- Dashboard --}}
        <a
            href="{{ route('admin.dashboard') }}"
            class="px-[20px] py-[12px] rounded-[10px] transition-all
                {{ request()->routeIs('admin.dashboard')
                    ? 'bg-white text-[#2e7d32] shadow-[0_3px_10px_rgba(0,0,0,0.20)]'
                    : 'bg-white/30 text-[#1b5e20] hover:bg-white/50 hover:translate-x-[5px]' }}"
        >
            Dashboard
        </a>

        {{-- Requests --}}
        <a
            href="{{ route('admin.requests.index') }}"
            class="px-[20px] py-[12px] rounded-[10px] transition-all
                {{ request()->routeIs('admin.requests.*')
                    ? 'bg-white text-[#2e7d32] shadow-[0_3px_10px_rgba(0,0,0,0.20)]'
                    : 'bg-white/30 text-[#1b5e20] hover:bg-white/50 hover:translate-x-[5px]' }}"
        >
            Requests
        </a>

        {{-- Books --}}
        <a
            href="{{ route('admin.books.index') }}"
            class="px-[20px] py-[12px] rounded-[10px] transition-all
                {{ request()->routeIs('admin.books.*')
                    ? 'bg-white text-[#2e7d32] shadow-[0_3px_10px_rgba(0,0,0,0.20)]'
                    : 'bg-white/30 text-[#1b5e20] hover:bg-white/50 hover:translate-x-[5px]' }}"
        >
            Books
        </a>

        {{-- Users --}}
        <a
            href="{{ route('admin.users.index') }}"
            class="px-[20px] py-[12px] rounded-[10px] transition-all
                {{ request()->routeIs('admin.users.*')
                    ? 'bg-white text-[#2e7d32] shadow-[0_3px_10px_rgba(0,0,0,0.20)]'
                    : 'bg-white/30 text-[#1b5e20] hover:bg-white/50 hover:translate-x-[5px]' }}"
        >
            Users
        </a>

        {{-- Settings --}}
        <a
            href="{{ route('admin.settings.index') }}"
            class="px-[20px] py-[12px] rounded-[10px] transition-all
                {{ request()->routeIs('admin.settings.*')
                    ? 'bg-white text-[#2e7d32] shadow-[0_3px_10px_rgba(0,0,0,0.20)]'
                    : 'bg-white/30 text-[#1b5e20] hover:bg-white/50 hover:translate-x-[5px]' }}"
        >
            Settings
        </a>

    </nav>
</aside>
