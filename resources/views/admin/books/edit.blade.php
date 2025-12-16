{{-- resources/views/admin/books/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Book - SNSU Library E-Request')

@section('content')
<div class="min-h-screen bg-[#e8e8e8]">
    {{-- Navbar --}}
    @include('admin.partials.navbar')

    <div class="flex min-h-[calc(100vh-60px)]">
        {{-- Sidebar --}}
        @include('admin.partials.sidebar')

        {{-- Main --}}
        <main class="flex-1 px-[30px] py-[30px] bg-[#f5f5f5]">
            <div class="mb-[20px] flex items-center justify-between">
                <h1 class="text-[28px] font-semibold text-[#333]">
                    Edit Book
                </h1>

                <a
                    href="{{ route('admin.books.index') }}"
                    class="px-[16px] py-[8px] rounded-[8px]
                           bg-[#a5d6a7] text-[#1b5e20] text-[14px] font-semibold
                           hover:bg-[#81c784] transition-colors"
                >
                    ← Back to Books
                </a>
            </div>

            {{-- Flash status --}}
            @if(session('status'))
                <div class="mb-[15px] px-4 py-2 rounded-[10px] bg-green-100 text-green-800 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Validation errors --}}
            @if ($errors->any())
                <div class="mb-[15px] px-4 py-3 rounded-[10px] bg-red-100 text-red-700 text-sm">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white rounded-[20px] p-[30px] shadow-[0_5px_20px_rgba(0,0,0,0.10)]">
                <form
                    method="POST"
                    action="{{ route('admin.books.update', $book) }}"
                    class="space-y-6"
                    enctype="multipart/form-data"
                >
                    @csrf
                    @method('PUT')

                    {{-- Cover preview + upload --}}
                    <div class="grid grid-cols-1 md:grid-cols-[auto,1fr] gap-[24px] mb-[20px]">
                        {{-- Preview box --}}
                        <div class="flex justify-center md:justify-start">
                            <div
                                class="w-[150px] h-[200px]
                                       bg-[#f5f5f5]
                                       border border-gray-300
                                       rounded-[12px]
                                       overflow-hidden
                                       flex items-center justify-center"
                            >
                                @if($book->cover_path)
                                    <img
                                        src="{{ asset('storage/' . $book->cover_path) }}"
                                        alt="Book Cover"
                                        class="w-full h-full object-cover"
                                    >
                                @else
                                    <span class="text-3xl">📚</span>
                                @endif
                            </div>
                        </div>

                        {{-- Upload field --}}
                        <div class="flex flex-col gap-[8px]">
                            <label class="font-semibold text-[14px] text-[#333]">
                                Book Cover:
                            </label>
                            <input
                                type="file"
                                name="cover"
                                accept="image/*"
                                class="px-[12px] py-[12px]
                                       border-2 border-[#a5d6a7]
                                       rounded-[8px] text-[15px]
                                       outline-none focus:border-[#66bb6a]"
                            >
                            <p class="text-xs text-gray-500">
                                Optional. JPG, PNG, WEBP up to 2MB. Leave empty to keep current cover.
                            </p>
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
                                value="{{ old('isbn', $book->isbn) }}"
                                class="px-[12px] py-[12px]
                                       border-2 border-[#a5d6a7]
                                       rounded-[8px] text-[15px]
                                       outline-none focus:border-[#66bb6a]"
                            >
                        </div>

                        <div class="flex flex-col gap-[8px]">
                            <label class="font-semibold text-[14px] text-[#333]">
                                Title:
                            </label>
                            <input
                                type="text"
                                name="title"
                                value="{{ old('title', $book->title) }}"
                                class="px-[12px] py-[12px]
                                       border-2 border-[#a5d6a7]
                                       rounded-[8px] text-[15px]
                                       outline-none focus:border-[#66bb6a]"
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
                                value="{{ old('author', $book->author) }}"
                                class="px-[12px] py-[12px]
                                       border-2 border-[#a5d6a7]
                                       rounded-[8px] text-[15px]
                                       outline-none focus:border-[#66bb6a]"
                            >
                        </div>

                        <div class="flex flex-col gap-[8px]">
                            <label class="font-semibold text-[14px] text-[#333]">
                                Publisher:
                            </label>
                            <input
                                type="text"
                                name="publisher"
                                value="{{ old('publisher', $book->publisher) }}"
                                class="px-[12px] py-[12px]
                                       border-2 border-[#a5d6a7]
                                       rounded-[8px] text-[15px]
                                       outline-none focus:border-[#66bb6a]"
                            >
                        </div>
                    </div>

                    {{-- Year + Category --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-[20px]">
                        <div class="flex flex-col gap-[8px]">
                            <label class="font-semibold text-[14px] text-[#333]">
                                Year Published:
                            </label>
                            <input
                                type="text"
                                name="year_published"
                                value="{{ old('year_published', $book->year_published) }}"
                                class="px-[12px] py-[12px]
                                       border-2 border-[#a5d6a7]
                                       rounded-[8px] text-[15px]
                                       outline-none focus:border-[#66bb6a]"
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
                                       rounded-[8px] text-[15px]
                                       outline-none focus:border-[#66bb6a]"
                            >
                                <option value="">No category</option>
                                @foreach($categories as $category)
                                    <option
                                        value="{{ $category->id }}"
                                        @selected(old('category_id', $book->category_id) == $category->id)
                                    >
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
                                       rounded-[8px] text-[15px]
                                       outline-none focus:border-[#66bb6a]"
                                value="{{ old('new_category') }}"
                            >
                        </div>
                    </div>

                    {{-- Total pages + Status --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-[20px]">
                        <div class="flex flex-col gap-[8px]">
                            <label class="font-semibold text-[14px] text-[#333]">
                                Total Pages:
                            </label>
                            <input
                                type="number"
                                name="total_pages"
                                value="{{ old('total_pages', $book->total_pages) }}"
                                class="px-[12px] py-[12px]
                                       border-2 border-[#a5d6a7]
                                       rounded-[8px] text-[15px]
                                       outline-none focus:border-[#66bb6a]"
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
                                       rounded-[8px] text-[15px]
                                       outline-none focus:border-[#66bb6a]"
                                required
                            >
                                <option value="available"
                                    @selected(old('status', $book->status) === 'available')>
                                    Available
                                </option>
                                <option value="unavailable"
                                    @selected(old('status', $book->status) === 'unavailable')>
                                    Unavailable
                                </option>
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
                                   text-[14px] resize-y
                                   outline-none focus:border-[#66bb6a]"
                        >{{ old('description', $book->description) }}</textarea>
                    </div>

                    {{-- TOC --}}
                    <div class="flex flex-col gap-[8px]">
                        <label class="font-semibold text-[14px] text-[#333]">
                            Table of Contents:
                        </label>
                        <textarea
                            name="table_of_contents"
                            rows="4"
                            class="px-[12px] py-[12px]
                                   border-2 border-[#a5d6a7]
                                   rounded-[8px]
                                   text-[14px] resize-y
                                   outline-none focus:border-[#66bb6a]"
                            placeholder="Chapter 1 - Introduction"
                        >{{ old('table_of_contents', $book->table_of_contents) }}</textarea>
                    </div>

                    {{-- Actions --}}
                    <div class="flex flex-col sm:flex-row gap-[12px] mt-[20px]">
                        <a
                            href="{{ route('admin.books.index') }}"
                            class="flex-1 text-center px-[20px] py-[14px]
                                   rounded-[8px]
                                   bg-[#a5d6a7] text-[#1b5e20]
                                   text-[15px] font-semibold
                                   hover:bg-[#81c784] transition-colors"
                        >
                            Cancel
                        </a>

                        <button
                            type="submit"
                            class="flex-1 px-[20px] py-[14px]
                                   rounded-[8px] border-0
                                   text-[15px] font-semibold
                                   bg-[#2e7d32] text-white
                                   hover:bg-[#1b5e20] transition-colors"
                        >
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    @include('admin.partials.footer-logos')
</div>
@endsection
