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
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TodayController extends Controller
{
    public function index(): View
    {
        $dueCards = DictionaryEntry::where('user_id', Auth::id())->due()->count();
        $dueQuestions = NoteQuestion::whereHas('note', fn ($q) => $q->where('user_id', Auth::id()))->due()->count();

        return view('today.index', [
            'streak' => ActivityTracker::streak(),
            'studiedToday' => ActivityTracker::studiedToday(),
            'todayActivity' => ActivityTracker::todayActivity(),
            'dueCards' => $dueCards,
            'dueQuestions' => $dueQuestions,
            'inboxCount' => InboxItem::where('user_id', Auth::id())->whereNull('processed_at')->count(),
            'lesson' => [
                'quote' => Quote::where('user_id', Auth::id())->with(['book', 'movie'])->inRandomOrder()->first(),
                'note' => Note::where('user_id', Auth::id())->with('topic.parent')->inRandomOrder()->first(),
                'card' => DictionaryEntry::where('user_id', Auth::id())->due()->with('dictionary')->inRandomOrder()->first()
                    ?? DictionaryEntry::where('user_id', Auth::id())->with('dictionary')->inRandomOrder()->first(),
            ],
        ]);
    }

    public function storeInbox(Request $request): RedirectResponse
    {
        $request->validate(['content' => 'required|string|max:2000']);
        InboxItem::create(['content' => $request->input('content'), 'user_id' => Auth::id()]);

        return back()->with('success', 'Добавлено в инбокс.');
    }
}
