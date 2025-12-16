{{-- resources/views/admin/users/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit User - SNSU Library E-Request')

@section('content')
<div class="min-h-screen bg-[#e8e8e8]">
    {{-- Top Navigation Bar --}}
    @include('admin.partials.navbar')

    <div class="flex min-h-[calc(100vh-60px)]">
        {{-- Sidebar --}}
        @include('admin.partials.sidebar')

        {{-- Main Content --}}
        <main class="flex-1 px-[30px] py-[30px] bg-[#f5f5f5]">
            @php
                // Make sure we always have a $profile variable available
                $profile = $profile ?? ($user->profile ?? null);
            @endphp

            <div class="max-w-[800px] mx-auto bg-white rounded-[20px] p-[30px] shadow-[0_5px_20px_rgba(0,0,0,0.10)]">

                <h1 class="text-[24px] mb-[20px] text-[#333] font-semibold">
                    Edit User
                </h1>

                {{-- Flash error message (e.g. delete self, etc.) --}}
                @if (session('error'))
                    <div class="mb-4 rounded-[10px] px-4 py-3 bg-red-100 text-red-700 text-sm">
                        {{ session('error') }}
                    </div>
                @endif

                {{-- Validation errors --}}
                @if ($errors->any())
                    <div class="mb-4 rounded-[10px] px-4 py-3 bg-red-100 text-red-700 text-sm">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- MAIN FORM --}}
                <form
                    method="POST"
                    action="{{ route('admin.users.update', $user) }}"
                    class="space-y-[24px]"
                >
                    @csrf
                    @method('PUT')

                    {{-- Account Info --}}
                    <div class="border-b border-gray-200 pb-[16px] mb-[10px]">
                        <h2 class="text-[18px] font-semibold text-[#2e7d32] mb-[12px]">
                            Account Information
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-[20px]">
                            @php
                                    // Prefer first + last from student profile if present
                                    $profileFullName = trim(
                                        ($profile->first_name ?? '') . ' ' . ($profile->last_name ?? '')
                                    );
                                @endphp

                                {{-- Full Name (derived from student profile if available) --}}
                                <div>
                                    <label class="block text-sm font-semibold mb-1 text-[#333]">
                                        Full Name
                                    </label>
                                    <input
                                        type="text"
                                        name="name"
                                        value="{{ old('name', $profileFullName !== '' ? $profileFullName : $user->name) }}"
                                        class="w-full rounded-[10px] border border-[#ddd] px-3 py-2 text-[14px] outline-none focus:border-[#4a90e2]"
                                        required
                                    >
                                </div>


                            {{-- Email --}}
                            <div>
                                <label class="block text-sm font-semibold mb-1 text-[#333]">
                                    Email Address
                                </label>
                                <input
                                    type="email"
                                    name="email"
                                    value="{{ old('email', $user->email) }}"
                                    class="w-full rounded-[10px] border border-[#ddd] px-3 py-2 text-[14px] outline-none focus:border-[#4a90e2]"
                                    required
                                >
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-[20px] mt-[16px]">
                            {{-- Role --}}
                            <div>
                                <label class="block text-sm font-semibold mb-1 text-[#333]">
                                    Role
                                </label>
                                <select
                                    name="role"
                                    class="w-full rounded-[10px] border border-[#ddd] px-3 py-2 text-[14px] outline-none focus:border-[#4a90e2]"
                                    required
                                >
                                    <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>
                                        Admin
                                    </option>
                                    <option value="student" {{ old('role', $user->role) === 'student' ? 'selected' : '' }}>
                                        Student
                                    </option>
                                </select>
                            </div>

                            {{-- Status (optional but matches index display) --}}
                            <div>
                                <label class="block text-sm font-semibold mb-1 text-[#333]">
                                    Status
                                </label>
                                <select
                                    name="status"
                                    class="w-full rounded-[10px] border border-[#ddd] px-3 py-2 text-[14px] outline-none focus:border-[#4a90e2]"
                                >
                                    @php
                                        $statusValue = old('status', $user->status ?? 'active');
                                    @endphp
                                    <option value="active" {{ $statusValue === 'active' ? 'selected' : '' }}>
                                        Active
                                    </option>
                                    <option value="inactive" {{ $statusValue === 'inactive' ? 'selected' : '' }}>
                                        Inactive
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Student Profile (optional, used when role = student) --}}
                    <div>
                        <h2 class="text-[18px] font-semibold text-[#2e7d32] mb-[12px]">
                            Student Profile (optional)
                        </h2>
                        <p class="text-xs text-gray-500 mb-[14px]">
                            These fields are mainly used when the user's role is <strong>student</strong>.
                        </p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-[20px]">
                            {{-- Student ID --}}
                            <div>
                                <label class="block text-sm font-semibold mb-1 text-[#333]">
                                    Student ID
                                </label>
                                <input
                                    type="text"
                                    name="student_id"
                                    value="{{ old('student_id', $profile->student_id ?? '') }}"
                                    class="w-full rounded-[10px] border border-[#ddd] px-3 py-2 text-[14px] outline-none focus:border-[#4a90e2]"
                                >
                            </div>

                            {{-- Phone --}}
                            <div>
                                <label class="block text-sm font-semibold mb-1 text-[#333]">
                                    Phone Number
                                </label>
                                <input
                                    type="text"
                                    name="phone"
                                    value="{{ old('phone', $profile->phone ?? '') }}"
                                    class="w-full rounded-[10px] border border-[#ddd] px-3 py-2 text-[14px] outline-none focus:border-[#4a90e2]"
                                    placeholder="09XXXXXXXXX"
                                >
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-[20px] mt-[16px]">
                            {{-- First Name --}}
                            <div>
                                <label class="block text-sm font-semibold mb-1 text-[#333]">
                                    First Name
                                </label>
                                <input
                                    type="text"
                                    name="first_name"
                                    value="{{ old('first_name', $profile->first_name ?? '') }}"
                                    class="w-full rounded-[10px] border border-[#ddd] px-3 py-2 text-[14px] outline-none focus:border-[#4a90e2]"
                                >
                            </div>

                            {{-- Last Name --}}
                            <div>
                                <label class="block text-sm font-semibold mb-1 text-[#333]">
                                    Last Name
                                </label>
                                <input
                                    type="text"
                                    name="last_name"
                                    value="{{ old('last_name', $profile->last_name ?? '') }}"
                                    class="w-full rounded-[10px] border border-[#ddd] px-3 py-2 text-[14px] outline-none focus:border-[#4a90e2]"
                                >
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-[20px] mt-[16px]">
                            {{-- Course --}}
                            <div>
                                <label class="block text-sm font-semibold mb-1 text-[#333]">
                                    Course / Program
                                </label>
                                <input
                                    type="text"
                                    name="course"
                                    value="{{ old('course', $profile->course ?? '') }}"
                                    class="w-full rounded-[10px] border border-[#ddd] px-3 py-2 text-[14px] outline-none focus:border-[#4a90e2]"
                                >
                            </div>

                            {{-- Year Level --}}
                            <div>
                                <label class="block text-sm font-semibold mb-1 text-[#333]">
                                    Year Level
                                </label>
                                <select
                                    name="year_level"
                                    class="w-full rounded-[10px] border border-[#ddd] px-3 py-2 text-[14px] outline-none focus:border-[#4a90e2]"
                                >
                                    <option value="">Select Year Level</option>
                                    @php
                                        $yl = old('year_level', $profile->year_level ?? '');
                                    @endphp
                                    <option value="1" {{ $yl == '1' ? 'selected' : '' }}>1st Year</option>
                                    <option value="2" {{ $yl == '2' ? 'selected' : '' }}>2nd Year</option>
                                    <option value="3" {{ $yl == '3' ? 'selected' : '' }}>3rd Year</option>
                                    <option value="4" {{ $yl == '4' ? 'selected' : '' }}>4th Year</option>
                                </select>
                            </div>
                        </div>

                        {{-- Address --}}
                        <div class="mt-[16px]">
                            <label class="block text-sm font-semibold mb-1 text-[#333]">
                                Address
                            </label>
                            <textarea
                                name="address"
                                rows="3"
                                class="w-full rounded-[10px] border border-[#ddd] px-3 py-2 text-[14px] outline-none focus:border-[#4a90e2] resize-y"
                                placeholder="Full residential address"
                            >{{ old('address', $profile->address ?? '') }}</textarea>
                        </div>
                    </div>

                    {{-- Buttons --}}
                    <div class="flex gap-[10px] mt-[10px]">
                        <a
                            href="{{ route('admin.users.index') }}"
                            class="inline-flex justify-center items-center px-[20px] py-[10px]
                                   rounded-[8px] text-[14px] font-semibold
                                   bg-[#9e9e9e] text-white hover:bg-[#757575]"
                        >
                            Cancel
                        </a>

                        <button
                            type="submit"
                            class="inline-flex justify-center items-center px-[20px] py-[10px]
                                   rounded-[8px] text-[14px] font-semibold
                                   bg-[#2e7d32] text-white hover:bg-[#1b5e20]"
                        >
                            Save Changes
                        </button>
                    </div>
                </form>

                {{-- Optional Delete --}}
                <form
                    method="POST"
                    action="{{ route('admin.users.destroy', $user) }}"
                    class="mt-[20px] border-t border-[#eee] pt-[20px]"
                    onsubmit="return confirm('Are you sure you want to delete this user?');"
                >
                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        class="inline-flex justify-center items-center px-[20px] py-[10px]
                               rounded-[8px] text-[14px] font-semibold
                               bg-red-600 text-white hover:bg-red-700"
                    >
                        Delete User
                    </button>
                </form>
            </div>
        </main>
    </div>

    {{-- Footer Logos --}}
    @include('admin.partials.footer-logos')
</div>
@endsection
