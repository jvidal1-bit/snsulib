@extends('layouts.app')

@section('title', 'Pending Requests - SNSU Library E-Request')

@push('styles')
    <style>[x-cloak]{display:none!important}</style>
@endpush

@section('content')
@php
    // Safe default so the view never breaks if the variable is missing.
    $requests = $requests ?? [];
@endphp

<div class="min-h-screen bg-[#e8e8e8]" x-data="{ adminMenuOpen: false }">
    {{-- Top Navigation Bar --}}
    @include('admin.partials.navbar')

    <div class="flex min-h-[calc(100vh-60px)]">
        {{-- Sidebar --}}
        @include('admin.partials.sidebar')

        {{-- Main Content --}}
        <main class="flex-1 px-[30px] py-[30px] bg-[#f5f5f5]">
            {{-- Content header (matches original: "Dashboard") --}}
            <div class="mb-[30px]">
                <h1 class="text-[32px] text-[#333] font-semibold">
                    Dashboard
                </h1>
            </div>

            {{-- Pending Container (status-page-container + status-pending-container) --}}
            <div
                class="rounded-[30px] p-[40px] min-h-[500px]
                       shadow-[0_10px_30px_rgba(0,0,0,0.15)]
                       bg-gradient-to-br from-[#81c784] to-[#66bb6a]"
            >
                <h2 class="text-[32px] font-bold mb-[25px] text-white">
                    Pending:
                </h2>

                <div
                    class="bg-white rounded-[20px] p-[25px]
                           shadow-[0_5px_15px_rgba(0,0,0,0.10)]"
                >
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse text-[14px]">
                            <thead>
                                <tr class="bg-[#e0e0e0]">
                                    <th class="px-[15px] py-[15px] text-left font-semibold text-[14px]">
                                        ISBN
                                    </th>
                                    <th class="px-[15px] py-[15px] text-left font-semibold text-[14px]">
                                        Title
                                    </th>
                                    <th class="px-[15px] py-[15px] text-left font-semibold text-[14px]">
                                        Chapter
                                    </th>
                                    <th class="px-[15px] py-[15px] text-left font-semibold text-[14px]">
                                        Status
                                    </th>
                                    <th class="px-[15px] py-[15px] text-left font-semibold text-[14px]">
                                        Date
                                    </th>
                                    <th class="px-[15px] py-[15px] text-left font-semibold text-[14px]">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($requests as $request)
                                    <tr
                                        class="border-b border-[#e0e0e0]
                                               transition-colors duration-200
                                               hover:bg-[#f5f5f5]"
                                    >
                                        {{-- ISBN from related book --}}
                                        <td class="px-[15px] py-[15px]">
                                            {{ $request->book->isbn ?? '-' }}
                                        </td>

                                        {{-- Book title --}}
                                        <td class="px-[15px] py-[15px]">
                                            {{ $request->book->title ?? 'Unknown Book' }}
                                        </td>

                                        {{-- Chapter from BookRequest --}}
                                        <td class="px-[15px] py-[15px]">
                                            {{ $request->chapter ?? '-' }}
                                        </td>

                                        {{-- Status --}}
                                        <td class="px-[15px] py-[15px] capitalize">
                                            {{ $request->status ?? 'pending' }}
                                        </td>

                                        {{-- Date (submitted) --}}
                                        <td class="px-[15px] py-[15px]">
                                            {{ optional($request->created_at)->format('m/d/Y') ?? '-' }}
                                        </td>

                                        {{-- Action: go to show page (details) --}}
                                        <td class="px-[15px] py-[15px]">
                                            <a
                                                href="{{ route('admin.requests.show', $request) }}"
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
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td
                                            colspan="6"
                                            class="px-[15px] py-[15px]
                                                   text-center text-gray-100
                                                   bg-[#81c784]/40 rounded-b-[20px]"
                                        >
                                            No pending requests found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Optional: pagination --}}
                    @if ($requests instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <div class="mt-[20px]">
                            {{ $requests->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>

    {{-- Footer Logos --}}
    @include('admin.partials.footer-logos')
</div>
@endsection
