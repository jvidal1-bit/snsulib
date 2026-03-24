<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = trim($request->input('q', ''));
        $query = Book::with('category');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                ->orWhere('author', 'like', "%{$search}%")
                ->orWhere('isbn', 'like', "%{$search}%");
            });
        }

        $books = $query->orderBy('title')->paginate(15)->withQueryString()
            ->through(fn($book) => [
                'id'                => $book->id,
                'isbn'              => $book->isbn,
                'title'             => $book->title,
                'author'            => $book->author,
                'publisher'         => $book->publisher,
                'year_published'    => $book->year_published,
                'category'          => optional($book->category)->name,
                'category_id'       => $book->category_id,
                'total_pages'       => $book->total_pages,
                'status'            => $book->status,
                'description'       => $book->description,
                'table_of_contents' => $book->table_of_contents,
                'cover_url'         => $book->cover_path
                    ? asset('storage/' . $book->cover_path)
                    : null,
            ]);

        $categories = Category::orderBy('name')->get(['id', 'name']);

        return Inertia::render('Admin/Books', [
            'books'      => $books,
            'categories' => $categories,
            'q'          => $search,
            'authName'   => Auth::user()->name ?? 'Admin',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * (Not used because we use the modal on index page.)
     */
    public function create()
    {
        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $validated = $request->validate([
        'isbn'              => ['nullable', 'string', 'max:255', 'unique:books,isbn'],
        'title'             => ['required', 'string', 'max:255'],
        'author'            => ['nullable', 'string', 'max:255'],
        'publisher'         => ['nullable', 'string', 'max:255'],
        'year_published'    => ['nullable', 'string', 'max:10'],
        'category_id'       => ['nullable', 'exists:categories,id'],
        'new_category'      => ['nullable', 'string', 'max:255'],   // ✅ NEW
        'total_pages'       => ['nullable', 'integer', 'min:1'],
        'status'            => ['required', 'in:available,unavailable'],
        'description'       => ['nullable', 'string'],
        'table_of_contents' => ['nullable', 'string'],
        'cover'             => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
    ]);

         // Handle cover upload
    if ($request->hasFile('cover')) {
        $path = $request->file('cover')->store('book_covers', 'public');
        $validated['cover_path'] = $path;
    }
    // ✅ require at least one of dropdown or new_category
    if (! $request->category_id && ! $request->filled('new_category')) {
        return back()
            ->withErrors('Please choose a category or enter a new one.')
            ->withInput();
    }

    // ✅ resolve category id
    $categoryId = $request->category_id;

    if ($request->filled('new_category')) {
        $category = Category::firstOrCreate([
            'name' => trim($request->new_category),
        ]);
        $categoryId = $category->id;
    }

    $validated['category_id'] = $categoryId;

    Book::create($validated);

    return redirect()
        ->route('admin.books.index')
        ->with('status', 'Book created successfully.');
}

    /**
     * Display the specified resource.
     * (Optional – we’ll just use edit & modal for now.)
     */
    public function show(Book $book)
    {
        return redirect()->route('admin.books.edit', $book);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        $categories = Category::orderBy('name')->get(['id', 'name']);

        return Inertia::render('Admin/BooksEdit', [
            'book' => [
                'id'                => $book->id,
                'isbn'              => $book->isbn,
                'title'             => $book->title,
                'author'            => $book->author,
                'publisher'         => $book->publisher,
                'year_published'    => $book->year_published,
                'category_id'       => $book->category_id,
                'total_pages'       => $book->total_pages,
                'status'            => $book->status,
                'description'       => $book->description,
                'table_of_contents' => $book->table_of_contents,
                'cover_url'         => $book->cover_path
                    ? asset('storage/' . $book->cover_path)
                    : null,
            ],
            'categories' => $categories,
            'authName'   => Auth::user()->name ?? 'Admin',
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
{
    $validated = $request->validate([
        'isbn'              => ['nullable', 'string', 'max:255', 'unique:books,isbn,' . $book->id],
        'title'             => ['required', 'string', 'max:255'],
        'author'            => ['nullable', 'string', 'max:255'],
        'publisher'         => ['nullable', 'string', 'max:255'],
        'year_published'    => ['nullable', 'string', 'max:10'],
        'category_id'       => ['nullable', 'exists:categories,id'],
        'new_category'      => ['nullable', 'string', 'max:255'],   // ✅ NEW
        'total_pages'       => ['nullable', 'integer', 'min:1'],
        'status'            => ['required', 'in:available,unavailable'],
        'description'       => ['nullable', 'string'],
        'table_of_contents' => ['nullable', 'string'],
        'cover'             => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
    ]);

        if ($request->hasFile('cover')) {
        // delete old
        if ($book->cover_path && Storage::disk('public')->exists($book->cover_path)) {
            Storage::disk('public')->delete($book->cover_path);
        }

        $path = $request->file('cover')->store('book_covers', 'public');
        $validated['cover_path'] = $path;
    }

    $book->update($validated);

    return redirect()
        ->route('admin.books.index')
        ->with('status', 'Book updated successfully.');


    // ✅ require at least one of dropdown or new_category
    if (! $request->category_id && ! $request->filled('new_category')) {
        return back()
            ->withErrors('Please choose a category or enter a new one.')
            ->withInput();
    }

    // ✅ resolve category id
    $categoryId = $request->category_id;

    if ($request->filled('new_category')) {
        $category = Category::firstOrCreate([
            'name' => trim($request->new_category),
        ]);
        $categoryId = $category->id;
    }

    $validated['category_id'] = $categoryId;

    $book->update($validated);

    return redirect()
        ->route('admin.books.index')
        ->with('status', 'Book updated successfully.');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        $book->delete();

        return redirect()
            ->route('admin.books.index')
            ->with('status', 'Book deleted successfully.');
    }
}