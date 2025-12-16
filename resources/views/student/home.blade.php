@extends('layouts.app')

@section('title', 'Home - SNSU Library E-Request')

@section('content')
    <div class="min-h-screen bg-gray-100 pb-24" x-data>
        @include('student.partials.navbar')

        {{-- Main Content --}}
        <main class="max-w-6xl mx-auto px-4 py-8">
            {{-- Status Cards --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                    <a href="{{ route('student.requests.total') }}"
                    class="block rounded-2xl px-6 py-6 text-center text-white shadow-lg transform transition
                            hover:-translate-y-1 hover:shadow-2xl"
                    style="background-color:#66bb6a;">
                        <h3 class="text-lg font-semibold mb-2">Total Request:</h3>
                        <p class="text-5xl font-bold leading-none">
                            {{ $stats['total'] ?? 0 }}
                        </p>
                    </a>

                    <a href="{{ route('student.requests.pending') }}"
                    class="block rounded-2xl px-6 py-6 text-center text-white shadow-lg transform transition
                            hover:-translate-y-1 hover:shadow-2xl"
                    style="background-color:#81c784;">
                        <h3 class="text-lg font-semibold mb-2">Pending:</h3>
                        <p class="text-5xl font-bold leading-none">
                            {{ $stats['pending'] ?? 0 }}
                        </p>
                    </a>

                    <a href="{{ route('student.requests.processing') }}"
                    class="block rounded-2xl px-6 py-6 text-center text-black shadow-lg transform transition
                            hover:-translate-y-1 hover:shadow-2xl"
                    style="background-color:#a5d6a7;">
                        <h3 class="text-lg font-semibold mb-2">Processing:</h3>
                        <p class="text-5xl font-bold leading-none">
                            {{ $stats['processing'] ?? 0 }}
                        </p>
                    </a>

                    <a href="{{ route('student.requests.completed') }}"
                    class="block rounded-2xl px-6 py-6 text-center text-white shadow-lg transform transition
                            hover:-translate-y-1 hover:shadow-2xl"
                    style="background-color:#00897b;">
                        <h3 class="text-lg font-semibold mb-2">Completed:</h3>
                        <p class="text-5xl font-bold leading-none">
                            {{ $stats['completed'] ?? 0 }}
                        </p>
                    </a>
                </div>


                        {{-- Recent Requests Table --}}
<div class="bg-white rounded-2xl shadow-xl px-6 py-6">
    <h2 class="text-2xl font-bold mb-4">Recent Requests</h2>

    <div class="overflow-x-auto">
        <table class="min-w-full text-sm border-collapse">
            <thead>
                <tr class="bg-gray-300">
                    <th class="px-4 py-3 text-left font-semibold rounded-l-lg">ISBN</th>
                    <th class="px-4 py-3 text-left font-semibold">Title</th>
                    <th class="px-4 py-3 text-left font-semibold">Chapter</th>
                    <th class="px-4 py-3 text-left font-semibold">Status</th>
                    <th class="px-4 py-3 text-left font-semibold">Date</th>
                    <th class="px-4 py-3 text-center font-semibold rounded-r-lg">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentRequests as $requestItem)
                    @php
                        $status = strtolower($requestItem->status);
                        $statusColors = [
                            'pending'    => 'bg-yellow-100 text-yellow-800',
                            'processing' => 'bg-blue-100 text-blue-800',
                            'completed'  => 'bg-green-100 text-green-800',
                        ];
                    @endphp
                    <tr class="border-b border-gray-200 hover:bg-gray-100 transition">
                        <td class="px-4 py-3 text-gray-800">
                            {{ $requestItem->book->isbn ?? '—' }}
                        </td>
                        <td class="px-4 py-3 text-gray-800">
                            {{ $requestItem->book->title ?? 'Unknown Title' }}
                        </td>
                        <td class="px-4 py-3 text-gray-800">
                            {{ $requestItem->chapter ?? '—' }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold {{ $statusColors[$status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-700">
                            {{ optional($requestItem->created_at)->format('m/d/y') }}
                        </td>
                        <td class="px-4 py-3 text-center">
    <a href="{{ route('student.requests.index') }}"
       class="inline-flex items-center justify-center px-3 py-1 text-lg font-medium rounded-md border border-gray-300 hover:bg-gray-200 transition">
        ⚙️
    </a>
</td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                            No recent requests found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

        </main>

        {{-- Footer --}}
        @include('student.partials.footer')
    </div>
@endsection
