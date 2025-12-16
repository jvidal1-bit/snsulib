<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    public function store(Request $request)
    {
        $studentId = Auth::id() ?? 1;

        $validated = $request->validate([
            'book_id'   => ['required', 'exists:books,id'],
            'chapter'   => ['required', 'string', 'max:255'],
            'purpose'   => ['required', 'string', 'max:255'],
            'needed_by' => ['required', 'date', 'after_or_equal:today'],
            'note'      => ['nullable', 'string', 'max:255'],
        ]);

        // 🔒 Extra safety: do not allow requests for unavailable books
        $book = Book::findOrFail($validated['book_id']);

        $statusValue = strtolower($book->status ?? 'available');
        if ($statusValue !== 'available') {
            return back()
                ->withErrors(['book' => 'This book is currently unavailable for requests.'])
                ->withInput();
        }

        $validated['user_id'] = $studentId;
        $validated['status']  = 'pending';

        BookRequest::create($validated);

        return redirect()
            ->route('student.catalog')
            ->with('status', 'Request submitted successfully!');
    }

    public function index()
    {
        $studentId = Auth::id() ?? 1;

        $requests = BookRequest::with('book')
            ->where('user_id', $studentId)
            ->orderBy('created_at', 'desc') // latest first for overview
            ->get();

        return view('student.requests.index', compact('requests'));
    }

    public function pending()
    {
        $studentId = Auth::id() ?? 1;

        $requests = BookRequest::with('book')
            ->where('user_id', $studentId)
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc') // oldest → latest, per client note
            ->get();

        return view('student.requests.pending', compact('requests'));
    }

    public function completed()
    {
        $studentId = Auth::id() ?? 1;

        $requests = BookRequest::with('book')
            ->where('user_id', $studentId)
            ->where('status', 'completed')
            ->orderBy('created_at', 'asc') // oldest → latest
            ->get();

        return view('student.requests.completed', compact('requests'));
    }

    public function processing()
    {
        $studentId = Auth::id() ?? 1;

        $requests = BookRequest::with('book')
            ->where('user_id', $studentId)
            ->where('status', 'processing')
            ->orderBy('created_at', 'asc') // oldest → latest
            ->get();

        return view('student.requests.processing', compact('requests'));
    }

    public function cancel(BookRequest $bookRequest)
    {
        $studentId = Auth::id() ?? 1;

        if ($bookRequest->user_id !== $studentId) {
            abort(403);
        }

        if ($bookRequest->status !== 'pending') {
            return back()->withErrors('Only pending requests can be cancelled.');
        }

        // For now: delete. Later we can add a "cancelled" status if needed.
        $bookRequest->delete();

        return back()->with('status', 'Request cancelled successfully.');
    }
}
