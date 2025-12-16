{{-- resources/views/student/partials/navbar.blade.php --}}
<nav class="w-full bg-[#c8e6c9] shadow-md">
    <div class="max-w-6xl mx-auto px-6">
        <div class="flex items-center justify-between h-16">
            {{-- Brand --}}
            <div class="nav-brand font-semibold text-lg tracking-wide text-[#1b5e20]">
                SNSU LIBRARY E-REQUEST
            </div>

            {{-- Links + dropdown --}}
            <div class="nav-links flex items-center gap-6 text-sm font-semibold text-gray-800"
                 x-data="{ userMenuOpen: false }">

                {{-- Home --}}
                <a href="{{ route('student.home') }}"
                   class="nav-link px-2 py-1 border-b-2 {{ request()->routeIs('student.home') ? 'border-[#1b5e20] text-[#1b5e20]' : 'border-transparent hover:border-[#81c784]' }}">
                    Home
                </a>

                {{-- Catalog --}}
                <a href="{{ route('student.catalog') }}"
                   class="nav-link px-2 py-1 border-b-2 {{ request()->routeIs('student.catalog') ? 'border-[#1b5e20] text-[#1b5e20]' : 'border-transparent hover:border-[#81c784]' }}">
                    Catalog
                </a>

                {{-- My Request --}}
                <a href="{{ route('student.requests.index') }}"
                   class="nav-link px-2 py-1 border-b-2 {{ request()->routeIs('student.requests.*') ? 'border-[#1b5e20] text-[#1b5e20]' : 'border-transparent hover:border-[#81c784]' }}">
                    My Request
                </a>

                {{-- Dropdown --}}
                <div class="dropdown relative">
                    <button
                        type="button"
                        class="dropdown-toggle flex items-center gap-1 px-3 py-1 rounded-md hover:bg-white/60"
                        @click="userMenuOpen = !userMenuOpen"
                    >
                        <span>{{ auth()->user()->name ?? 'Student Name' }}</span>
                        <span class="arrow text-xs">▼</span>
                    </button>

                    <div
                        class="dropdown-menu absolute right-0 mt-2 w-40 bg-white rounded-md shadow-lg border text-sm z-50"
                        x-show="userMenuOpen"
                        x-transition
                        x-cloak
                        @click.outside="userMenuOpen = false"
                    >
                        <a href="{{ route('student.profile') }}"
                           class="dropdown-item flex items-center px-3 py-2 hover:bg-gray-100">
                            <span class="icon mr-2">👤</span> Profile
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button
                                type="submit"
                                class="w-full text-left dropdown-item flex items-center px-3 py-2 text-red-600 hover:bg-gray-100"
                            >
                                <span class="icon mr-2">🚪</span> Logout
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</nav>
