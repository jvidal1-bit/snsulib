<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookRequest;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $totalRequests      = BookRequest::count();
        $pendingRequests    = BookRequest::where('status', 'pending')->count();
        $processingRequests = BookRequest::where('status', 'processing')->count();
        $completedRequests  = BookRequest::where('status', 'completed')->count();

        $recentRequests = BookRequest::with(['user', 'book.category'])
            ->latest()
            ->take(10)
            ->get()
            ->map(fn($req) => [
                'id'             => $req->id,
                'student'        => $req->user->name ?? 'Unknown',
                'title'          => $req->book->title ?? 'Unknown Book',
                'author'         => $req->book->author ?? '-',
                'category'       => $req->book->category->name ?? '-',
                'year_published' => $req->book->year_published ?? '-',
            ]);

        return Inertia::render('Admin/Dashboard', [
            'totalRequests'      => $totalRequests,
            'pendingRequests'    => $pendingRequests,
            'processingRequests' => $processingRequests,
            'completedRequests'  => $completedRequests,
            'recentRequests'     => $recentRequests,
            'authName'           => auth()->user()->name ?? 'Admin',  // ← add this
        ]);
    }
}