@extends('layouts.app')

@section('title', 'Catalog - SNSU Library E-Request')

@push('styles')
<style>
    [x-cloak] { display: none !important; }
</style>
@endpush

@section('content')
    <div class="min-h-screen bg-gray-100 pb-20" x-data="catalogPage()">

        @include('student.partials.navbar')

        {{-- Main Content --}}
        <main class="max-w-6xl mx-auto px-4 py-8">
            {{-- Success status alert --}}
            @if(session('status'))
                <div class="mb-4">
                    <div class="bg-green-100 text-green-800 text-sm px-4 py-2 rounded-lg">
                        {{ session('status') }}
                    </div>
                </div>
            @endif

            {{-- Error alert for unavailable book / validation --}}
            @if($errors->has('book') || $errors->any())
                <div class="mb-4">
                    <div class="bg-red-100 text-red-800 text-sm px-4 py-3 rounded-lg">
                        {{-- Specific message from controller (book unavailable) --}}
                        @if($errors->has('book'))
                            <p class="font-semibold mb-1">
                                {{ $errors->first('book') }}
                            </p>
                        @endif

                        {{-- Other validation errors, if any --}}
                        @if($errors->count() > ($errors->has('book') ? 1 : 0))
                            <ul class="list-disc list-inside mt-1 space-y-1">
                                @foreach($errors->all() as $error)
                                    @if(!$errors->has('book') || $error !== $errors->first('book'))
                                        <li>{{ $error }}</li>
                                    @endif
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Search Bar --}}
            <div class="search-container mb-8">
                <form method="GET" action="{{ route('student.catalog') }}" class="max-w-[500px] relative">
                    <input
                        type="text"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="Search books..."
                        class="w-full py-[15px] pl-5 pr-12 border-2 border-gray-300 rounded-full text-base outline-none
                               focus:border-[#2e7d32] transition"
                    >
                    <button type="submit"
                            class="absolute right-2 top-1/2 -translate-y-1/2 bg-transparent border-0 text-2xl cursor-pointer px-2">
                        🔍
                    </button>
                </form>
            </div>

            {{-- Books Grid --}}
            <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 2xl:grid-cols-5">
                @forelse($books as $book)
                    @php
                        $statusValue   = strtolower($book->status ?? 'available');
                        $isUnavailable = $statusValue !== 'available';

                        // Build cover URL with fallback
                        $coverUrl = $book->cover_path
                            ? asset('storage/' . $book->cover_path)
                            : asset('assets/images/laravel-book.png'); // placeholder
                    @endphp

                    <div class="book-card bg-white rounded-xl p-5 shadow-md transition transform
                                hover:-translate-y-1 hover:shadow-2xl flex flex-col justify-between">
                        <div>
                            {{-- Cover thumbnail --}}
                            <div class="mb-3 w-full aspect-[3/4] bg-gray-100 rounded-lg overflow-hidden flex items-center justify-center">
                                @if($book->cover_path)
                                    <img
                                        src="{{ $coverUrl }}"
                                        alt="Book Cover"
                                        class="w-full h-full object-cover"
                                    >
                                @else
                                    <div class="flex flex-col items-center justify-center text-gray-400 text-xs">
                                        <span class="text-3xl mb-1">📚</span>
                                        <span>No cover</span>
                                    </div>
                                @endif
                            </div>

                            <h3 class="book-title text-sm font-semibold mb-1 min-h-[40px]">
                                {{ $book->title }}
                            </h3>
                            <p class="book-author text-xs text-gray-600 mb-3">
                                Author: {{ $book->author ?? 'Unknown' }}
                            </p>
                            <p class="text-[11px] text-gray-500">
                                ISBN: {{ $book->isbn ?? '—' }}
                            </p>
                            <p class="text-[11px] text-gray-500">
                                Category: {{ $book->category->name ?? 'Uncategorized' }}
                            </p>
                        </div>

                        {{-- Actions (status + View button) --}}
                        <div class="book-actions mt-4 flex items-center justify-between gap-2">
                            <span
                                class="inline-flex items-center px-2 py-1 rounded-full text-[11px] font-semibold
                                @if($isUnavailable)
                                    bg-red-100 text-red-700
                                @else
                                    bg-green-100 text-green-700
                                @endif"
                            >
                                {{ ucfirst($book->status ?? 'available') }}
                            </span>

                            <button
                                type="button"
                                class="btn-view flex-1 px-3 py-2 bg-white border border-gray-300 rounded-md text-xs font-medium
                                       hover:bg-gray-100 hover:border-[#2e7d32] transition
                                       @if($isUnavailable) opacity-60 cursor-not-allowed @endif"
                                @if(!$isUnavailable)
                                    @click="openBook({
                                        id: {{ $book->id }},
                                        title: @js($book->title),
                                        author: @js($book->author),
                                        publisher: @js($book->publisher),
                                        year_published: @js($book->year_published),
                                        isbn: @js($book->isbn),
                                        category: @js(optional($book->category)->name),
                                        total_pages: @js($book->total_pages),
                                        status: @js($book->status ?? 'Available'),
                                        description: @js($book->description),
                                        table_of_contents: @js($book->table_of_contents),
                                        cover_url: @js($coverUrl),
                                    })"
                                @endif
                                @if($isUnavailable) disabled @endif
                            >
                                View
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="col-span-1 sm:col-span-2 lg:col-span-3 2xl:col-span-5">
                        <div class="bg-white rounded-xl shadow p-6 text-center text-gray-500">
                            No books found{{ request('q') ? ' for "' . e(request('q')) . '"' : '' }}.
                        </div>
                    </div>
                @endforelse
            </div>

        </main>

        {{-- Book View Modal --}}
        <div
            class="fixed inset-0 flex items-center justify-center bg-black/40 z-40"
            x-show="bookModalOpen"
            x-cloak
            x-transition
        >
            <div class="bg-white rounded-2xl shadow-xl max-w-4xl w-full mx-4 p-6 relative">
                <div class="flex flex-col md:flex-row gap-6">
                    {{-- Cover --}}
                    <div class="w-full md:w-1/3">
                        <div class="border rounded-xl h-52 flex items-center justify-center bg-gray-50 overflow-hidden">
                            <template x-if="selectedBook && selectedBook.cover_url">
                                <img
                                    :src="selectedBook.cover_url"
                                    alt="Book Cover"
                                    class="w-full h-full object-cover"
                                >
                            </template>

                            <template x-if="!selectedBook || !selectedBook.cover_url">
                                <div class="flex flex-col items-center justify-center text-gray-400 text-xs">
                                    <span class="text-4xl mb-1">📚</span>
                                    <span>No cover uploaded</span>
                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- Book info --}}
                    <div class="w-full md:w-2/3" x-show="selectedBook">
                        <h2 class="text-xl font-semibold mb-2" x-text="'Book Title: ' + (selectedBook?.title ?? '')"></h2>

                        <p class="text-sm">
                            <strong>Author:</strong>
                            <span x-text="selectedBook?.author || 'Unknown'"></span>
                        </p>
                        <p class="text-sm">
                            <strong>Publisher:</strong>
                            <span x-text="selectedBook?.publisher || '—'"></span>
                        </p>
                        <p class="text-sm">
                            <strong>Year Published:</strong>
                            <span x-text="selectedBook?.year_published || '—'"></span>
                        </p>
                        <p class="text-sm">
                            <strong>ISBN:</strong>
                            <span x-text="selectedBook?.isbn || '—'"></span>
                        </p>
                        <p class="text-sm">
                            <strong>Category:</strong>
                            <span x-text="selectedBook?.category || '—'"></span>
                        </p>
                        <p class="text-sm">
                            <strong>Total pages:</strong>
                            <span x-text="selectedBook?.total_pages || '—'"></span>
                        </p>
                        <p class="text-sm mb-2">
                            <strong>Status:</strong>
                            <span x-text="selectedBook?.status || 'Available'"></span>
                        </p>

                        <h3 class="font-semibold mt-3 mb-1">Description:</h3>
                        <p class="text-sm text-gray-700 max-h-32 overflow-y-auto"
                           x-text="selectedBook?.description || 'No description available.'"></p>

                        <h3 class="font-semibold mt-3 mb-1">Table of Contents:</h3>
                        <div class="text-sm text-gray-700 max-h-32 overflow-y-auto whitespace-pre-line"
                             x-text="selectedBook?.table_of_contents || 'No table of contents available.'">
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button
                        type="button"
                        class="px-4 py-2 rounded-lg border text-sm font-medium hover:bg-gray-100"
                        @click="closeBook()"
                    >
                        Back
                    </button>

                    <button
                        type="button"
                        class="px-4 py-2 rounded-lg text-sm font-medium bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50"
                        @click="openRequest()"
                        :disabled="!selectedBook || (selectedBook?.status ?? '').toLowerCase() !== 'available'"
                    >
                        Request
                    </button>
                </div>
            </div>
        </div>

        {{-- Request Form Modal --}}
        <div
            class="fixed inset-0 flex items-center justify-center bg-black/40 z-50"
            x-show="requestModalOpen"
            x-cloak
            x-transition
        >
            <div class="bg-white rounded-2xl shadow-xl max-w-md w-full mx-4 p-6">
                <h2 class="text-lg font-semibold mb-4">Request Form:</h2>

                <form method="POST" action="{{ route('student.requests.store') }}">
                    @csrf

                    <input type="hidden" name="book_id" :value="selectedBook?.id">

                    <div class="mb-3">
                        <label class="block text-xs font-semibold text-gray-600 mb-1">
                            Book Title:
                        </label>
                        <input
                            type="text"
                            class="w-full border rounded-lg px-3 py-2 text-sm bg-gray-100"
                            x-model="selectedBook.title"
                            readonly
                        >
                    </div>

                    <div class="mb-3">
                        <label class="block text-xs font-semibold text-gray-600 mb-1">
                            Chapter/Section:
                        </label>
                        <input
                            type="text"
                            name="chapter"
                            class="w-full border rounded-lg px-3 py-2 text-sm"
                            placeholder="Chapter/Section:"
                            required
                        >
                    </div>

                    <div class="mb-3">
                        <label class="block text-xs font-semibold text-gray-600 mb-1">
                            Purpose:
                        </label>
                        <input
                            type="text"
                            name="purpose"
                            class="w-full border rounded-lg px-3 py-2 text-sm"
                            placeholder="Purpose:"
                            required
                        >
                    </div>

                    <div class="mb-3">
                        <label class="block text-xs font-semibold text-gray-600 mb-1">
                            Needed By:
                        </label>
                        <input
                            type="date"
                            name="needed_by"
                            class="w-full border rounded-lg px-3 py-2 text-sm"
                            required
                        >
                    </div>

                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-gray-600 mb-1">
                            Note:
                        </label>
                        <input
                            type="text"
                            name="note"
                            class="w-full border rounded-lg px-3 py-2 text-sm"
                            placeholder="Note (optional):"
                        >
                    </div>

                    <div class="modal-actions flex gap-3 mt-4">
                        <button
                            type="button"
                            class="btn-back flex-1 py-3 px-4 rounded-full text-sm font-semibold bg-[#a5d6a7] text-black border-0
                                   hover:bg-[#81c784] transition"
                            @click="closeRequest()"
                        >
                            Back
                        </button>
                        <button
                            type="submit"
                            class="btn-request flex-1 py-3 px-4 rounded-full text-sm font-semibold bg-[#2e7d32] text-white border-0
                                   hover:bg-[#1b5e20] transition"
                        >
                            Submit Request
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @include('student.partials.footer')
    </div>
@endsection

@push('scripts')
<script>
    function catalogPage() {
        return {
            bookModalOpen: false,
            requestModalOpen: false,
            selectedBook: null,
            openBook(book) {
                this.selectedBook = book;
                this.bookModalOpen = true;
                this.requestModalOpen = false;
            },
            closeBook() {
                this.bookModalOpen = false;
            },
            openRequest() {
                // Extra guard on frontend just in case
                if (!this.selectedBook) return;
                if ((this.selectedBook.status || '').toLowerCase() !== 'available') {
                    alert('This book is currently unavailable for requests.');
                    return;
                }
                this.requestModalOpen = true;
            },
            closeRequest() {
                this.requestModalOpen = false;
            }
        }
    }
</script>
@endpush
