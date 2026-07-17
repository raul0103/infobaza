<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Note;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('dashboard', [
            'recentNotes' => Note::with('topic.parent')->latest()->limit(5)->get(),
            'readingBooks' => Book::where('status', 'reading')->orderBy('title')->limit(3)->get(),
        ]);
    }
}
