{{-- resources/views/admin/requests/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Request Details - SNSU Library E-Request')

@push('styles')
    <style>[x-cloak]{display:none!important}</style>
@endpush

@section('content')
@php
    // Support both: controller may pass $bookRequest or $request (BookRequest model)
    /** @var \App\Models\BookRequest|null $br */
    $br = $bookRequest ?? $request ?? null;

    $book = $br?->book;

    $coverUrl = $book && $book->cover_path
        ? asset('storage/' . $book->cover_path)
        : asset('assets/images/laravel-book.png'); // fallback placeholder
@endphp

@if(!$br)
    <div class="min-h-screen bg-[#e8e8e8] flex items-center justify-center">
        <p class="text-gray-600">
            Request not found.
        </p>
    </div>
@else
<div class="min-h-screen bg-[#e8e8e8]" x-data="{ adminMenuOpen: false, uploadFileName: 'No file selected' }">
    {{-- Top nav & sidebar --}}
    @include('admin.partials.navbar')

    <div class="flex min-h-[calc(100vh-60px)]">
        @include('admin.partials.sidebar')

        {{-- MAIN --}}
        <main class="flex-1 px-[30px] py-[30px] bg-[#f5f5f5]">
            {{-- Header --}}
            <div class="mb-[30px] flex items-center justify-between gap-4">
                <div>
                    <h1 class="text-[32px] text-[#333] font-semibold">
                        Request Details
                    </h1>
                    <p class="text-sm text-gray-600 mt-1">
                        Request ID: <span class="font-semibold">
                            {{ $br->id }}
                        </span>
                    </p>
                </div>

                <a href="{{ route('admin.requests.index') }}"
                   class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold
                          bg-[#a5d6a7] text-black hover:bg-[#81c784] transition-colors">
                    ← Back to Requests
                </a>
            </div>

            {{-- Flash message --}}
            @if(session('status'))
                <div class="mb-4 px-4 py-3 rounded-lg bg-green-100 text-green-800 text-sm font-semibold">
                    {{ session('status') }}
                </div>
            @endif

            {{-- CONTENT CARD --}}
            <div class="bg-white rounded-[20px] p-[30px] shadow-[0_10px_30px_rgba(0,0,0,0.12)]">
                <div class="flex flex-col md:flex-row gap-[30px]">
                    {{-- LEFT: Info + form --}}
                    <div class="flex-1 space-y-5">
                        {{-- Student info --}}
                        <div class="bg-[#f9f9f9] p-[20px] rounded-[12px]">
                            <div
                                class="bg-gradient-to-r from-[#b3e5fc] to-[#81d4fa]
                                       px-[15px] py-[15px]
                                       rounded-[8px]
                                       font-semibold
                                       mb-[15px]"
                            >
                                Request ID: {{ $br->id }}
                            </div>

                            <div class="mt-[10px]">
                                <h3 class="text-[16px] font-bold mb-[12px] text-[#2e7d32]">
                                    Student Information:
                                </h3>
                                <p class="mb-[6px] text-[14px] text-[#555]">
                                    <strong class="text-[#333]">Name:</strong>
                                    {{ $br->user->name ?? 'Unknown student' }}
                                </p>
                                <p class="mb-[6px] text-[14px] text-[#555]">
                                    <strong class="text-[#333]">Email:</strong>
                                    {{ $br->user->email ?? 'N/A' }}
                                </p>
                                {{-- still placeholder unless you map to student_profiles --}}
                                <p class="mb-[6px] text-[14px] text-[#555]">
                                    <strong class="text-[#333]">Student ID:</strong>
                                    N/A
                                </p>
                                <p class="mb-[6px] text-[14px] text-[#555]">
                                    <strong class="text-[#333]">Program:</strong>
                                    N/A
                                </p>
                            </div>
                        </div>

                        {{-- Book info --}}
                        <div class="bg-[#f9f9f9] p-[20px] rounded-[12px]">
                            <h3 class="text-[16px] font-bold mb-[12px] text-[#2e7d32]">
                                Book Information:
                            </h3>
                            <p class="mb-[6px] text-[14px] text-[#555]">
                                <strong class="text-[#333]">Title:</strong>
                                {{ $book->title ?? 'Unknown Book' }}
                            </p>
                            <p class="mb-[6px] text-[14px] text-[#555]">
                                <strong class="text-[#333]">Author:</strong>
                                {{ $book->author ?? 'N/A' }}
                            </p>
                            <p class="mb-[6px] text-[14px] text-[#555]">
                                <strong class="text-[#333]">Category:</strong>
                                {{ optional($book->category)->name ?? 'N/A' }}
                            </p>
                            <p class="mb-[6px] text-[14px] text-[#555]">
                                <strong class="text-[#333]">Year Published:</strong>
                                {{ $book->year_published ?? 'N/A' }}
                            </p>
                            <p class="mb-[6px] text-[14px] text-[#555]">
                                <strong class="text-[#333]">ISBN:</strong>
                                {{ $book->isbn ?? 'N/A' }}
                            </p>
                        </div>

                        {{-- Request details --}}
                        <div class="bg-[#f9f9f9] p-[20px] rounded-[12px]">
                            <h3 class="text-[16px] font-bold mb-[12px] text-[#2e7d32]">
                                Request Details:
                            </h3>
                            <p class="mb-[6px] text-[14px] text-[#555]">
                                <strong class="text-[#333]">Chapter:</strong>
                                {{ $br->chapter ?? 'N/A' }}
                            </p>
                            <p class="mb-[6px] text-[14px] text-[#555]">
                                <strong class="text-[#333]">Purpose:</strong>
                                {{ $br->purpose ?? 'N/A' }}
                            </p>
                            <p class="mb-[6px] text-[14px] text-[#555]">
                                <strong class="text-[#333]">Needed By:</strong>
                                {{ optional($br->needed_by)->format('m/d/Y') ?? 'N/A' }}
                            </p>
                        </div>

                        {{-- Timeline --}}
                        <div class="bg-[#f9f9f9] p-[20px] rounded-[12px]">
                            <h3 class="text-[16px] font-bold mb-[12px] text-[#2e7d32]">
                                Timeline:
                            </h3>
                            <p class="mb-[6px] text-[14px] text-[#555]">
                                <strong class="text-[#333]">Submitted:</strong>
                                {{ optional($br->created_at)->format('m/d/Y - g:i A') ?? 'N/A' }}
                            </p>
                            <p class="mb-[6px] text-[14px] text-[#555]">
                                <strong class="text-[#333]">Current Status:</strong>
                                <span class="capitalize">{{ $br->status }}</span>
                            </p>
                        </div>

                        {{-- UPDATE FORM --}}
                        <form
                            method="POST"
                            action="{{ route('admin.requests.updateStatus', $br) }}"
                            enctype="multipart/form-data"
                            class="space-y-5 bg-[#f9f9f9] p-[20px] rounded-[12px]"
                        >
                            @csrf

                            <h3 class="text-[16px] font-bold mb-[8px] text-[#2e7d32]">
                                Update Request:
                            </h3>

                            {{-- Status --}}
                            <div>
                                <label class="block mb-2 text-[14px] font-semibold text-[#333]">
                                    Status:
                                </label>
                                <select
                                    name="status"
                                    class="w-full px-[15px] py-[10px]
                                           border-2 border-[#a5d6a7]
                                           rounded-[8px] text-[15px]
                                           outline-none cursor-pointer"
                                >
                                    @foreach (['pending', 'processing', 'completed', 'expired'] as $status)
                                        <option value="{{ $status }}"
                                            {{ $br->status === $status ? 'selected' : '' }}>
                                            {{ ucfirst($status) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Expiration --}}
                            <div>
                                <label class="block mb-2 text-[14px] font-semibold text-[#333]">
                                    Expiration Date (optional):
                                </label>
                                <input
                                    type="date"
                                    name="expiration_at"
                                    value="{{ optional($br->expiration_at)->format('Y-m-d') }}"
                                    class="w-full px-[12px] py-[10px]
                                           border-2 border-[#a5d6a7]
                                           rounded-[8px] text-[14px]
                                           outline-none focus:border-[#66bb6a]"
                                >
                            </div>

                            {{-- File upload --}}
                            <div>
                                <h3 class="text-[16px] font-bold mb-[8px] text-[#2e7d32]">
                                    Upload Digital Copy <span class="text-[16px]">❤️</span>:
                                </h3>
                                <div
                                    class="px-[20px] py-[20px]
                                           border-2 border-dashed border-[#ccc]
                                           rounded-[12px] text-center"
                                >
                                    <input
                                        type="file"
                                        name="file"
                                        class="hidden"
                                        x-ref="fileInput"
                                        @change="uploadFileName = $event.target.files[0]?.name || 'No file selected'"
                                        accept=".pdf,.doc,.docx"
                                    >
                                    <button
                                        type="button"
                                        class="px-[25px] py-[10px]
                                               bg-[#4caf50] text-white
                                               rounded-[8px]
                                               text-[15px] font-semibold
                                               border-0 cursor-pointer
                                               transition-colors
                                               hover:bg-[#45a049]"
                                        @click="$refs.fileInput.click()"
                                    >
                                        Choose File
                                    </button>
                                    <p
                                        class="mt-[10px] text-[14px] text-[#666]"
                                        x-text="uploadFileName"
                                    >
                                        No file selected
                                    </p>

                                    @if ($br->file_path || $br->completed_file)
                                        <div class="mt-[10px]">
                                            <a
                                                href="{{ Storage::url($br->file_path ?? $br->completed_file) }}"
                                                target="_blank"
                                                class="text-[#2196f3] font-semibold hover:underline"
                                            >
                                                View current file
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Admin notes --}}
                            <div>
                                <h3 class="text-[16px] font-bold mb-[8px] text-[#2e7d32]">
                                    Admin Notes:
                                </h3>
                                <textarea
                                    name="note"
                                    rows="3"
                                    class="w-full px-[12px] py-[12px]
                                           border-2 border-[#a5d6a7]
                                           rounded-[8px] text-[14px]
                                           resize-y outline-none
                                           focus:border-[#66bb6a]"
                                >{{ old('note', $br->note ?? '') }}</textarea>
                            </div>

                            {{-- Buttons --}}
                            <div class="flex flex-col sm:flex-row gap-[12px] mt-[10px]">
                                <a
                                    href="{{ route('admin.requests.index') }}"
                                    class="flex-1 inline-flex items-center justify-center
                                           px-[20px] py-[14px]
                                           rounded-[8px] text-[15px] font-semibold
                                           bg-[#a5d6a7] text-black
                                           hover:bg-[#81c784] transition-colors"
                                >
                                    Back
                                </a>

                                <button
                                    type="submit"
                                    class="flex-1 px-[20px] py-[14px]
                                           rounded-[8px] text-[15px] font-semibold
                                           bg-[#2e7d32] text-white
                                           hover:bg-[#1b5e20] transition-colors"
                                >
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- RIGHT: Book cover --}}
                    <div class="flex-shrink-0 flex justify-center md:justify-start">
                        <div
                            class="w-[250px] h-[350px]
                                   bg-[#f5f5f5]
                                   rounded-[12px]
                                   overflow-hidden
                                   shadow-[0_5px_15px_rgba(0,0,0,0.20)]
                                   flex items-center justify-center"
                        >
                            @if($book && $book->cover_path)
                                <img
                                    src="{{ $coverUrl }}"
                                    alt="Book Cover"
                                    class="w-full h-full object-cover"
                                >
                            @else
                                <div class="flex flex-col items-center justify-center text-gray-400 text-sm">
                                    <span class="text-4xl mb-1">📚</span>
                                    <span>No cover uploaded</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    @include('admin.partials.footer-logos')
</div>
@endif
@endsection
