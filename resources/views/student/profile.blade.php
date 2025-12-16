@extends('layouts.app')

@section('title', 'Profile - SNSU Library E-Request')

@push('styles')
<style>
    [x-cloak] { display: none !important; }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-100 pb-24" x-data>
    @include('student.partials.navbar')

    <main class="px-4 py-8">
        <div class="max-w-3xl mx-auto">

            {{-- Flash & errors --}}
            @if(session('status'))
                <div class="mb-4">
                    <div class="bg-green-100 text-green-800 text-sm px-4 py-2 rounded-lg">
                        {{ session('status') }}
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4">
                    <div class="bg-red-100 text-red-800 text-sm px-4 py-2 rounded-lg">
                        {{ $errors->first() }}
                    </div>
                </div>
            @endif

            <div class="bg-white p-6 rounded-2xl shadow-xl">
                <h1 class="text-2xl font-bold text-[#1b5e20] text-center mb-8">
                    Student Profile
                </h1>

                {{-- Profile picture section --}}
                <div class="flex flex-col items-center mb-8 pb-6 border-b border-gray-200">
                    <div class="w-32 h-32 rounded-full bg-[#a5d6a7] flex items-center justify-center mb-3 overflow-hidden shadow-md">
                        @if($user->avatar_path)
                            <img
                                src="{{ asset('storage/'.$user->avatar_path) }}"
                                alt="Profile Picture"
                                class="w-full h-full object-cover"
                            >
                        @elseif($profile->profile_picture)
                            <img
                                src="{{ asset('storage/'.$profile->profile_picture) }}"
                                alt="Profile Picture"
                                class="w-full h-full object-cover"
                            >
                        @else
                            <div class="text-4xl font-bold text-[#1b5e20]">
                                {{ strtoupper(substr($user->name ?? 'S', 0, 1)) }}
                            </div>
                        @endif
                    </div>

                    <label for="profile_picture"
                           class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold text-white cursor-pointer bg-[#4caf50] hover:bg-[#45a049] transition">
                        Change Picture
                    </label>
                </div>

                {{-- Profile form --}}
                <form
                    method="POST"
                    action="{{ route('student.profile.update') }}"
                    enctype="multipart/form-data"
                    class="flex flex-col gap-6"
                >
                    @csrf

                    <input type="file" id="profile_picture" name="profile_picture" class="hidden" accept="image/*">

                    <h2 class="text-xl font-semibold text-[#2e7d32] mb-2">
                        Personal Information
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Student ID --}}
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-gray-800">
                                Student ID:
                            </label>
                            <input
                                type="text"
                                name="student_id"
                                class="border-2 border-[#a5d6a7] rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#66bb6a] bg-gray-50"
                                value="{{ old('student_id', $profile->student_id) }}"
                            >
                        </div>

                        {{-- Email --}}
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-gray-800">
                                Email Address:
                            </label>
                            <input
                                type="email"
                                name="email"
                                class="border-2 border-[#a5d6a7] rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#66bb6a]"
                                value="{{ old('email', $user->email) }}"
                                required
                            >
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- First Name --}}
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-gray-800">
                                First Name:
                            </label>
                            <input
                                type="text"
                                name="first_name"
                                class="border-2 border-[#a5d6a7] rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#66bb6a]"
                                value="{{ old('first_name', $profile->first_name) }}"
                                required
                            >
                        </div>

                        {{-- Last Name --}}
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-gray-800">
                                Last Name:
                            </label>
                            <input
                                type="text"
                                name="last_name"
                                class="border-2 border-[#a5d6a7] rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#66bb6a]"
                                value="{{ old('last_name', $profile->last_name) }}"
                                required
                            >
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Phone --}}
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-gray-800">
                                Phone Number:
                            </label>
                            <input
                                type="tel"
                                name="phone"
                                class="border-2 border-[#a5d6a7] rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#66bb6a]"
                                placeholder="09XXXXXXXXX"
                                value="{{ old('phone', $profile->phone) }}"
                            >
                        </div>

                        {{-- Course --}}
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-gray-800">
                                Course/Program:
                            </label>
                            <input
                                type="text"
                                name="course"
                                class="border-2 border-[#a5d6a7] rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#66bb6a]"
                                value="{{ old('course', $profile->course) }}"
                            >
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Year Level --}}
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-gray-800">
                                Year Level:
                            </label>
                            <select
                                name="year_level"
                                class="border-2 border-[#a5d6a7] rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#66bb6a]"
                            >
                                <option value="">Select Year Level</option>
                                @foreach(['1' => '1st Year', '2' => '2nd Year', '3' => '3rd Year', '4' => '4th Year'] as $val => $label)
                                    <option value="{{ $val }}" {{ old('year_level', $profile->year_level) == $val ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Address --}}
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-semibold text-gray-800">
                            Address:
                        </label>
                        <textarea
                            name="address"
                            rows="3"
                            class="border-2 border-[#a5d6a7] rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#66bb6a] resize-y"
                            placeholder="Enter your complete address"
                        >{{ old('address', $profile->address) }}</textarea>
                    </div>

                    <div class="flex gap-3 mt-2">
                        <button
                            type="reset"
                            class="flex-1 px-4 py-2 rounded-lg text-sm font-semibold bg-gray-200 text-gray-800 hover:bg-gray-300 transition"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            class="flex-1 px-4 py-2 rounded-lg text-sm font-semibold bg-[#4caf50] text-white hover:bg-[#45a049] transition"
                        >
                            Save Changes
                        </button>
                    </div>
                </form>

                {{-- Change password section --}}
                <div class="mt-10 pt-6 border-t border-gray-200">
                    <h2 class="text-xl font-semibold text-[#2e7d32] mb-4">
                        Change Password
                    </h2>

                    <form method="POST" action="{{ route('student.profile.password') }}" class="flex flex-col gap-4 max-w-md">
                        @csrf

                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-gray-800">
                                Current Password:
                            </label>
                            <input
                                type="password"
                                name="current_password"
                                class="border-2 border-[#a5d6a7] rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#66bb6a]"
                                required
                            >
                        </div>

                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-gray-800">
                                New Password:
                            </label>
                            <input
                                type="password"
                                name="new_password"
                                class="border-2 border-[#a5d6a7] rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#66bb6a]"
                                required
                                minlength="8"
                            >
                        </div>

                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-gray-800">
                                Confirm New Password:
                            </label>
                            <input
                                type="password"
                                name="new_password_confirmation"
                                class="border-2 border-[#a5d6a7] rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#66bb6a]"
                                required
                                minlength="8"
                            >
                        </div>

                        <button
                            type="submit"
                            class="px-4 py-2 rounded-lg text-sm font-semibold bg-[#4caf50] text-white hover:bg-[#45a049] transition mt-2"
                        >
                            Change Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    @include('student.partials.footer')
</div>
@endsection
