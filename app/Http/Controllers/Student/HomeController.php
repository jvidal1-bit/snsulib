<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\BookRequest;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        $studentId = Auth::id() ?? 1;

        $baseQuery = BookRequest::with('book')
            ->where('user_id', $studentId);

        $stats = [
            'total'      => (clone $baseQuery)->count(),
            'pending'    => (clone $baseQuery)->where('status', 'pending')->count(),
            'processing' => (clone $baseQuery)->where('status', 'processing')->count(),
            'completed'  => (clone $baseQuery)->where('status', 'completed')->count(),
        ];

        $recentRequests = (clone $baseQuery)
            ->latest()
            ->take(10)
            ->get()
            ->map(fn($r) => [
                'id'      => $r->id,
                'isbn'    => $r->book->isbn ?? '—',
                'title'   => $r->book->title ?? 'Unknown Title',
                'chapter' => $r->chapter ?? '—',
                'status'  => $r->status,
                'date'    => optional($r->created_at)->format('m/d/y'),
            ]);

        return Inertia::render('Student/Home', [
            'stats'          => $stats,
            'recentRequests' => $recentRequests,
            'authName'       => Auth::user()->name ?? 'Student',
        ]);
    }
}