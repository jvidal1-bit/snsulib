<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Carbon\Carbon;

class BookRequestController extends Controller
{
    private function mapRequest($req): array
    {
        return [
            'id'           => $req->id,
            'isbn'         => $req->book->isbn ?? '-',
            'title'        => $req->book->title ?? 'Unknown Book',
            'chapter'      => $req->chapter ?? '-',
            'status'       => $req->status ?? '-',
            'date'         => optional($req->created_at)->format('m/d/Y') ?? '-',
            'student'      => $req->user->name ?? 'Unknown',
            'author'       => $req->book->author ?? '-',
            'category'     => $req->book->category->name ?? '-',
            'year_published' => $req->book->year_published ?? '-',
            'cover_url'    => $req->book?->cover_path
                ? asset('storage/' . $req->book->cover_path)
                : null,
            'has_book'     => $req->book !== null,
        ];
    }

    public function index(Request $request)
    {
        $search = trim($request->input('q', ''));
        $query  = BookRequest::with(['user', 'book.category']);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%"))
                  ->orWhereHas('book', fn($b) => $b->where('title', 'like', "%{$search}%")
                    ->orWhere('author', 'like', "%{$search}%"))
                  ->orWhere('status', 'like', "%{$search}%");
            });
        }

        $requests = $query->latest()->paginate(10)->withQueryString()
            ->through(fn($req) => $this->mapRequest($req));

        return Inertia::render('Admin/Requests/Index', [
            'requests' => $requests,
            'q'        => $search,
            'authName' => Auth::user()->name ?? 'Admin',
        ]);
    }

    public function total()
    {
        $requests = BookRequest::with(['user', 'book.category'])
            ->latest()->paginate(15)
            ->through(fn($req) => $this->mapRequest($req));

        return Inertia::render('Admin/Requests/Total', [
            'requests' => $requests,
            'authName' => Auth::user()->name ?? 'Admin',
        ]);
    }

    public function pending()
    {
        $requests = BookRequest::with(['user', 'book.category'])
            ->where('status', 'pending')
            ->latest()->paginate(15)
            ->through(fn($req) => $this->mapRequest($req));

        return Inertia::render('Admin/Requests/Pending', [
            'requests' => $requests,
            'authName' => Auth::user()->name ?? 'Admin',
        ]);
    }

    public function processing()
    {
        $requests = BookRequest::with(['user', 'book.category'])
            ->where('status', 'processing')
            ->latest()->paginate(15)
            ->through(fn($req) => $this->mapRequest($req));

        return Inertia::render('Admin/Requests/Processing', [
            'requests' => $requests,
            'authName' => Auth::user()->name ?? 'Admin',
        ]);
    }

    public function completed()
    {
        $requests = BookRequest::with(['user', 'book.category'])
            ->where('status', 'completed')
            ->latest()->paginate(15)
            ->through(fn($req) => $this->mapRequest($req));

        return Inertia::render('Admin/Requests/Completed', [
            'requests' => $requests,
            'authName' => Auth::user()->name ?? 'Admin',
        ]);
    }

    public function show(BookRequest $bookRequest)
    {
        $bookRequest->load(['book.category', 'user']);
        $book = $bookRequest->book;

        return Inertia::render('Admin/Requests/Show', [
            'bookRequest' => [
                'id'             => $bookRequest->id,
                'status'         => $bookRequest->status,
                'chapter'        => $bookRequest->chapter,
                'purpose'        => $bookRequest->purpose,
                'note'           => $bookRequest->note ?? '',
                'needed_by'      => optional($bookRequest->needed_by)->format('m/d/Y'),
                'submitted_at'   => optional($bookRequest->created_at)->format('m/d/Y - g:i A'),
                'expiration_at_raw' => optional($bookRequest->expiration_at)->format('Y-m-d'),
                'completed_file' => $bookRequest->completed_file
                    ? asset('storage/' . $bookRequest->completed_file)
                    : null,
                'student_name'   => $bookRequest->user->name ?? 'Unknown',
                'student_email'  => $bookRequest->user->email ?? 'N/A',
                'book_title'     => $book->title ?? 'Unknown Book',
                'book_author'    => $book->author ?? 'N/A',
                'book_category'  => $book->category->name ?? 'N/A',
                'book_year'      => $book->year_published ?? 'N/A',
                'book_isbn'      => $book->isbn ?? 'N/A',
                'cover_url'      => $book?->cover_path
                    ? asset('storage/' . $book->cover_path)
                    : null,
            ],
            'authName' => Auth::user()->name ?? 'Admin',
        ]);
    }

    public function updateStatus(Request $request, BookRequest $bookRequest)
    {
        $validated = $request->validate([
            'status'        => ['required', 'in:pending,processing,completed,expired'],
            'file'          => ['nullable', 'file', 'max:10240'],
            'expiration_at' => ['nullable', 'date'],
            'note'          => ['nullable', 'string'],
        ]);

        if ($request->hasFile('file')) {
            if ($bookRequest->completed_file && Storage::disk('public')->exists($bookRequest->completed_file)) {
                Storage::disk('public')->delete($bookRequest->completed_file);
            }
            $path = $request->file('file')->store('request_files', 'public');
            $bookRequest->completed_file = $path;
        }

        $bookRequest->status      = $validated['status'];
        $bookRequest->prepared_by = Auth::user()->name ?? 'Admin';

        if (!empty($validated['expiration_at'])) {
            $bookRequest->expiration_at = Carbon::parse($validated['expiration_at']);
        } else {
            $bookRequest->expiration_at = null;
        }

        if (array_key_exists('note', $validated)) {
            $bookRequest->note = $validated['note'];
        }

        $bookRequest->save();

        return back()->with('status', 'Request updated successfully.');
    }
}