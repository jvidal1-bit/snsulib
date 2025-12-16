@extends('layouts.app')

@section('title', 'Manage Users - SNSU Library E-Request')

@push('styles')
    <style>[x-cloak]{display:none!important}</style>
@endpush

@section('content')
@php
    /** @var \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Support\Collection $users */
    $users = $users ?? collect();
@endphp

<div
    class="min-h-screen bg-[#e8e8e8]"
    x-data="{
        adminMenuOpen: false,
        userViewOpen: false,
        selectedUser: null,
    }"
>
    {{-- Top Navigation Bar --}}
    @include('admin.partials.navbar')

    <div class="flex min-h-[calc(100vh-60px)]">
        {{-- Sidebar --}}
        @include('admin.partials.sidebar')

        {{-- Main Content --}}
        <main class="flex-1 px-[30px] py-[30px] bg-[#f5f5f5]">



                {{-- Flash messages --}}
            @if (session('success'))
                <div class="mb-4 rounded-[10px] px-4 py-3 bg-green-100 text-green-700 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 rounded-[10px] px-4 py-3 bg-red-100 text-red-700 text-sm">
                    {{ session('error') }}
                </div>
            @endif
            {{-- Search Bar --}}
            <div class="mb-[30px]">
    <div
        class="max-w-[800px]
               bg-white
               rounded-full
               px-[20px] py-[5px]
               shadow-[0_3px_10px_rgba(0,0,0,0.10)]
               flex flex-col md:flex-row md:items-center gap-3"
    >
        <form
            method="GET"
            action="{{ route('admin.users.index') }}"
            class="flex flex-col md:flex-row items-center w-full gap-3"
        >
            {{-- Search input --}}
            <div class="flex items-center flex-1">
                <button
                    type="submit"
                    class="bg-transparent border-0
                           text-[20px] mr-[10px] cursor-pointer"
                >
                    🔍
                </button>

                <input
                    type="text"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Search users..."
                    class="flex-1 border-none outline-none
                           py-[10px] text-[16px]
                           bg-transparent"
                />
            </div>

            {{-- Role filter --}}
            <div class="flex items-center gap-2">
                <label class="text-[14px] text-gray-700 hidden md:block">
                    Role:
                </label>
                <select
                    name="role"
                    class="px-3 py-2 text-[14px]
                           border border-gray-300
                           rounded-full outline-none
                           bg-white"
                    onchange="this.form.submit()"
                >
                    <option value="">All roles</option>
                    <option value="admin"   {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="student" {{ request('role') === 'student' ? 'selected' : '' }}>Student</option>
                </select>
            </div>
        </form>
    </div>
</div>


            {{-- Users Table --}}
            <div class="bg-white rounded-[20px] p-[30px] shadow-[0_5px_20px_rgba(0,0,0,0.10)]">
                <h2 class="text-[24px] mb-[20px] text-[#333]">
                    Admin User
                </h2>

                <div class="overflow-x-auto">
                    <table class="w-full border-collapse text-[14px]">
                        <thead>
                            <tr class="bg-[#e0e0e0]">
                                <th class="px-[15px] py-[15px] text-left font-semibold text-[14px]">ID</th>
                                <th class="px-[15px] py-[15px] text-left font-semibold text-[14px]">Name</th>
                                <th class="px-[15px] py-[15px] text-left font-semibold text-[14px]">Email</th>
                                <th class="px-[15px] py-[15px] text-left font-semibold text-[14px]">Role</th>
                                <th class="px-[15px] py-[15px] text-left font-semibold text-[14px]">Course</th>
                                <th class="px-[15px] py-[15px] text-left font-semibold text-[14px]">Year Level</th>
                                <th class="px-[15px] py-[15px] text-left font-semibold text-[14px]">Status</th>
                                <th class="px-[15px] py-[15px] text-left font-semibold text-[14px]">Action</th>
                            </tr>
                        </thead>

                                    <tbody>
                                @forelse ($users as $user)
                                    <tr
                                        class="border-b border-[#e0e0e0]
                                            transition-colors duration-200
                                            hover:bg-[#f5f5f5]"
                                    >
                                        <td class="px-[15px] py-[15px]">
                                            {{ $user->id }}
                                        </td>

                                        {{-- Name – if student and profile exists, use first + last; fall back to user->name --}}
                                        <td class="px-[15px] py-[15px]">
                                            @if($user->role === 'student' && $user->profile)
                                                {{ $user->profile->first_name }} {{ $user->profile->last_name }}
                                                <div class="text-xs text-gray-500">
                                                    (Username: {{ $user->name }})
                                                </div>
                                            @else
                                                {{ $user->name }}
                                            @endif
                                        </td>

                                        <td class="px-[15px] py-[15px]">
                                            {{ $user->email }}
                                        </td>

                                        <td class="px-[15px] py-[15px] capitalize">
                                            {{ $user->role ?? 'admin' }}
                                        </td>

                                        {{-- Course & year from student_profiles --}}
                                        <td class="px-[15px] py-[15px]">
                                            {{ $user->profile->course ?? '-' }}
                                        </td>
                                        <td class="px-[15px] py-[15px]">
                                            {{ $user->profile->year_level ?? '-' }}
                                        </td>

                                        <td class="px-[15px] py-[15px] capitalize">
                                            {{ $user->status ?? 'active' }}
                                        </td>

                                        <td class="px-[15px] py-[15px] flex items-center gap-[8px]">
                                            {{-- View user with profile info --}}
                                            <button
                                                type="button"
                                                @click="userViewOpen = true; selectedUser = {
                                                    id: {{ $user->id }},
                                                    name: @js($user->name),
                                                    email: @js($user->email),
                                                    role: @js($user->role ?? 'admin'),
                                                    status: @js($user->status ?? 'active'),

                                                    // Profile fields (may be null)
                                                    student_id: @js(optional($user->profile)->student_id),
                                                    first_name: @js(optional($user->profile)->first_name),
                                                    last_name: @js(optional($user->profile)->last_name),
                                                    phone: @js(optional($user->profile)->phone),
                                                    course: @js(optional($user->profile)->course),
                                                    year_level: @js(optional($user->profile)->year_level),
                                                    address: @js(optional($user->profile)->address),
                                                }"
                                                class="bg-transparent border-0
                                                    text-[20px]
                                                    w-8 h-8
                                                    rounded-full
                                                    cursor-pointer
                                                    transition-transform
                                                    hover:scale-110"
                                                title="View User"
                                            >
                                                👁️
                                            </button>

                                            {{-- Edit --}}
                                            <a
                                                href="{{ route('admin.users.edit', $user) }}"
                                                class="inline-flex items-center justify-center
                                                    w-8 h-8 text-[16px]
                                                    rounded-full
                                                    bg-[#2e7d32] text-white
                                                    hover:bg-[#1b5e20]
                                                    transition-transform hover:scale-110"
                                                title="Edit User"
                                            >
                                                ✏️
                                            </a>
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-[15px] py-[15px] text-center text-gray-500">
                                            No users found.
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>

                    </table>
                </div>

                {{-- Pagination (UI-ready, backend later) --}}
                @if ($users instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    <div class="mt-[20px]">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>
        </main>
    </div>

    {{-- Footer Logos --}}
    @include('admin.partials.footer-logos')

    {{-- User View Modal (UI only, placeholder details for now) --}}
    {{-- User View Modal --}}
<div
    x-show="userViewOpen"
    x-cloak
    @click.self="userViewOpen = false"
    class="fixed inset-0
           bg-black/50
           z-[2000]
           flex items-center justify-center
           p-[20px]
           overflow-y-auto"
>
    <div
        @click.stop
        class="bg-white rounded-[20px] p-[40px]
               w-full max-w-[600px]
               max-h-[90vh]
               overflow-y-auto
               relative
               shadow-[0_10px_30px_rgba(0,0,0,0.25)]"
    >
        <button
            type="button"
            @click="userViewOpen = false"
            class="absolute top-[20px] right-[20px]
                   bg-transparent border-0
                   text-[32px] text-[#666]
                   w-[40px] h-[40px]
                   flex items-center justify-center
                   rounded-full
                   cursor-pointer
                   transition-all
                   hover:bg-[#f5f5f5] hover:text-[#333]"
        >
            ×
        </button>

        <template x-if="selectedUser">
            <div>
                {{-- Avatar + basic info --}}
                <div
                    class="text-center p-[30px]
                           bg-[#f9f9f9]
                           rounded-[15px]
                           mb-[30px]"
                >
                    <div
                        class="w-[100px] h-[100px]
                               rounded-full
                               mx-auto mb-[20px]
                               flex items-center justify-center
                               bg-gradient-to-br from-[#6b7c93] to-[#5a6a7f]"
                    >
                        <span class="text-[50px] text-white">
                            👤
                        </span>
                    </div>

                    <h3
                        class="text-[22px] font-bold mb-[5px] text-[#333]"
                        x-text="(selectedUser.first_name || selectedUser.last_name)
                                ? (selectedUser.first_name + ' ' + selectedUser.last_name)
                                : selectedUser.name"
                    ></h3>

                    <p class="text-[14px] text-[#666] mb-[3px]">
                        ID: <span x-text="selectedUser.id"></span>
                    </p>

                    <p class="text-[14px] text-[#666]">
                        <span class="capitalize" x-text="selectedUser.role"></span>
                        &nbsp;•&nbsp;
                        <span class="capitalize" x-text="selectedUser.status"></span>
                    </p>
                </div>

                {{-- Detailed info --}}
                <div class="mb-[25px]">
                    <h3 class="text-[18px] font-bold mb-[15px] text-[#333]">
                        Account & Profile Information
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-[15px]">
                        <div class="flex flex-col gap-[5px]">
                            <strong class="text-[14px] text-[#333]">Email:</strong>
                            <span class="text-[14px] text-[#666]" x-text="selectedUser.email"></span>
                        </div>

                        <div class="flex flex-col gap-[5px]">
                            <strong class="text-[14px] text-[#333]">Student ID:</strong>
                            <span class="text-[14px] text-[#666]" x-text="selectedUser.student_id || '-'"></span>
                        </div>

                        <div class="flex flex-col gap-[5px]">
                            <strong class="text-[14px] text-[#333]">Course:</strong>
                            <span class="text-[14px] text-[#666]" x-text="selectedUser.course || '-'"></span>
                        </div>

                        <div class="flex flex-col gap-[5px]">
                            <strong class="text-[14px] text-[#333]">Year Level:</strong>
                            <span class="text-[14px] text-[#666]" x-text="selectedUser.year_level || '-'"></span>
                        </div>

                        <div class="flex flex-col gap-[5px]">
                            <strong class="text-[14px] text-[#333]">Phone:</strong>
                            <span class="text-[14px] text-[#666]" x-text="selectedUser.phone || '-'"></span>
                        </div>

                        <div class="flex flex-col gap-[5px] md:col-span-2">
                            <strong class="text-[14px] text-[#333]">Address:</strong>
                            <span class="text-[14px] text-[#666]" x-text="selectedUser.address || '-'"></span>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex gap-[12px] mt-[25px]">
                    <button
                        type="button"
                        @click="userViewOpen = false"
                        class="flex-1 px-[20px] py-[14px]
                               rounded-[8px] border-0
                               text-[15px] font-semibold
                               bg-[#2e7d32] text-white
                               cursor-pointer
                               transition-colors
                               hover:bg-[#1b5e20]"
                    >
                        Close
                    </button>
                </div>
            </div>
        </template>
    </div>
</div>

</div>
@endsection
