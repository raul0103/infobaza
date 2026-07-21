<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\NoteQuestion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NoteQuestionController extends Controller
{
    public function store(Request $request, Note $note): RedirectResponse
    {
        $note->questions()->create($request->validate([
            'question' => 'required|string|max:500',
            'answer' => 'required|string',
        ]));

        return back()->with('success', 'Вопрос добавлен.');
    }

    public function destroy(Note $note, NoteQuestion $question): RedirectResponse
    {
        abort_unless($question->note_id === $note->id, 404);
        $question->delete();

        return back()->with('success', 'Вопрос удалён.');
    }

    public function exam(): View
    {
        $ownedQuestions = NoteQuestion::query();
        $question = (clone $ownedQuestions)->due()->with('note')->inRandomOrder()->first()
            ?? (clone $ownedQuestions)->with('note')->inRandomOrder()->first();

        return view('exam.index', [
            'question' => $question,
        ]);
    }

    public function examAnswer(NoteQuestion $question): RedirectResponse
    {
        $question->recordReview(true);
        \App\Services\ActivityTracker::log('card');

        return redirect()->route('exam');
    }
}
