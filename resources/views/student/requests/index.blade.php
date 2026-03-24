@extends('layouts.app')

@section('title', 'My Requests - SNSU Library E-Request')

@push('styles')
<style>
    [x-cloak] { display: none !important; }
</style>
@endpush

@section('content')
    <div class="min-h-screen bg-gray-100" x-data="myRequestsPage()">
       @include('student.partials.navbar')

        {{-- Main Content --}}
        <main class="max-w-6xl mx-auto px-4 py-8">
            {{-- Flash messages --}}
            @if(session('status'))
                <div class="mb-4">
                    <div class="bg-green-100 text-green-800 text-sm px-4 py-2 rounded-lg">
                        {{ session('status') }}
                    </div>
                </div>
            @endif

            {{-- Filter pills (links) --}}
            <div class="mb-4 flex flex-wrap gap-2">
                <a href="{{ route('student.requests.index') }}"
                   class="px-3 py-1 text-xs font-semibold rounded-full border
                          {{ request()->routeIs('student.requests.index') ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 hover:bg-gray-100' }}">
                    All
                </a>
                <a href="{{ route('student.requests.pending') }}"
                   class="px-3 py-1 text-xs font-semibold rounded-full border bg-white text-gray-700 hover:bg-gray-100">
                    Pending
                </a>
                <a href="{{ route('student.requests.processing') }}"
                   class="px-3 py-1 text-xs font-semibold rounded-full border bg-white text-gray-700 hover:bg-gray-100">
                    Processing
                </a>
                <a href="{{ route('student.requests.completed') }}"
                   class="px-3 py-1 text-xs font-semibold rounded-full border bg-white text-gray-700 hover:bg-gray-100">
                    Completed
                </a>
            </div>

            <div class="table-container bg-white rounded-2xl shadow-xl px-6 py-6">
                <h2 class="text-2xl font-bold mb-4">My Requests</h2>

                <div class="overflow-x-auto">
                    <table class="request-table min-w-full text-sm border-collapse">
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
                            @forelse($requests as $requestItem)
                                @php
                                    $status = strtolower($requestItem->status);
                                    $statusStyles = [
                                        'pending'    => 'bg-[#fff3cd] text-[#856404]',
                                        'processing' => 'bg-[#cfe2ff] text-[#084298]',
                                        'completed'  => 'bg-[#d1e7dd] text-[#0f5132]',
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
                                        <span class="inline-flex px-4 py-1 rounded-full text-xs font-semibold {{ $statusStyles[$status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst($status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-gray-700">
                                        {{ optional($requestItem->created_at)->format('m/d/y') }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <button
                                            type="button"
                                            class="action-btn text-xl leading-none bg-transparent border-0 cursor-pointer transform transition hover:scale-110"
                                            @click="openModal(@js([
                                                'id'           => $requestItem->id,
                                                'isbn'         => $requestItem->book->isbn ?? '—',
                                                'title'        => $requestItem->book->title ?? 'Unknown Title',
                                                'chapter'      => $requestItem->chapter,
                                                'purpose'      => $requestItem->purpose,
                                                'status'       => $requestItem->status,
                                                'needed_by'    => optional($requestItem->needed_by)->format('m/d/Y'),
                                                'note'         => $requestItem->note,
                                                'created_at'   => optional($requestItem->created_at)->setTimezone('Asia/Manila')->format('m/d/Y H:i'),
                                                'completed_file' => $requestItem->completed_file
                                                    ? asset('storage/' . $requestItem->completed_file)
                                                    : null,
                                                'prepared_by'  => $requestItem->prepared_by,
                                                'expiration_at'=> optional($requestItem->expiration_at)->format('m/d/Y H:i'),
                                                'expired'      => $requestItem->expiration_at && $requestItem->expiration_at->isPast(),
                                            ]))"
                                        >
                                            ⚙️
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                                        You have no requests yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>

        @include('student.partials.footer')

        {{-- Modal --}}
        <div
            class="fixed inset-0 flex items-center justify-center bg-black/40 z-50"
            x-show="modalOpen"
            x-cloak
            x-transition
        >
            <div class="modal-content bg-white rounded-2xl shadow-xl max-w-xl w-full mx-4 p-8">
                <h2 class="text-2xl font-bold mb-6">Request Details</h2>

                <template x-if="selected">
                    <div>
                        <div class="request-summary bg-gray-100 rounded-xl p-4 mb-6">
                            <p class="text-sm mb-2">
                                <strong>Title:</strong>
                                <span x-text="selected.title"></span>
                            </p>
                            <p class="text-sm mb-2">
                                <strong>ISBN:</strong>
                                <span x-text="selected.isbn"></span>
                            </p>
                            <p class="text-sm mb-2">
                                <strong>Chapter:</strong>
                                <span x-text="selected.chapter"></span>
                            </p>
                            <p class="text-sm mb-2">
                                <strong>Status:</strong>
                                <span x-text="selected.status"></span>
                            </p>
                            <p class="text-sm mb-2">
                                <strong>Requested On:</strong>
                                <span x-text="selected.created_at"></span>
                            </p>
                            <p class="text-sm mb-2">
                                <strong>Needed By:</strong>
                                <span x-text="selected.needed_by"></span>
                            </p>
                        </div>

                        <div class="detail-section mb-6">
                            <h3 class="text-lg font-semibold mb-2">Purpose</h3>
                            <p class="text-sm text-gray-700" x-text="selected.purpose || '—'"></p>
                        </div>

                        <div class="detail-section mb-6">
                            <h3 class="text-lg font-semibold mb-2">Note</h3>
                            <p class="text-sm text-gray-700" x-text="selected.note || 'No additional notes.'"></p>
                        </div>

                        <div class="detail-section mb-6" x-show="selected.status === 'completed'">
                            <h3 class="text-lg font-semibold mb-2">Completed Details</h3>
                            <p class="text-sm text-gray-700 mb-1">
                                <strong>Prepared By:</strong>
                                <span x-text="selected.prepared_by || '—'"></span>
                            </p>
                            <p class="text-sm text-gray-700 mb-3">
                                <strong>Expires At:</strong>
                                <span x-text="selected.expiration_at || '—'"></span>
                            </p>
                            <template x-if="selected.completed_file && !selected.expired">
                                <a
                                    :href="selected.completed_file"
                                    target="_blank"
                                    class="inline-flex px-4 py-2 rounded-full bg-blue-600 text-white text-xs font-semibold hover:bg-blue-700"
                                >
                                    Download File
                                </a>
                            </template>
                            <template x-if="selected.expired">
                                <p class="text-sm text-red-600 font-semibold mt-2">
                                    This request has expired. The file is no longer available.
                                </p>
                            </template>
                        </div>
                    </div>
                </template>

                <div class="modal-actions flex gap-3 mt-4">
                    <button
                        type="button"
                        class="btn-back flex-1 py-3 px-4 rounded-full text-sm font-semibold bg-[#a5d6a7] text-black border-0 hover:bg-[#81c784] transition"
                        @click="closeModal()"
                    >
                        Back
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function myRequestsPage() {
        return {
            modalOpen: false,
            selected: null,
            openModal(data) {
                this.selected = data;
                this.modalOpen = true;
            },
            closeModal() {
                this.modalOpen = false;
                this.selected = null;
            }
        }
    }
</script>
@endpush
