<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\BookRequest;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        // TODO later: use Auth::id() once login is done
        $studentId = Auth::id() ?? 1; // temporary test user

        $baseQuery = BookRequest::with('book')
            ->where('user_id', $studentId);

        $stats = [
            'total'      => (clone $baseQuery)->count(),
            'pending'    => (clone $baseQuery)->where('status', 'pending')->count(),
            'processing' => (clone $baseQuery)->where('status', 'processing')->count(),
            'completed'  => (clone $baseQuery)->where('status', 'completed')->count(),
        ];

        // Latest 10 requests (any status)
        $recentRequests = (clone $baseQuery)
            ->latest()
            ->take(10)
            ->get();

        return view('student.home', [
            'stats'          => $stats,
            'recentRequests' => $recentRequests,
        ]);
    }
}
