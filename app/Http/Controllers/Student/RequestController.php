<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

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
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn($r) => [
                'id'             => $r->id,
                'isbn'           => $r->book->isbn ?? '—',
                'title'          => $r->book->title ?? 'Unknown Title',
                'chapter'        => $r->chapter ?? '—',
                'status'         => $r->status,
                'date'           => optional($r->created_at)->format('m/d/y'),
                'purpose'        => $r->purpose,
                'note'           => $r->note,
                'needed_by'      => optional($r->needed_by)->format('m/d/Y'),
                'created_at'     => optional($r->created_at)->setTimezone('Asia/Manila')->format('m/d/Y H:i'),
                'prepared_by'    => $r->prepared_by,
                'expiration_at'  => optional($r->expiration_at)->format('m/d/Y H:i'),
                'completed_file' => $r->completed_file ? asset('storage/' . $r->completed_file) : null,
                'expired'        => $r->expiration_at && $r->expiration_at->isPast(),
            ]);

        return Inertia::render('Student/Requests', [
            'requests' => $requests,
            'authName' => Auth::user()->name ?? 'Student',
        ]);
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
