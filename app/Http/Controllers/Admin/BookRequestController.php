<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class BookRequestController extends Controller
{
    /**
     * Main "Manage Requests" page (admin/requests.blade.php)
     * - Search across user, book, category, status
     * - Optional ?status=pending|processing|completed
     */
    public function index(Request $request)
    {
        $search = trim($request->input('q', ''));
        $status = $request->input('status');

        $query = BookRequest::with(['user', 'book.category']);

        // Optional status filter
        if ($status && in_array($status, ['pending', 'processing', 'completed'])) {
            $query->where('status', $status);
        }

        // Full search: user, book, category, status
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                // Student name / email
                $q->whereHas('user', function ($qUser) use ($search) {
                    $qUser->where('name', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%");
                });

                // Book title / author
                $q->orWhereHas('book', function ($qBook) use ($search) {
                    $qBook->where('title', 'like', "%{$search}%")
                          ->orWhere('author', 'like', "%{$search}%");
                });

                // Category name
                $q->orWhereHas('book.category', function ($qCat) use ($search) {
                    $qCat->where('name', 'like', "%{$search}%");
                });

                // Status
                $q->orWhere('status', 'like', "%{$search}%");
            });
        }

        $requests = $query->latest()->paginate(10)->withQueryString();

        return view('admin.requests', compact('requests'));
    }

    /**
     * Total Request page (admin/requests/total.blade.php)
     * All statuses, just a different UI.
     */
    public function total()
    {
        $requests = BookRequest::with(['user', 'book.category'])
            ->latest()
            ->paginate(15);

        return view('admin.requests.total', compact('requests'));
    }

    /**
     * Pending requests (admin/requests/pending.blade.php)
     */
    public function pending()
    {
        $requests = BookRequest::with(['user', 'book.category'])
            ->where('status', 'pending')
            ->latest()
            ->paginate(15);

        return view('admin.requests.pending', compact('requests'));
    }

    /**
     * Processing requests (admin/requests/processing.blade.php)
     */
    public function processing()
    {
        $requests = BookRequest::with(['user', 'book.category'])
            ->where('status', 'processing')
            ->latest()
            ->paginate(15);

        return view('admin.requests.processing', compact('requests'));
    }

    /**
     * Completed requests (admin/requests/completed.blade.php)
     */
    public function completed()
    {
        $requests = BookRequest::with(['user', 'book.category'])
            ->where('status', 'completed')
            ->latest()
            ->paginate(15);

        return view('admin.requests.completed', compact('requests'));
    }

    /**
     * Single request details page
     * (we'll build admin/requests/show.blade.php later if you want)
     */
    public function show(BookRequest $bookRequest)
{
    $bookRequest->load(['book.category', 'user']);

    $book = $bookRequest->book;

    $coverUrl = $book && $book->cover_path
        ? asset('storage/' . $book->cover_path)
        : asset('assets/images/laravel-book.png'); // fallback

    return view('admin.requests.show', [
        'request'  => $bookRequest, // nicer variable name for Blade
        'coverUrl' => $coverUrl,
    ]);
}


    /**
     * Update status / upload completed file / set expiration.
     *
     * Your BookRequest model fields:
     * - status
     * - completed_file   (string | nullable)
     * - prepared_by      (string | nullable)
     * - expiration_at    (datetime | nullable)
     */
    public function updateStatus(Request $request, BookRequest $bookRequest)
{
    $validated = $request->validate([
        'status'        => ['required', 'in:pending,processing,completed,expired'],
        'file'          => ['nullable', 'file', 'max:10240'], // 10MB
        'expiration_at' => ['nullable', 'date'],              // <-- use expiration_at
        'note'          => ['nullable', 'string'],            // optional, if you want to save notes
    ]);

    // Handle file upload (use completed_file column from your model)
    if ($request->hasFile('file')) {
        // delete old file if exists
        if ($bookRequest->completed_file && Storage::disk('public')->exists($bookRequest->completed_file)) {
            Storage::disk('public')->delete($bookRequest->completed_file);
        }

        $path = $request->file('file')->store('request_files', 'public');
        $bookRequest->completed_file = $path;
    }

    $bookRequest->status      = $validated['status'];
    $bookRequest->prepared_by = Auth::user()->name ?? 'Admin';

    // Save expiration date to the correct column
    if (!empty($validated['expiration_at'])) {
        $bookRequest->expiration_at = Carbon::parse($validated['expiration_at']);
    } else {
        $bookRequest->expiration_at = null; // optional: clear if empty
    }

    // Optional: save admin note if you’re using that column
    if (array_key_exists('note', $validated)) {
        $bookRequest->note = $validated['note'];
    }

    $bookRequest->save();

    return back()->with('status', 'Request updated successfully.');
}
}
