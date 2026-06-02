<?php

namespace App\Http\Controllers;

use App\Models\DictionaryEntry;
use App\Models\InboxItem;
use App\Models\Note;
use App\Models\NoteQuestion;
use App\Models\Quote;
use App\Services\ActivityTracker;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TodayController extends Controller
{
    public function index(): View
    {
        $dueCards = DictionaryEntry::due()->count();
        $dueQuestions = NoteQuestion::due()->count();

        return view('today.index', [
            'streak' => ActivityTracker::streak(),
            'studiedToday' => ActivityTracker::studiedToday(),
            'todayActivity' => ActivityTracker::todayActivity(),
            'dueCards' => $dueCards,
            'dueQuestions' => $dueQuestions,
            'inboxCount' => InboxItem::whereNull('processed_at')->count(),
            'lesson' => [
                'quote' => Quote::with(['book', 'movie'])->inRandomOrder()->first(),
                'note' => Note::with('topic.parent')->inRandomOrder()->first(),
                'card' => DictionaryEntry::due()->with('dictionary')->inRandomOrder()->first()
                    ?? DictionaryEntry::with('dictionary')->inRandomOrder()->first(),
            ],
        ]);
    }

    public function storeInbox(Request $request): RedirectResponse
    {
        $request->validate(['content' => 'required|string|max:2000']);
        InboxItem::create(['content' => $request->content]);

        return back()->with('success', 'Добавлено в инбокс.');
    }
}
