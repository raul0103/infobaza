<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\JournalEntry;
use App\Models\Note;
use App\Models\Reminder;
use App\Models\Event;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('dashboard', [
            'dueReminders' => Reminder::due()->orderBy('remind_at')->limit(5)->get(),
            'upcomingEvents' => Event::where('starts_at', '>=', now())->orderBy('starts_at')->limit(5)->get(),
            'recentNotes' => Note::with('topic.parent')->latest()->limit(5)->get(),
            'readingBooks' => Book::where('status', 'reading')->orderBy('title')->limit(3)->get(),
            'todayJournal' => JournalEntry::whereDate('entry_date', today())->latest()->first(),
        ]);
    }
}
