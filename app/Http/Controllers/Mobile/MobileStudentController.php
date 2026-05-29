<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BookRequest;
use App\Models\Book;

class MobileStudentController extends Controller
{
    public function home(Request $request)
    {
        $user = $request->user();

        $stats = [
            'total'      => BookRequest::where('user_id', $user->id)->count(),
            'pending'    => BookRequest::where('user_id', $user->id)->where('status', 'pending')->count(),
            'processing' => BookRequest::where('user_id', $user->id)->where('status', 'processing')->count(),
            'completed'  => BookRequest::where('user_id', $user->id)->where('status', 'completed')->count(),
        ];

        $recent = BookRequest::where('user_id', $user->id)
            ->with('book')
            ->latest()
            ->take(5)
            ->get()
            ->map(fn($r) => [
                'id'      => $r->id,
                'isbn'    => $r->book->isbn    ?? '—',
                'title'   => $r->book->title   ?? '—',
                'chapter' => $r->chapter       ?? '—',
                'status'  => ucfirst($r->status),
                'date'    => $r->created_at->format('m/d/y'),
            ]);

        return response()->json(['stats' => $stats, 'recent' => $recent]);
    }

    public function catalog(Request $request)
    {
        $q = $request->query('q', '');

        $books = Book::when($q, fn($query) =>
                $query->where('title', 'like', "%$q%")
                      ->orWhere('author', 'like', "%$q%")
                      ->orWhere('isbn', 'like', "%$q%")
            )
            ->get()
            ->map(fn($b) => [
                'id'               => $b->id,
                'isbn'             => $b->isbn,
                'title'            => $b->title,
                'author'           => $b->author,
                'publisher'        => $b->publisher,
                'year_published'   => $b->year_published,
                'category'         => is_object($b->category) ? $b->category->name : $b->category,
                'total_pages'      => $b->total_pages,
                'description'      => $b->description,
                'table_of_contents'=> $b->table_of_contents,
                'status'           => $b->status ?? 'available',
                'is_unavailable'   => ($b->status === 'unavailable'),
                'cover_url'        => $b->cover_path
                    ? url('storage/' . $b->cover_path)
                    : null,
            ]);

        return response()->json(['books' => $books]);
    }

    public function requests(Request $request)
    {
        $user   = $request->user();
        $status = $request->query('status', '');

        $requests = BookRequest::where('user_id', $user->id)
            ->when($status, fn($q) => $q->where('status', $status))
            ->with('book')
            ->latest()
            ->get()
            ->map(fn($r) => [
                'id'      => $r->id,
                'isbn'    => $r->book->isbn  ?? '—',
                'title'   => $r->book->title ?? '—',
                'chapter' => $r->chapter,
                'status'  => ucfirst($r->status),
                'date'    => $r->created_at->format('m/d/y'),
            ]);

        return response()->json(['requests' => $requests]);
    }

    public function storeRequest(Request $request)
    {
        $validated = $request->validate([
            'book_id'   => 'required|exists:books,id',
            'chapter'   => 'required|string',
            'purpose'   => 'required|string',
            'needed_by' => 'required|string',
            'note'      => 'nullable|string',
        ]);

        BookRequest::create([
            'user_id'   => $request->user()->id,
            'book_id'   => $validated['book_id'],
            'chapter'   => $validated['chapter'],
            'purpose'   => $validated['purpose'],
            'needed_by' => $validated['needed_by'],
            'note'      => $validated['note'] ?? null,
            'status'    => 'pending',
        ]);

        return response()->json(['message' => 'Request submitted successfully.']);
    }

