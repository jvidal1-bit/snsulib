@extends('layouts.app')

@section('title', 'Settings - SNSU Library E-Request')

@push('styles')
    <style>[x-cloak]{display:none!important}</style>
@endpush

@section('content')
<div
    class="min-h-screen bg-[#e8e8e8]"
    x-data="{
        adminMenuOpen: false,

        // Accordion open/close
        requestSettingsOpen: true,
        libraryInfoOpen: false,

        // Modals
        requestSettingsModalOpen: false,
        libraryInfoModalOpen: false,
        myAccountModalOpen: false
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

            @if ($errors->any())
                <div class="mb-4 rounded-[10px] px-4 py-3 bg-red-100 text-red-700 text-sm">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div
                class="max-w-[600px] mx-auto
                       bg-white rounded-[20px]
                       p-[40px]
                       shadow-[0_5px_20px_rgba(0,0,0,0.10)]"
            >
                {{-- Request Settings --}}
                <div class="mb-[15px]">
                    <button
                        type="button"
                        @click="requestSettingsOpen = !requestSettingsOpen"
                        class="w-full flex items-center gap-[15px]
                               px-[25px] py-[18px]
                               bg-gradient-to-br from-[#c8c8c8] to-[#b0b0b0]
                               rounded-[12px]
                               border-0
                               cursor-pointer
                               text-[16px] font-semibold text-[#333]
                               transition-all
                               hover:bg-gradient-to-br hover:from-[#b8b8b8] hover:to-[#a0a0a0]
                               hover:-translate-y-[2px]
                               hover:shadow-[0_5px_15px_rgba(0,0,0,0.20)]"
                    >
                        <span class="text-[20px]">⚙️</span>
                        <span class="flex-1 text-left">
                            Request Settings
                        </span>
                        <span
                            class="text-[14px] transition-transform"
                            :class="requestSettingsOpen ? 'rotate-180' : ''"
                        >
                            ▼
                        </span>
                    </button>

                    <div
                        x-show="requestSettingsOpen"
                        x-collapse
                        x-cloak
                        class="bg-[#f9f9f9]
                               rounded-b-[12px]
                               -mt-[10px] pt-[20px] pb-[20px] px-[25px]"
                    >
                        <div
                            class="flex items-center gap-[15px]
                                   py-[12px] border-b border-[#e0e0e0]
                                   text-[15px] text-[#555]"
                        >
                            <span class="text-[18px]">📖</span>
                            <span class="flex-1">
                                Maximum chapters per request & expiry
                            </span>
                            <button
                                type="button"
                                @click="requestSettingsModalOpen = true"
                                class="px-[16px] py-[6px]
                                       bg-[#4caf50] text-white
                                       rounded-[6px]
                                       text-[13px] font-semibold
                                       border-0 cursor-pointer
                                       transition-colors
                                       hover:bg-[#45a049]"
                            >
                                Edit
                            </button>
                        </div>

                        <div
                            class="flex items-center gap-[15px]
                                   py-[12px] border-b border-[#e0e0e0]
                                   text-[15px] text-[#555]"
                        >
                            <span class="text-[18px]">📅</span>
                            <span class="flex-1">
                                Request expiry and lifetime rules
                            </span>
                        </div>

                        <div
                            class="flex items-center gap-[15px]
                                   py-[12px]
                                   text-[15px] text-[#555]"
                        >
                            <span class="text-[18px]">👤</span>
                            <span class="flex-1">
                                Email notifications for admins & students
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Library Information --}}
                <div class="mb-[15px]">
                    <button
                        type="button"
                        @click="libraryInfoOpen = !libraryInfoOpen"
                        class="w-full flex items-center gap-[15px]
                               px-[25px] py-[18px]
                               bg-gradient-to-br from-[#c8c8c8] to-[#b0b0b0]
                               rounded-[12px]
                               border-0
                               cursor-pointer
                               text-[16px] font-semibold text-[#333]
                               transition-all
                               hover:bg-gradient-to-br hover:from-[#b8b8b8] hover:to-[#a0a0a0]
                               hover:-translate-y-[2px]
                               hover:shadow-[0_5px_15px_rgba(0,0,0,0.20)]"
                    >
                        <span class="text-[20px]">🏛️</span>
                        <span class="flex-1 text-left">
                            Library Information
                        </span>
                        <span
                            class="text-[14px] transition-transform"
                            :class="libraryInfoOpen ? 'rotate-180' : ''"
                        >
                            ▼
                        </span>
                    </button>

                    <div
                        x-show="libraryInfoOpen"
                        x-collapse
                        x-cloak
                        class="bg-[#f9f9f9]
                               rounded-b-[12px]
                               -mt-[10px] pt-[20px] pb-[20px] px-[25px]"
                    >
                        <div
                            class="flex items-center gap-[15px]
                                   py-[12px] border-b border-[#e0e0e0]
                                   text-[15px] text-[#555]"
                        >
                            <span class="flex-1">
                                Library Name
                            </span>
                            <button
                                type="button"
                                @click="libraryInfoModalOpen = true"
                                class="px-[16px] py-[6px]
                                       bg-[#4caf50] text-white
                                       rounded-[6px]
                                       text-[13px] font-semibold
                                       border-0 cursor-pointer
                                       transition-colors
                                       hover:bg-[#45a049]"
                            >
                                Edit
                            </button>
                        </div>

                        <div
                            class="flex items-center gap-[15px]
                                   py-[12px] border-b border-[#e0e0e0]
                                   text-[15px] text-[#555]"
                        >
                            <span class="flex-1">
                                Operation Hours
                            </span>
                        </div>

                        <div
                            class="flex items-center gap-[15px]
                                   py-[12px] border-b border-[#e0e0e0]
                                   text-[15px] text-[#555]"
                        >
                            <span class="flex-1">
                                Contact & Email
                            </span>
                        </div>

                        <div
                            class="flex items-center gap-[15px]
                                   py-[12px]
                                   text-[15px] text-[#555]"
                        >
                            <span class="flex-1">
                                Library Address
                            </span>
                        </div>
                    </div>
                </div>

                {{-- My Account --}}
                <div class="mb-[15px]">
                    <button
                        type="button"
                        @click="myAccountModalOpen = true"
                        class="w-full flex items-center gap-[15px]
                               px-[25px] py-[18px]
                               bg-gradient-to-br from-[#c8c8c8] to-[#b0b0b0]
                               rounded-[12px]
                               border-0
                               cursor-pointer
                               text-[16px] font-semibold text-[#333]
                               transition-all
                               hover:bg-gradient-to-br hover:from-[#b8b8b8] hover:to-[#a0a0a0]
                               hover:-translate-y-[2px]
                               hover:shadow-[0_5px_15px_rgba(0,0,0,0.20)]"
                    >
                        <span class="text-[20px]">👤</span>
                        <span class="flex-1 text-left">
                            My Account
                        </span>
                        <span class="text-[14px]">
                            ▼
                        </span>
                    </button>
                </div>
            </div>
        </main>
    </div>

    {{-- Footer Logos --}}
    @include('admin.partials.footer-logos')

    {{-- Request Settings Modal --}}
    <div
        x-show="requestSettingsModalOpen"
        x-cloak
        @click.self="requestSettingsModalOpen = false"
        class="fixed inset-0 bg-black/50 z-[2000]
               flex items-center justify-center
               p-[20px] overflow-y-auto"
    >
        <div
            @click.stop
            class="bg-white rounded-[20px] p-[40px]
                   w-full max-w-[700px] max-h-[90vh]
                   overflow-y-auto relative
                   shadow-[0_10px_30px_rgba(0,0,0,0.25)]"
        >
            <button
                type="button"
                @click="requestSettingsModalOpen = false"
                class="absolute top-[20px] right-[20px]
                       bg-transparent border-0
                       text-[32px] text-[#666]
                       w-[40px] h-[40px]
                       flex items-center justify-center
                       rounded-full cursor-pointer
                       transition-all
                       hover:bg-[#f5f5f5] hover:text-[#333]"
            >
                ×
            </button>

            <h2 class="text-[24px] mb-[25px] text-[#333]">
                Request Settings
            </h2>

            <form
                method="POST"
                action="{{ route('admin.settings.update') }}"
                class="flex flex-col gap-[20px]"
            >
                @csrf

                {{-- Request settings fields --}}
                <div class="flex flex-col gap-[8px]">
                    <label
                        for="maxChapters"
                        class="font-semibold text-[15px] text-[#333] flex items-center gap-[8px]"
                    >
                        <span class="text-[18px]">📖</span>
                        Maximum Chapters per Request:
                    </label>
                    @php
                        $maxChapters = old('max_chapters_per_request', $settings->max_chapters_per_request ?? 1);
                    @endphp
                    <select
                        id="maxChapters"
                        name="max_chapters_per_request"
                        class="px-[12px] py-[12px]
                               border-2 border-[#e0e0e0]
                               rounded-[8px]
                               text-[15px]
                               outline-none
                               transition-colors
                               focus:border-[#66bb6a]"
                    >
                        <option value="1"  {{ $maxChapters == 1  ? 'selected' : '' }}>1 Chapter</option>
                        <option value="2"  {{ $maxChapters == 2  ? 'selected' : '' }}>2 Chapters</option>
                        <option value="3"  {{ $maxChapters == 3  ? 'selected' : '' }}>3 Chapters</option>
                        <option value="5"  {{ $maxChapters == 5  ? 'selected' : '' }}>5 Chapters</option>
                        <option value="10" {{ $maxChapters == 10 ? 'selected' : '' }}>10 Chapters</option>
                    </select>
                </div>

                <div class="flex flex-col gap-[8px]">
                    <label
                        for="requestExpiry"
                        class="font-semibold text-[15px] text-[#333] flex items-center gap-[8px]"
                    >
                        <span class="text-[18px]">📅</span>
                        Request Expiry (Days):
                    </label>
                    <input
                        type="number"
                        id="requestExpiry"
                        name="request_expiry_days"
                        min="1"
                        max="30"
                        placeholder="Enter days (1-30)"
                        value="{{ old('request_expiry_days', $settings->request_expiry_days ?? 7) }}"
                        class="px-[12px] py-[12px]
                               border-2 border-[#e0e0e0]
                               rounded-[8px]
                               text-[15px]
                               outline-none
                               transition-colors
                               focus:border-[#66bb6a]"
                    >
                    <small class="text-[13px] text-[#666] italic">
                        Request will expire after this many days
                    </small>
                </div>

                <div class="flex flex-col gap-[8px]">
                    <label class="font-semibold text-[15px] text-[#333] flex items-center gap-[8px]">
                        <span class="text-[18px]">👤</span>
                        Email Notification:
                    </label>

                    <div
                        class="flex flex-col gap-[12px]
                               p-[15px]
                               bg-[#f9f9f9]
                               rounded-[8px]"
                    >
                        <label class="flex items-center gap-[10px] text-[14px] cursor-pointer">
                            <input
                                type="checkbox"
                                id="notifyOnRequest"
                                name="notify_on_request"
                                value="1"
                                {{ old('notify_on_request', $settings->notify_on_request ?? true) ? 'checked' : '' }}
                                class="w-[18px] h-[18px] cursor-pointer accent-[#4caf50]"
                            >
                            <span class="text-[#555]">
                                Notify admin on new request
                            </span>
                        </label>

                        <label class="flex items-center gap-[10px] text-[14px] cursor-pointer">
                            <input
                                type="checkbox"
                                id="notifyOnComplete"
                                name="notify_on_complete"
                                value="1"
                                {{ old('notify_on_complete', $settings->notify_on_complete ?? true) ? 'checked' : '' }}
                                class="w-[18px] h-[18px] cursor-pointer accent-[#4caf50]"
                            >
                            <span class="text-[#555]">
                                Notify student on completion
                            </span>
                        </label>

                        <label class="flex items-center gap-[10px] text-[14px] cursor-pointer">
                            <input
                                type="checkbox"
                                id="notifyOnExpiry"
                                name="notify_on_expiry"
                                value="1"
                                {{ old('notify_on_expiry', $settings->notify_on_expiry ?? false) ? 'checked' : '' }}
                                class="w-[18px] h-[18px] cursor-pointer accent-[#4caf50]"
                            >
                            <span class="text-[#555]">
                                Notify on request expiry
                            </span>
                        </label>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-[12px] mt-[25px]">
                    <button
                        type="button"
                        @click="requestSettingsModalOpen = false"
                        class="flex-1 px-[20px] py-[14px]
                               rounded-[8px] border-0
                               text-[15px] font-semibold
                               bg-[#a5d6a7] text-black
                               cursor-pointer
                               transition-colors
                               hover:bg-[#81c784]"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        class="flex-1 px-[20px] py-[14px]
                               rounded-[8px] border-0
                               text-[15px] font-semibold
                               bg-[#2e7d32] text-white
                               cursor-pointer
                               transition-colors
                               hover:bg-[#1b5e20]"
                    >
                        Save Settings
                    </button>
                </div>
            </form>

        </div>
    </div>

    {{-- Library Information Modal --}}
    <div
        x-show="libraryInfoModalOpen"
        x-cloak
        @click.self="libraryInfoModalOpen = false"
        class="fixed inset-0 bg-black/50 z-[2000]
               flex items-center justify-center
               p-[20px] overflow-y-auto"
    >
        <div
            @click.stop
            class="bg-white rounded-[20px] p-[40px]
                   w-full max-w-[700px] max-h-[90vh]
                   overflow-y-auto relative
                   shadow-[0_10px_30px_rgba(0,0,0,0.25)]"
        >
            <button
                type="button"
                @click="libraryInfoModalOpen = false"
                class="absolute top-[20px] right-[20px]
                       bg-transparent border-0
                       text-[32px] text-[#666]
                       w-[40px] h-[40px]
                       flex items-center justify-center
                       rounded-full cursor-pointer
                       transition-all
                       hover:bg-[#f5f5f5] hover:text-[#333]"
            >
                ×
            </button>

            <h2 class="text-[24px] mb-[25px] text-[#333]">
                Library Information
            </h2>

            <form
                method="POST"
                action="{{ route('admin.settings.update') }}"
                class="flex flex-col gap-[20px]"
            >
                @csrf

                {{-- Library info fields --}}
                <div class="flex flex-col gap-[8px]">
                    <label
                        for="libraryName"
                        class="font-semibold text-[15px] text-[#333] flex items-center gap-[8px]"
                    >
                        <span class="text-[18px]">🏛️</span>
                        Library Name:
                    </label>
                    <input
                        type="text"
                        id="libraryName"
                        name="library_name"
                        value="{{ old('library_name', $settings->library_name ?? '') }}"
                        placeholder="Enter library name"
                        class="px-[12px] py-[12px]
                               border-2 border-[#e0e0e0]
                               rounded-[8px]
                               text-[15px]
                               outline-none
                               transition-colors
                               focus:border-[#66bb6a]"
                    >
                </div>

                <div class="flex flex-col gap-[8px]">
                    <label
                        for="operationHours"
                        class="font-semibold text-[15px] text-[#333] flex items-center gap-[8px]"
                    >
                        <span class="text-[18px]">🕐</span>
                        Operation Hours:
                    </label>
                    <input
                        type="text"
                        id="operationHours"
                        name="operation_hours"
                        value="{{ old('operation_hours', $settings->operation_hours ?? '') }}"
                        placeholder="e.g., Mon-Fri: 8:00 AM - 5:00 PM"
                        class="px-[12px] py-[12px]
                               border-2 border-[#e0e0e0]
                               rounded-[8px]
                               text-[15px]
                               outline-none
                               transition-colors
                               focus:border-[#66bb6a]"
                    >
                </div>

                <div class="flex flex-col gap-[8px]">
                    <label
                        for="libraryContact"
                        class="font-semibold text-[15px] text-[#333] flex items-center gap-[8px]"
                    >
                        <span class="text-[18px]">📞</span>
                        Contact Number:
                    </label>
                    <input
                        type="tel"
                        id="libraryContact"
                        name="library_contact"
                        value="{{ old('library_contact', $settings->library_contact ?? '') }}"
                        placeholder="Enter contact number"
                        class="px-[12px] py-[12px]
                               border-2 border-[#e0e0e0]
                               rounded-[8px]
                               text-[15px]
                               outline-none
                               transition-colors
                               focus:border-[#66bb6a]"
                    >
                </div>

                <div class="flex flex-col gap-[8px]">
                    <label
                        for="libraryEmail"
                        class="font-semibold text-[15px] text-[#333] flex items-center gap-[8px]"
                    >
                        <span class="text-[18px]">📧</span>
                        Email Address:
                    </label>
                    <input
                        type="email"
                        id="libraryEmail"
                        name="library_email"
                        value="{{ old('library_email', $settings->library_email ?? '') }}"
                        placeholder="Enter email address"
                        class="px-[12px] py-[12px]
                               border-2 border-[#e0e0e0]
                               rounded-[8px]
                               text-[15px]
                               outline-none
                               transition-colors
                               focus:border-[#66bb6a]"
                    >
                </div>

                <div class="flex flex-col gap-[8px]">
                    <label
                        for="libraryAddress"
                        class="font-semibold text-[15px] text-[#333] flex items-center gap-[8px]"
                    >
                        <span class="text-[18px]">📍</span>
                        Library Address:
                    </label>
                    <textarea
                        id="libraryAddress"
                        name="library_address"
                        rows="3"
                        placeholder="Enter complete library address"
                        class="px-[12px] py-[12px]
                               border-2 border-[#e0e0e0]
                               rounded-[8px]
                               text-[15px]
                               resize-y
                               outline-none
                               transition-colors
                               focus:border-[#66bb6a]"
                    >{{ old('library_address', $settings->library_address ?? '') }}</textarea>
                </div>

                <div class="flex flex-col sm:flex-row gap-[12px] mt-[25px]">
                    <button
                        type="button"
                        @click="libraryInfoModalOpen = false"
                        class="flex-1 px-[20px] py-[14px]
                               rounded-[8px] border-0
                               text-[15px] font-semibold
                               bg-[#a5d6a7] text-black
                               cursor-pointer
                               transition-colors
                               hover:bg-[#81c784]"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        class="flex-1 px-[20px] py-[14px]
                               rounded-[8px] border-0
                               text-[15px] font-semibold
                               bg-[#2e7d32] text-white
                               cursor-pointer
                               transition-colors
                               hover:bg-[#1b5e20]"
                    >
                        Save Information
                    </button>
                </div>
            </form>

        </div>
    </div>

    {{-- My Account Modal --}}
    <div
        x-show="myAccountModalOpen"
        x-cloak
        @click.self="myAccountModalOpen = false"
        class="fixed inset-0 bg-black/50 z-[2000]
               flex items-center justify-center
               p-[20px] overflow-y-auto"
    >
        <div
            @click.stop
            class="bg-white rounded-[20px] p-[40px]
                   w-full max-w-[700px] max-h-[90vh]
                   overflow-y-auto relative
                   shadow-[0_10px_30px_rgba(0,0,0,0.25)]"
        >
            <button
                type="button"
                @click="myAccountModalOpen = false"
                class="absolute top-[20px] right-[20px]
                       bg-transparent border-0
                       text-[32px] text-[#666]
                       w-[40px] h-[40px]
                       flex items-center justify-center
                       rounded-full cursor-pointer
                       transition-all
                       hover:bg-[#f5f5f5] hover:text-[#333]"
            >
                ×
            </button>

            {{-- User Profile --}}
            <div
                class="text-center p-[20px] mb-[30px]"
            >
                <div class="w-[100px] h-[100px] rounded-full overflow-hidden mx-auto mb-[15px]">
                    @if(auth()->user()->avatar_path)
                        <img
                            src="{{ asset('storage/' . auth()->user()->avatar_path) }}"
                            alt="Profile picture"
                            class="w-full h-full object-cover"
                        >
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gray-300 text-4xl">
                            👤
                        </div>
                    @endif
                </div>

                <h3
                    id="accountUserName"
                    class="text-[20px] font-bold text-[#333]"
                >
                    {{ auth()->user()->name ?? 'User Name of Admin' }}
                </h3>
            </div>

            {{-- Account Form --}}
            <form
                method="POST"
                action="{{ route('admin.account.update') }}"
                enctype="multipart/form-data"
                class="mb-[30px]"
            >
                @csrf

                <h3 class="text-[18px] font-bold mb-[20px] text-[#333]">
                    Personal Information:
                </h3>

                <div class="flex flex-col gap-[8px] mb-[15px]">
                    <label class="font-semibold text-[14px] text-[#333]">
                        Profile Picture:
                    </label>
                    <input
                        type="file"
                        name="avatar"
                        accept="image/*"
                        class="px-[12px] py-[12px]
                               border-2 border-[#e0e0e0]
                               rounded-[8px]
                               text-[15px]
                               outline-none
                               transition-colors
                               focus:border-[#66bb6a]"
                    >
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-[15px] mb-[15px]">
                    <div class="flex flex-col gap-[8px]">
                        <label class="font-semibold text-[14px] text-[#333]">
                            Full Name:
                        </label>
                        <input
                            type="text"
                            name="name"
                            value="{{ old('name', auth()->user()->name) }}"
                            class="px-[12px] py-[12px]
                                   border-2 border-[#e0e0e0]
                                   rounded-[8px]
                                   text-[15px]
                                   outline-none
                                   transition-colors
                                   focus:border-[#66bb6a]"
                        >
                    </div>

                    <div class="flex flex-col gap-[8px]">
                        <label class="font-semibold text-[14px] text-[#333]">
                            Email address:
                        </label>
                        <input
                            type="email"
                            name="email"
                            value="{{ old('email', auth()->user()->email) }}"
                            class="px-[12px] py-[12px]
                                   border-2 border-[#e0e0e0]
                                   rounded-[8px]
                                   text-[15px]
                                   outline-none
                                   transition-colors
                                   focus:border-[#66bb6a]"
                        >
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-[12px] mt-[25px]">
                    <button
                        type="button"
                        @click="myAccountModalOpen = false"
                        class="flex-1 px-[20px] py-[14px]
                               rounded-[8px] border-0
                               text-[15px] font-semibold
                               bg-[#a5d6a7] text-black
                               cursor-pointer
                               transition-colors
                               hover:bg-[#81c784]"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        class="flex-1 px-[20px] py-[14px]
                               rounded-[8px] border-0
                               text-[15px] font-semibold
                               bg-[#2e7d32] text-white
                               cursor-pointer
                               transition-colors
                               hover:bg-[#1b5e20]"
                    >
                        Save Changes
                    </button>
                </div>
            </form>

            {{-- Password Section --}}
            <div class="pt-[30px] border-t-2 border-[#e0e0e0]">
                <h3 class="text-[18px] font-bold mb-[20px] text-[#333]">
                    Change Password:
                </h3>

                <form
                    method="POST"
                    action="{{ route('admin.account.password') }}"
                    class="flex flex-col gap-[15px]"
                >
                    @csrf

                    <div class="flex flex-col gap-[8px]">
                        <label class="font-semibold text-[14px] text-[#333]">
                            Current Password:
                        </label>
                        <input
                            type="password"
                            name="current_password"
                            class="px-[12px] py-[12px]
                                   border-2 border-[#e0e0e0]
                                   rounded-[8px]
                                   text-[15px]
                                   outline-none
                                   transition-colors
                                   focus:border-[#66bb6a]"
                        >
                    </div>

                    <div class="flex flex-col gap-[8px]">
                        <label class="font-semibold text-[14px] text-[#333]">
                            New Password:
                        </label>
                        <input
                            type="password"
                            name="password"
                            minlength="8"
                            class="px-[12px] py-[12px]
                                   border-2 border-[#e0e0e0]
                                   rounded-[8px]
                                   text-[15px]
                                   outline-none
                                   transition-colors
                                   focus:border-[#66bb6a]"
                        >
                    </div>

                    <div class="flex flex-col gap-[8px]">
                        <label class="font-semibold text-[14px] text-[#333]">
                            Confirm Password:
                        </label>
                        <input
                            type="password"
                            name="password_confirmation"
                            class="px-[12px] py-[12px]
                                   border-2 border-[#e0e0e0]
                                   rounded-[8px]
                                   text-[15px]
                                   outline-none
                                   transition-colors
                                   focus:border-[#66bb6a]"
                        >
                    </div>

                    <button
                        type="submit"
                        class="w-full px-[20px] py-[14px]
                               rounded-[8px] border-0
                               text-[15px] font-semibold
                               bg-[#2e7d32] text-white
                               cursor-pointer
                               mt-[15px]
                               transition-colors
                               hover:bg-[#1b5e20]"
                    >
                        Change Password
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection
