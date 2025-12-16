<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book; // we'll create this model + migration later

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
            ->withQueryString();

        return view('student.catalog', compact('books', 'q'));
    }
}
