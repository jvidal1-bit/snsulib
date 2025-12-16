@extends('layouts.app')

@section('title', 'Manage Requests - SNSU Library E-Request')

@push('styles')
    <style>[x-cloak]{ display:none!important }</style>
@endpush

@section('content')
<div
    class="min-h-screen bg-[#e8e8e8]"
    x-data="{
        adminMenuOpen: false,
        requestModalOpen: false,
        uploadFileName: 'No file selected',
    }"
>
    {{-- Top Navigation Bar --}}
    @include('admin.partials.navbar')

    {{-- Layout: sidebar + main --}}
    <div class="flex min-h-[calc(100vh-60px)]">
        {{-- Sidebar --}}
        @include('admin.partials.sidebar')

        {{-- Main Content --}}
        <main class="flex-1 px-[30px] py-[30px] bg-[#f5f5f5]">
            {{-- Page Title --}}
            <div class="mb-[30px]">
                <h1 class="text-[32px] text-[#333] font-semibold">
                    Manage Request
                </h1>
            </div>

            {{-- Search Bar --}}
            <div class="mb-[30px]">
                <div
                    class="max-w-[600px]
                           relative flex items-center
                           bg-white
                           rounded-full
                           px-[20px] py-[5px]
                           shadow-[0_3px_10px_rgba(0,0,0,0.10)]"
                >
                    <form
                        method="GET"
                        action="{{ route('admin.requests.index') }}"
                        class="flex items-center w-full"
                    >
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
                            placeholder="Search requests..."
                            class="flex-1 border-none outline-none
                                   py-[10px] text-[16px]
                                   bg-transparent"
                        />
                    </form>
                </div>
            </div>

            {{-- Requests Table --}}
            <div class="bg-white rounded-[20px] p-[30px] shadow-[0_5px_20px_rgba(0,0,0,0.10)]">
                <h2 class="text-[24px] mb-[20px] text-[#333]">
                    Manage Request
                </h2>

                <div class="overflow-x-auto">
                    <table class="w-full border-collapse text-[14px]">
                        <thead>
                            <tr class="bg-[#e0e0e0]">
                                <th class="px-[15px] py-[15px] text-left font-semibold text-[14px]">
                                    Student
                                </th>
                                <th class="px-[15px] py-[15px] text-left font-semibold text-[14px]">
                                    Cover
                                </th>
                                <th class="px-[15px] py-[15px] text-left font-semibold text-[14px]">
                                    Title
                                </th>
                                <th class="px-[15px] py-[15px] text-left font-semibold text-[14px]">
                                    Author
                                </th>
                                <th class="px-[15px] py-[15px] text-left font-semibold text-[14px]">
                                    Category
                                </th>
                                <th class="px-[15px] py-[15px] text-left font-semibold text-[14px]">
                                    Year-Pub
                                </th>
                                <th class="px-[15px] py-[15px] text-left font-semibold text-[14px]">
                                    Action
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($requests as $req)
                                @php
                                    $book = $req->book; // may be null if book was deleted

                                    $coverUrl = $book && $book->cover_path
                                        ? asset('storage/' . $book->cover_path)
                                        : asset('assets/images/laravel-book.png'); // fallback
                                @endphp

                                <tr
                                    class="border-b border-[#e0e0e0]
                                           transition-colors duration-200
                                           hover:bg-[#f5f5f5]"
                                >
                                    {{-- Student --}}
                                    <td class="px-[15px] py-[15px]">
                                        {{ $req->user->name ?? 'Unknown' }}
                                    </td>

                                    {{-- Cover --}}
                                    <td class="px-[15px] py-[15px]">
                                        @if($book && $book->cover_path)
                                            <div class="w-[40px] h-[55px] rounded overflow-hidden bg-gray-100 shadow-sm">
                                                <img
                                                    src="{{ $coverUrl }}"
                                                    alt="Book Cover"
                                                    class="w-full h-full object-cover"
                                                >
                                            </div>
                                        @else
                                            <div class="w-[40px] h-[55px] rounded bg-gray-100 flex items-center justify-center text-xs text-gray-400">
                                                📚
                                            </div>
                                        @endif
                                    </td>

                                    {{-- Title --}}
                                    <td class="px-[15px] py-[15px]">
                                        {{ optional($book)->title ?? 'Unknown Book' }}
                                    </td>

                                    {{-- Author --}}
                                    <td class="px-[15px] py-[15px]">
                                        {{ optional($book)->author ?? '-' }}
                                    </td>

                                    {{-- Category --}}
                                    <td class="px-[15px] py-[15px]">
                                        {{ optional(optional($book)->category)->name ?? '-' }}
                                    </td>

                                    {{-- Year published --}}
                                    <td class="px-[15px] py-[15px]">
                                        {{ optional($book)->year_published ?? '-' }}
                                    </td>

                                    {{-- Action --}}
                                    <td class="px-[15px] py-[15px]">
                                        @if($book)
                                            <a
                                                href="{{ route('admin.requests.show', $req) }}"
                                                class="inline-flex items-center justify-center
                                                       text-[20px]
                                                       w-8 h-8
                                                       rounded-full
                                                       transition-transform
                                                       hover:scale-110"
                                                title="View Request"
                                            >
                                                ⚙️
                                            </a>
                                        @else
                                            <span class="text-xs text-gray-400 italic">
                                                Book removed
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-[15px] py-[15px] text-center text-gray-500">
                                        No requests found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination (from controller paginate(...)) --}}
                @if(method_exists($requests, 'links'))
                    <div class="mt-4">
                        {{ $requests->links() }}
                    </div>
                @endif
            </div>
        </main>
    </div>

    {{-- Footer Logos --}}
    @include('admin.partials.footer-logos')

    {{-- The big Alpine-powered "Request Details" modal below is still just dummy UI.
         You’re now using admin.requests.show($req) instead, so you can either:
         - keep this as future enhancement, or
         - delete it to avoid confusion. --}}
</div>
@endsection
