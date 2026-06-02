<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use App\Support\CurrentUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ReminderController extends Controller
{
    public function index(): View
    {
        return view('reminders.index', [
            'pending' => Reminder::where('user_id', Auth::id())->pending()->orderBy('remind_at')->get(),
            'completed' => Reminder::where('user_id', Auth::id())->whereNotNull('completed_at')->latest('completed_at')->limit(20)->get(),
        ]);
    }

    public function create(): View
    {
        return view('reminders.form', ['reminder' => new Reminder(['remind_at' => now()->addHour()])]);
    }

    public function store(Request $request): RedirectResponse
    {
        Reminder::create($this->validated($request) + ['user_id' => CurrentUser::id(), 'visibility' => 'private']);

        return redirect()->route('reminders.index')->with('success', 'Напоминание создано.');
    }

    public function edit(Reminder $reminder): View
    {
        $this->ensureOwnedByCurrentUser($reminder);
        return view('reminders.form', compact('reminder'));
    }

    public function update(Request $request, Reminder $reminder): RedirectResponse
    {
        $this->ensureOwnedByCurrentUser($reminder);
        $reminder->update($this->validated($request) + ['visibility' => 'private']);

        return redirect()->route('reminders.index')->with('success', 'Напоминание обновлено.');
    }

    public function complete(Reminder $reminder): RedirectResponse
    {
        $this->ensureOwnedByCurrentUser($reminder);
        $reminder->update(['completed_at' => now()]);

        return back()->with('success', 'Выполнено.');
    }

    public function destroy(Reminder $reminder): RedirectResponse
    {
        $this->ensureOwnedByCurrentUser($reminder);
        $reminder->delete();

        return redirect()->route('reminders.index')->with('success', 'Напоминание удалено.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'nullable|string',
            'remind_at' => 'required|date',
        ]);
    }
}
