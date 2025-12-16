@extends('layouts.app')

@section('title', 'Admin Dashboard - SNSU Library E-Request')

@push('styles')
<style>[x-cloak]{display:none!important}</style>
@endpush

@section('content')
<div class="min-h-screen bg-[#e8e8e8]" x-data="{ adminMenuOpen: false }">
    {{-- Top Navigation Bar --}}
    @include('admin.partials.navbar')

    {{-- Layout: sidebar + main --}}
    <div class="flex min-h-[calc(100vh-60px)]">
        {{-- Sidebar --}}
        @include('admin.partials.sidebar')

        {{-- Main content --}}
        <main class="flex-1 px-[30px] py-[30px] bg-[#f5f5f5]">
            <div class="mb-[30px]">
                <h1 class="text-[32px] text-[#333] font-semibold">Dashboard</h1>
            </div>

            {{-- Status cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-[25px] mb-[40px]">
                {{-- Pending --}}
                <a
                    href="{{ route('admin.requests.pending') }}"
                    class="rounded-[20px] p-[30px] text-center
                           shadow-[0_8px_20px_rgba(0,0,0,0.15)]
                           text-white no-underline cursor-pointer
                           transition-transform duration-300
                           hover:-translate-y-[5px] hover:shadow-[0_12px_30px_rgba(0,0,0,0.20)]
                           bg-[#81c784]"
                >
                    <h3 class="text-[20px] font-bold mb-[15px]">Pending:</h3>
                    <p class="text-[56px] font-bold">
                        {{ $pendingRequests ?? 0 }}
                    </p>
                </a>

                {{-- Total --}}
                <a
                    href="{{ route('admin.requests.index') }}"
                    class="rounded-[20px] p-[30px] text-center
                           shadow-[0_8px_20px_rgba(0,0,0,0.15)]
                           no-underline cursor-pointer
                           transition-transform duration-300
                           hover:-translate-y-[5px] hover:shadow-[0_12px_30px_rgba(0,0,0,0.20)]
                           bg-[#a5d6a7] text-black"
                >
                    <h3 class="text-[20px] font-bold mb-[15px]">Total Request:</h3>
                    <p class="text-[56px] font-bold">
                        {{ $totalRequests ?? 0 }}
                    </p>
                </a>

                {{-- Processing --}}
                <a
                    href="{{ route('admin.requests.processing') }}"
                    class="rounded-[20px] p-[30px] text-center
                           shadow-[0_8px_20px_rgba(0,0,0,0.15)]
                           text-white no-underline cursor-pointer
                           transition-transform duration-300
                           hover:-translate-y-[5px] hover:shadow-[0_12px_30px_rgba(0,0,0,0.20)]
                           bg-[#00897b]"
                >
                    <h3 class="text-[20px] font-bold mb-[15px]">Processing:</h3>
                    <p class="text-[56px] font-bold">
                        {{ $processingRequests ?? 0 }}
                    </p>
                </a>

                {{-- Completed --}}
                <a
                    href="{{ route('admin.requests.completed') }}"
                    class="rounded-[20px] p-[30px] text-center
                           shadow-[0_8px_20px_rgba(0,0,0,0.15)]
                           text-white no-underline cursor-pointer
                           transition-transform duration-300
                           hover:-translate-y-[5px] hover:shadow-[0_12px_30px_rgba(0,0,0,0.20)]
                           bg-[#66bb6a]"
                >
                    <h3 class="text-[20px] font-bold mb-[15px]">Completed:</h3>
                    <p class="text-[56px] font-bold">
                        {{ $completedRequests ?? 0 }}
                    </p>
                </a>
            </div>

            {{-- Recent Requests Table --}}
            <div class="bg-white rounded-[20px] p-[30px] shadow-[0_5px_20px_rgba(0,0,0,0.10)]">
                <h2 class="text-[24px] mb-[20px] text-[#333]">Resent Request</h2>

                <div class="overflow-x-auto">
                    <table class="w-full border-collapse text-[14px] data-table">
                        <thead>
                            <tr class="bg-[#e0e0e0]">
                                <th class="px-[15px] py-[15px] text-left font-semibold text-[14px]">Student</th>
                                <th class="px-[15px] py-[15px] text-left font-semibold text-[14px]">Title</th>
                                <th class="px-[15px] py-[15px] text-left font-semibold text-[14px]">Author</th>
                                <th class="px-[15px] py-[15px] text-left font-semibold text-[14px]">Category</th>
                                <th class="px-[15px] py-[15px] text-left font-semibold text-[14px]">Year-Pub</th>
                                <th class="px-[15px] py-[15px] text-left font-semibold text-[14px]">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $recentRequests = $recentRequests ?? [];
                            @endphp

                            @forelse ($recentRequests as $req)
                                <tr class="border-b border-[#e0e0e0] transition-colors duration-200 hover:bg-[#f5f5f5]">
                                    <td class="px-[15px] py-[15px]">
                                        {{ $req->user->name ?? 'Unknown' }}
                                    </td>
                                    <td class="px-[15px] py-[15px]">
                                        {{ $req->book->title ?? 'Unknown Book' }}
                                    </td>
                                    <td class="px-[15px] py-[15px]">
                                        {{ $req->book->author ?? '-' }}
                                    </td>
                                    <td class="px-[15px] py-[15px]">
                                        {{ $req->book->category->name ?? '-' }}
                                    </td>
                                    <td class="px-[15px] py-[15px]">
                                        {{ $req->book->year_published ?? '-' }}
                                    </td>
                                    <td class="px-[15px] py-[15px]">
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
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-[15px] py-[15px] text-center text-gray-500">
                                        No recent requests.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    {{-- Footer logos --}}
    @include('admin.partials.footer-logos')
</div>
@endsection
