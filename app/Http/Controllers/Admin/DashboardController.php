<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookRequest;

class DashboardController extends Controller
{
    public function index()
    {
        // Counts
        $totalRequests      = BookRequest::count();
        $pendingRequests    = BookRequest::where('status', 'pending')->count();
        $processingRequests = BookRequest::where('status', 'processing')->count();
        $completedRequests  = BookRequest::where('status', 'completed')->count();

        // Recent requests (latest 10, adjust as needed)
        $recentRequests = BookRequest::with(['user', 'book.category'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalRequests',
            'pendingRequests',
            'processingRequests',
            'completedRequests',
            'recentRequests'
        ));
    }
}
