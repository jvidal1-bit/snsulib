@extends('layouts.app')

@section('title', 'Manage Books - SNSU Library E-Request')

@push('styles')
    <style>[x-cloak]{display:none!important}</style>
@endpush

@section('content')
@php
    /** @var \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Support\Collection $books */
    $books = $books ?? collect();
@endphp

<div
    class="min-h-screen bg-[#e8e8e8]"
    x-data="{
        adminMenuOpen: false,
        addBookOpen: false,
        bookViewOpen: false,
        coverPreview: null,
        selectedBook: null,
        handleCoverChange(event) {
            const file = event.target.files[0];
            if (!file) {
                this.coverPreview = null;
                return;
            }
            const reader = new FileReader();
            reader.onload = e => this.coverPreview = e.target.result;
            reader.readAsDataURL(file);
        }
    }"
>
    {{-- Top Navigation Bar --}}
    @include('admin.partials.navbar')

    <div class="flex min-h-[calc(100vh-60px)]">
        {{-- Sidebar --}}
        @include('admin.partials.sidebar')

        {{-- Main Content --}}
        <main class="flex-1 px-[30px] py-[30px] bg-[#f5f5f5]">
            {{-- Header with Search and Add Button --}}
            <div
                class="flex flex-col md:flex-row items-stretch md:items-center
                       justify-between gap-[20px] mb-[30px]"
            >
                {{-- Search --}}
                <div
                    class="flex-1 max-w-[600px]
                           relative flex items-center
                           bg-white
                           rounded-full
                           px-[20px] py-[5px]
                           shadow-[0_3px_10px_rgba(0,0,0,0.10)]"
                >
                    <form
                        method="GET"
                        action="{{ route('admin.books.index') }}"
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
                            placeholder="Search books..."
                            class="flex-1 border-none outline-none
                                   py-[10px] text-[16px] bg-transparent"
                        />
                    </form>
                </div>

                {{-- Add New Book --}}
                <button
                    type="button"
                    @click="addBookOpen = true"
                    class="px-[30px] py-[12px]
                           bg-[#4caf50] text-white
                           rounded-[8px]
                           text-[16px] font-semibold
                           whitespace-nowrap
                           border-0
                           cursor-pointer
                           transition-colors
                           hover:bg-[#45a049]"
                >
                    Add New Book
                </button>
            </div>

            {{-- Books Table --}}
            <div class="bg-white rounded-[20px] p-[30px] shadow-[0_5px_20px_rgba(0,0,0,0.10)]">
                <h2 class="text-[24px] mb-[20px] text-[#333]">
                    Manage Books
                </h2>

                <div class="overflow-x-auto">
                    <table class="w-full border-collapse text-[14px]">
                        <thead>
                            <tr class="bg-[#e0e0e0]">
                                <th class="px-[15px] py-[15px] text-left font-semibold text-[14px]">
                                    Book No./ISBN
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
                            @forelse ($books as $book)
                                <tr
                                    class="border-b border-[#e0e0e0]
                                           transition-colors duration-200
                                           hover:bg-[#f5f5f5]"
                                >
                                    <td class="px-[15px] py-[15px]">
                                        {{ $book->isbn ?? '-' }}
                                    </td>
                                    <td class="px-[15px] py-[15px]">
                                        {{ $book->title ?? 'Untitled' }}
                                    </td>
                                    <td class="px-[15px] py-[15px]">
                                        {{ $book->author ?? '-' }}
                                    </td>
                                    <td class="px-[15px] py-[15px]">
                                        {{ optional($book->category)->name ?? '-' }}
                                    </td>
                                    <td class="px-[15px] py-[15px]">
                                        {{ $book->year_published ?? '-' }}
                                    </td>
                                    <td class="px-[15px] py-[15px] space-x-2">
                                        {{-- View (opens placeholder modal for now) --}}
                                        @php
                                        $coverUrl = $book->cover_path
                                            ? asset('storage/' . $book->cover_path)
                                            : null;
                                    @endphp

                                    <button
                                        type="button"
                                        @click="bookViewOpen = true; selectedBook = {
                                            title: @js($book->title),
                                            id: {{ $book->id }},
                                            author: @js($book->author),
                                            publisher: @js($book->publisher),
                                            year_published: @js($book->year_published),
                                            isbn: @js($book->isbn),
                                            category: @js(optional($book->category)->name),
                                            total_pages: @js($book->total_pages),
                                            status: @js($book->status),
                                            cover_url: @js($coverUrl),
                                            description: @js($book->description),
                                            table_of_contents: @js($book->table_of_contents),
                                        }"
                                        class="inline-flex items-center justify-center
                                            text-[20px]
                                            w-8 h-8
                                            rounded-full
                                            bg-transparent border-0
                                            cursor-pointer
                                            transition-transform
                                            hover:scale-110"
                                        title="View Book"
                                    >
                                        👁️
                                    </button>


                                        {{-- Edit (go to edit page) --}}
                                        <a
                                            href="{{ route('admin.books.edit', $book) }}"
                                            class="inline-flex items-center justify-center
                                                   text-[20px]
                                                   w-8 h-8
                                                   rounded-full
                                                   cursor-pointer
                                                   transition-transform
                                                   hover:scale-110"
                                            title="Edit Book"
                                        >
                                            ✏️
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-[15px] py-[15px] text-center text-gray-500">
                                        No books found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if ($books instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    <div class="mt-[20px]">
                        {{ $books->links() }}
                    </div>
                @endif
            </div>
        </main>
    </div>

    {{-- Footer Logos --}}
    @include('admin.partials.footer-logos')

    {{-- Book View Modal (UI placeholder, not yet wired to specific book) --}}
    {{-- Book View Modal --}}
<div
    x-show="bookViewOpen"
    x-cloak
    @click.self="bookViewOpen = false"
    class="fixed inset-0 bg-black/50 z-[2000]
           flex items-center justify-center
           p-[20px] overflow-y-auto"
>
    <div
        @click.stop
        class="bg-white rounded-[20px] p-[40px]
               w-full max-w-[900px] max-h-[90vh]
               overflow-y-auto relative
               shadow-[0_10px_30px_rgba(0,0,0,0.25)]"
    >
        <button
            type="button"
            @click="bookViewOpen = false"
            class="absolute top-[20px] right-[20px]
                   bg-transparent border-0
                   text-[32px] text-[#666]
                   w-[40px] h-[40px]
                   flex items-center justify-center
                   rounded-full cursor-pointer
                   transition-all
                   hover:bg-[#f5f5f5] hover:text-[#333]"
        >
            ×
        </button>

        <template x-if="selectedBook">
            <div>
                <div class="flex flex-col md:flex-row gap-[30px] mb-[30px]">
                    {{-- Left: Book cover --}}
                    <div class="flex-shrink-0 flex justify-center md:justify-start">
                        <div
                            class="w-[200px] h-[280px]
                                   bg-[#f5f5f5]
                                   rounded-[12px]
                                   overflow-hidden
                                   shadow-[0_5px_15px_rgba(0,0,0,0.20)]
                                   flex items-center justify-center"
                        >
                            <template x-if="selectedBook.cover_url">
                                <img
                                    :src="selectedBook.cover_url"
                                    alt="Book Cover"
                                    class="w-full h-full object-cover"
                                >
                            </template>

                            <template x-if="!selectedBook.cover_url">
                                <span class="text-[40px]">📚</span>
                            </template>
                        </div>
                    </div>

                    {{-- Right: Book details --}}
                    <div class="flex-1">
                        <h2 class="text-[22px] mb-[15px]">
                            <span class="font-semibold">Book Title:</span>
                            <span x-text="selectedBook.title ?? 'Untitled'"></span>
                        </h2>

                        <p class="mb-[8px] text-[14px]">
                            <strong>Author:</strong>
                            <span x-text="selectedBook.author || '-'"></span>
                        </p>
                        <p class="mb-[8px] text-[14px]">
                            <strong>Publisher:</strong>
                            <span x-text="selectedBook.publisher || '-'"></span>
                        </p>
                        <p class="mb-[8px] text-[14px]">
                            <strong>Year Published:</strong>
                            <span x-text="selectedBook.year_published || '-'"></span>
                        </p>
                        <p class="mb-[8px] text-[14px]">
                            <strong>ISBN:</strong>
                            <span x-text="selectedBook.isbn || '-'"></span>
                        </p>
                        <p class="mb-[8px] text-[14px]">
                            <strong>Category:</strong>
                            <span x-text="selectedBook.category || '-'"></span>
                        </p>
                        <p class="mb-[8px] text-[14px]">
                            <strong>Total pages:</strong>
                            <span x-text="selectedBook.total_pages || '-'"></span>
                        </p>
                        <p class="mb-[8px] text-[14px]">
                            <strong>Status:</strong>
                            <span x-text="selectedBook.status || 'available'"></span>
                        </p>

                        <h3 class="text-[16px] font-bold mt-[20px] mb-[10px] text-[#2e7d32]">
                            Description:
                        </h3>
                        <p
                            class="text-[14px] text-[#555] leading-relaxed"
                            x-text="selectedBook.description || 'No description available.'"
                        ></p>

                        <h3 class="text-[16px] font-bold mt-[20px] mb-[10px] text-[#2e7d32]">
                            Table of Contents:
                        </h3>
                        <pre
                            class="text-[14px] text-[#555] whitespace-pre-wrap"
                            x-text="selectedBook.table_of_contents || 'No table of contents available.'"
                        ></pre>
                    </div>
                </div>
                    <div class="flex flex-col sm:flex-row gap-[12px] mt-[25px]">
    {{-- Close button --}}
                    <button
                        type="button"
                        @click="bookViewOpen = false"
                        class="flex-1 px-[20px] py-[14px]
                            rounded-[8px] border-0
                            text-[15px] font-semibold
                            bg-[#a5d6a7] text-black
                            cursor-pointer
                            transition-colors
                            hover:bg-[#81c784]"
                    >
                        Close
                    </button>

                    {{-- Delete form --}}
                    <form
                        x-show="selectedBook && selectedBook.id"
                        :action="`{{ url('/admin/books') }}/${selectedBook.id}`"
                        method="POST"
                        class="flex-1"
                        @submit.prevent="if (confirm('Delete this book? This cannot be undone.')) $el.submit()"
                    >
                        @csrf
                        @method('DELETE')

                        <button
                            type="submit"
                            class="w-full px-[20px] py-[14px]
                                rounded-[8px] border-0
                                text-[15px] font-semibold
                                bg-red-600 text-white
                                cursor-pointer
                                transition-colors
                                hover:bg-red-700"
                        >
                            Delete Book
                        </button>
                    </form>
                </div>

                
            </div>
        </template>
    </div>
</div>


    {{-- Add/Edit Book Modal --}}
    <div
        x-show="addBookOpen"
        x-cloak
        @click.self="addBookOpen = false"
        class="fixed inset-0 bg-black/50 z-[2000]
               flex items-center justify-center
               p-[20px] overflow-y-auto"
    >
        <div
            @click.stop
            class="bg-white rounded-[20px] p-[40px]
                   w-full max-w-[700px] max-h-[90vh]
                   overflow-y-auto relative
                   shadow-[0_10px_30px_rgba(0,0,0,0.25)]"
        >
            <button
                type="button"
                @click="addBookOpen = false"
                class="absolute top-[20px] right-[20px]
                       bg-transparent border-0
                       text-[32px] text-[#666]
                       w-[40px] h-[40px]
                       flex items-center justify-center
                       rounded-full cursor-pointer
                       transition-all
                       hover:bg-[#f5f5f5] hover:text-[#333]"
            >
                ×
            </button>

            <h2 class="text-[24px] mb-[25px] text-[#333]" id="modalTitle">
                Add New Book
            </h2>

            <form
                id="bookForm"
                class="flex flex-col gap-[20px]"
                method="POST"
                action="{{ route('admin.books.store') }}"
                enctype="multipart/form-data"
            >
                @csrf

                {{-- Cover upload --}}
                <div class="flex flex-col gap-[8px]">
                    <label class="font-semibold text-[14px] text-[#333]">
                        Book Cover:
                    </label>
                    <div class="flex flex-col items-center gap-[15px]">
                        <div
                            class="w-[150px] h-[200px]
                                   bg-[#f5f5f5]
                                   border-2 border-dashed border-[#ccc]
                                   rounded-[12px]
                                   flex flex-col items-center justify-center
                                   gap-[10px] overflow-hidden"
                        >
                            <template x-if="!coverPreview">
                                <div class="flex flex-col items-center gap-[10px]">
                                    <span class="text-[48px]">📚</span>
                                    <p class="text-[12px] text-[#666]">
                                        Upload Book Cover
                                    </p>
                                </div>
                            </template>

                            <template x-if="coverPreview">
                                <img
                                    :src="coverPreview"
                                    alt="Cover Preview"
                                    class="w-full h-full object-cover"
                                >
                            </template>
                        </div>

                        <input
                            type="file"
                            id="coverInput"
                            name="cover"
                            accept="image/*"
                            class="hidden"
                            @change="handleCoverChange($event)"
                            x-ref="coverInput"
                        />


                        <button
                            type="button"
                            class="px-[25px] py-[10px]
                                   bg-[#4caf50] text-white
                                   rounded-[8px]
                                   text-[14px] font-semibold
                                   border-0 cursor-pointer
                                   transition-colors
                                   hover:bg-[#45a049]"
                            @click="$refs.coverInput.click()"
                            x-ref="coverInputButton"
                        >
                            Choose Image
                        </button>
                    </div>
                </div>

                {{-- ISBN + Title --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-[20px]">
                    <div class="flex flex-col gap-[8px]">
                        <label class="font-semibold text-[14px] text-[#333]">
                            ISBN:
                        </label>
                        <input
                            type="text"
                            name="isbn"
                            class="px-[12px] py-[12px]
                                   border-2 border-[#a5d6a7]
                                   rounded-[8px]
                                   text-[15px]
                                   outline-none
                                   transition-colors
                                   focus:border-[#66bb6a]"
                            required
                        >
                    </div>
                    <div class="flex flex-col gap-[8px]">
                        <label class="font-semibold text-[14px] text-[#333]">
                            Title:
                        </label>
                        <input
                            type="text"
                            name="title"
                            class="px-[12px] py-[12px]
                                   border-2 border-[#a5d6a7]
                                   rounded-[8px]
                                   text-[15px]
                                   outline-none
                                   transition-colors
                                   focus:border-[#66bb6a]"
                            required
                        >
                    </div>
                </div>

                {{-- Author + Publisher --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-[20px]">
                    <div class="flex flex-col gap-[8px]">
                        <label class="font-semibold text-[14px] text-[#333]">
                            Author:
                        </label>
                        <input
                            type="text"
                            name="author"
                            class="px-[12px] py-[12px]
                                   border-2 border-[#a5d6a7]
                                   rounded-[8px]
                                   text-[15px]
                                   outline-none
                                   transition-colors
                                   focus:border-[#66bb6a]"
                            required
                        >
                    </div>
                    <div class="flex flex-col gap-[8px]">
                        <label class="font-semibold text-[14px] text-[#333]">
                            Publisher:
                        </label>
                        <input
                            type="text"
                            name="publisher"
                            class="px-[12px] py-[12px]
                                   border-2 border-[#a5d6a7]
                                   rounded-[8px]
                                   text-[15px]
                                   outline-none
                                   transition-colors
                                   focus:border-[#66bb6a]"
                            required
                        >
                    </div>
                </div>

                {{-- Year Published + Category --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-[20px]">
                        <div class="flex flex-col gap-[8px]">
                            <label class="font-semibold text-[14px] text-[#333]">
                                Year Published:
                            </label>
                            <input
                                type="text"
                                name="year_published"
                                class="px-[12px] py-[12px]
                                    border-2 border-[#a5d6a7]
                                    rounded-[8px]
                                    text-[15px]
                                    outline-none
                                    transition-colors
                                    focus:border-[#66bb6a]"
                                required
                            >
                        </div>

                        <div class="flex flex-col gap-[8px]">
                            <label class="font-semibold text-[14px] text-[#333]">
                                Category:
                            </label>

                            <select
                                name="category_id"
                                class="px-[12px] py-[12px]
                                    border-2 border-[#a5d6a7]
                                    rounded-[8px]
                                    text-[15px]
                                    outline-none
                                    transition-colors
                                    focus:border-[#66bb6a]"
                            >
                                <option value="">Choose category</option>
                                @foreach($categories ?? [] as $category)
                                    <option value="{{ $category->id }}">
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>

                            <p class="mt-1 text-xs text-gray-500">
                                Or add a new category:
                            </p>
                            <input
                                type="text"
                                name="new_category"
                                placeholder="e.g. Web Development"
                                class="px-[12px] py-[12px]
                                    border-2 border-[#a5d6a7]
                                    rounded-[8px]
                                    text-[15px]
                                    outline-none
                                    transition-colors
                                    focus:border-[#66bb6a]"
                            >
                        </div>
                    </div>


                {{-- Total Pages + Status --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-[20px]">
                    <div class="flex flex-col gap-[8px]">
                        <label class="font-semibold text-[14px] text-[#333]">
                            Total Pages:
                        </label>
                        <input
                            type="number"
                            name="total_pages"
                            class="px-[12px] py-[12px]
                                   border-2 border-[#a5d6a7]
                                   rounded-[8px]
                                   text-[15px]
                                   outline-none
                                   transition-colors
                                   focus:border-[#66bb6a]"
                            required
                        >
                    </div>
                    <div class="flex flex-col gap-[8px]">
                        <label class="font-semibold text-[14px] text-[#333]">
                            Status:
                        </label>
                        <select
                            name="status"
                            class="px-[12px] py-[12px]
                                   border-2 border-[#a5d6a7]
                                   rounded-[8px]
                                   text-[15px]
                                   outline-none
                                   transition-colors
                                   focus:border-[#66bb6a]"
                            required
                        >
                            <option value="available">Available</option>
                            <option value="unavailable">Unavailable</option>
                        </select>
                    </div>
                </div>

                {{-- Description --}}
                <div class="flex flex-col gap-[8px]">
                    <label class="font-semibold text-[14px] text-[#333]">
                        Description:
                    </label>
                    <textarea
                        name="description"
                        rows="4"
                        class="px-[12px] py-[12px]
                               border-2 border-[#a5d6a7]
                               rounded-[8px]
                               text-[14px]
                               resize-y
                               outline-none
                               transition-colors
                               focus:border-[#66bb6a]"
                        required
                    ></textarea>
                </div>

                {{-- Table of Contents --}}
                <div class="flex flex-col gap-[8px]">
                    <label class="font-semibold text-[14px] text-[#333]">
                        Table of Contents:
                    </label>
                    <textarea
                        name="table_of_contents"
                        rows="4"
                        placeholder="Chapter 1 - Introduction"
                        class="px-[12px] py-[12px]
                               border-2 border-[#a5d6a7]
                               rounded-[8px]
                               text-[14px]
                               resize-y
                               outline-none
                               transition-colors
                               focus:border-[#66bb6a]"
                    ></textarea>
                </div>

                {{-- Modal Actions --}}
                <div class="flex flex-col sm:flex-row gap-[12px] mt-[25px]">
                    <button
                        type="button"
                        @click="addBookOpen = false"
                        class="flex-1 px-[20px] py-[14px]
                               rounded-[8px] border-0
                               text-[15px] font-semibold
                               bg-[#a5d6a7] text-black
                               cursor-pointer
                               transition-colors
                               hover:bg-[#81c784]"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        class="flex-1 px-[20px] py-[14px]
                               rounded-[8px] border-0
                               text-[15px] font-semibold
                               bg-[#2e7d32] text-white
                               cursor-pointer
                               transition-colors
                               hover:bg-[#1b5e20]"
                    >
                        Save Book
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
