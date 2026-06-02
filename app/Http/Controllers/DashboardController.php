<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\JournalEntry;
use App\Models\Note;
use App\Models\Reminder;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('dashboard', [
            'dueReminders' => Reminder::where('user_id', Auth::id())->due()->orderBy('remind_at')->limit(5)->get(),
            'upcomingEvents' => Event::where('user_id', Auth::id())->where('starts_at', '>=', now())->orderBy('starts_at')->limit(5)->get(),
            'recentNotes' => Note::where('user_id', Auth::id())->with('topic.parent')->latest()->limit(5)->get(),
            'readingBooks' => Book::where('user_id', Auth::id())->where('status', 'reading')->orderBy('title')->limit(3)->get(),
            'todayJournal' => JournalEntry::where('user_id', Auth::id())->whereDate('entry_date', today())->latest()->first(),
        ]);
    }
}
