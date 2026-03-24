<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');

        $booksQuery = Book::query()->with('category');

        if ($q) {
            $booksQuery->where(function ($query) use ($q) {
                $query->where('title', 'like', "%{$q}%")
                      ->orWhere('author', 'like', "%{$q}%")
                      ->orWhere('isbn', 'like', "%{$q}%");
            });
        }

        $books = $booksQuery
            ->orderBy('title')
            ->paginate(12)
            ->withQueryString()
            ->through(fn($book) => [
                'id'                => $book->id,
                'title'             => $book->title,
                'author'            => $book->author,
                'publisher'         => $book->publisher,
                'year_published'    => $book->year_published,
                'isbn'              => $book->isbn,
                'category'          => optional($book->category)->name,
                'total_pages'       => $book->total_pages,
                'status'            => ucfirst($book->status ?? 'available'),
                'is_unavailable'    => strtolower($book->status ?? 'available') !== 'available',
                'description'       => $book->description,
                'table_of_contents' => $book->table_of_contents,
                'cover_url'         => $book->cover_path
                    ? asset('storage/' . $book->cover_path)
                    : null,
            ]);

        return Inertia::render('Student/Catalog', [
            'books'    => $books,
            'q'        => $q ?? '',
            'authName' => Auth::user()->name ?? 'Student',
        ]);
    }
}