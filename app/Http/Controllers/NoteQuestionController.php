<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\NoteQuestion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NoteQuestionController extends Controller
{
    public function store(Request $request, Note $note): RedirectResponse
    {
        $this->ensureOwnedByCurrentUser($note);
        $note->questions()->create($request->validate([
            'question' => 'required|string|max:500',
            'answer' => 'required|string',
        ]));

        return back()->with('success', 'Вопрос добавлен.');
    }

    public function destroy(Note $note, NoteQuestion $question): RedirectResponse
    {
        $this->ensureOwnedByCurrentUser($note);
        abort_unless($question->note_id === $note->id, 404);
        $question->delete();

        return back()->with('success', 'Вопрос удалён.');
    }

    public function exam(): View
    {
        $ownedQuestions = NoteQuestion::whereHas('note', fn ($q) => $q->where('user_id', Auth::id()));
        $question = (clone $ownedQuestions)->due()->with('note')->inRandomOrder()->first()
            ?? (clone $ownedQuestions)->with('note')->inRandomOrder()->first();

        return view('review.exam', [
            'question' => $question,
            'dueCount' => $ownedQuestions->due()->count(),
        ]);
    }

    public function examAnswer(Request $request, NoteQuestion $question): RedirectResponse
    {
        $this->ensureOwnedByCurrentUser($question->note);
        $request->validate(['known' => 'required|boolean']);
        $question->recordReview($request->boolean('known'));
        \App\Services\ActivityTracker::log('card');

        return redirect()->route('review.exam');
    }
}
