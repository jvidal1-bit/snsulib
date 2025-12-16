@extends('layouts.app')

@section('title', 'Pending - SNSU Library E-Request')

@push('styles')
<style>
    [x-cloak] { display: none !important; }
</style>
@endpush

@section('content')
    <div class="min-h-screen bg-gray-100" x-data="pendingPage()">
        {{-- Navbar --}}
        @include('student.partials.navbar')

        {{-- Main content --}}
        <main class="max-w-6xl mx-auto px-4 py-8">
            {{-- Flash messages --}}
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
                <h2 class="text-2xl font-bold mb-4">Pending:</h2>

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
                                        {{-- pending badge style from CSS --}}
                                        <span class="inline-flex px-4 py-1 rounded-full text-xs font-semibold bg-[#fff3cd] text-[#856404]">
                                            Pending
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-gray-700">
                                        {{ optional($requestItem->created_at)->format('m/d/y') }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            {{-- View / details modal trigger --}}
                                            <button
                                                type="button"
                                                class="action-btn text-xl leading-none bg-transparent border-0 cursor-pointer transform transition hover:scale-110"
                                                @click="openModal(@js([
                                                    'id'         => $requestItem->id,
                                                    'isbn'       => $requestItem->book->isbn ?? '—',
                                                    'title'      => $requestItem->book->title ?? 'Unknown Title',
                                                    'chapter'    => $requestItem->chapter,
                                                    'purpose'    => $requestItem->purpose,
                                                    'needed_by'  => optional($requestItem->needed_by)->format('m/d/Y'),
                                                    'note'       => $requestItem->note,
                                                    'created_at' => optional($requestItem->created_at)->format('m/d/Y H:i'),
                                                ]))"
                                            >
                                                ⚙️
                                            </button>

                                            {{-- Cancel button --}}
                                            <form method="POST"
                                                  action="{{ route('student.requests.cancel', $requestItem) }}"
                                                  onsubmit="return confirm('Cancel this request?');">
                                                @csrf
                                                <button
                                                    type="submit"
                                                    class="text-xs px-3 py-1 rounded-full bg-[#00897b] text-white font-semibold hover:bg-[#00695c] transition"
                                                >
                                                    Cancel
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                                        No pending requests found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>

        {{-- Footer (reusing same style as other pages) --}}
        @include('student.partials.footer')

        {{-- Modal for request details --}}
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
                    <form x-show="selected"
                          method="POST"
                          :action="`{{ url('/student/requests') }}/${selected.id}/cancel`"
                          @submit.prevent="$el.submit()">
                        @csrf
                        <button
                            type="submit"
                            class="btn-cancel flex-1 py-3 px-6 rounded-full text-sm font-semibold bg-[#00897b] text-white border-0 hover:bg-[#00695c] transition"
                        >
                            Cancel Request
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function pendingPage() {
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