    public function showRequest(Request $request, $id)
    {
    $user = $request->user();

    $req = \App\Models\BookRequest::with('book')
        ->where('id', $id)
        ->where('user_id', $user->id)
        ->firstOrFail();

    $isExpired = $req->expiration_at
        && \Carbon\Carbon::parse($req->expiration_at)->isPast();

    $fileUrl = null;
    if ($req->completed_file && !$isExpired) {
        $fileUrl = url('storage/' . $req->completed_file);
    }

        return response()->json([
            'request' => [
                'id'           => $req->id,
                'isbn'         => $req->book->isbn         ?? '—',
                'title'        => $req->book->title        ?? 'Unknown Title',
                'chapter'      => $req->chapter,
                'status'       => ucfirst($req->status),
                'purpose'      => $req->purpose,
                'note'         => $req->note,
                'needed_by'    => $req->needed_by
                    ? \Carbon\Carbon::parse($req->needed_by)->format('m/d/Y')
                    : '—',
                'requested_on' => $req->created_at->format('m/d/Y H:i'),
                'prepared_by'  => $req->prepared_by   ?? null,
                'expiration_at'=> $req->expiration_at
                    ? \Carbon\Carbon::parse($req->expiration_at)->format('m/d/Y H:i')
                    : null,
                'is_expired'   => $isExpired,
                'file_url'     => $fileUrl,
            ]
        ]);
    }

    public function profile(Request $request)
    {
    $user    = $request->user();
    $profile = $user->profile ?? null;

    $nameParts  = explode(' ', $user->name, 2);
    $firstName  = $nameParts[0] ?? '';
    $lastName   = $nameParts[1] ?? '';

        return response()->json([
            'user' => [
                'name'       => $user->name,
                'email'      => $user->email,
                'avatar_url' => $user->avatar_path
                    ? url('storage/' . $user->avatar_path)
                    : null,
            ],
            'profile' => [
                'student_id' => $profile?->student_id ?? '',
                'first_name' => $profile?->first_name  ?? $firstName,
                'last_name'  => $profile?->last_name   ?? $lastName,
                'phone'      => $profile?->phone        ?? '',
                'course'     => $profile?->course       ?? '',
                'year_level' => $profile?->year_level   ?? '',
                'address'    => $profile?->address      ?? '',
            ],
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'student_id' => 'nullable|string|max:255',
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone'      => 'nullable|string|max:50',
            'course'     => 'nullable|string|max:255',
            'year_level' => 'nullable|string|max:50',
            'address'    => 'nullable|string',
        ]);

        $user->email = $validated['email'];
        $user->name  = trim($validated['first_name'] . ' ' . $validated['last_name']);
        $user->save();

        $profile = $user->profile ?? new \App\Models\StudentProfile(['user_id' => $user->id]);
        $profile->fill([
            'student_id' => $validated['student_id'] ?? null,
            'first_name' => $validated['first_name'],
            'last_name'  => $validated['last_name'],
            'phone'      => $validated['phone']      ?? null,
            'course'     => $validated['course']     ?? null,
            'year_level' => $validated['year_level'] ?? null,
            'address'    => $validated['address']    ?? null,
        ]);
        $profile->save();

        return response()->json(['message' => 'Profile updated successfully.']);
    }

    public function updatePassword(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'current_password'      => 'required',
            'new_password'          => 'required|string|min:8',
            'new_password_confirm'  => 'required|same:new_password',
        ]);

        if (!\Illuminate\Support\Facades\Hash::check($validated['current_password'], $user->password)) {
            return response()->json(['message' => 'Current password is incorrect.'], 422);
        }

        $user->password = \Illuminate\Support\Facades\Hash::make($validated['new_password']);
        $user->save();

        return response()->json(['message' => 'Password changed successfully.']);
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|max:2048',
        ]);

        $user = $request->user();

        // Delete old avatar if exists
        if ($user->avatar_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->avatar_path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar_path);
        }

        $path = $request->file('avatar')->store('avatars', 'public');
        $user->avatar_path = $path;
        $user->save();

        return response()->json([
            'message'    => 'Avatar updated successfully.',
            'avatar_url' => url('storage/' . $path),
        ]);
    }

}