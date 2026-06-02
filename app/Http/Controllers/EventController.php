<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Support\CurrentUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class EventController extends Controller
{
    public function index(): View
    {
        return view('events.index', [
            'upcoming' => Event::where('user_id', Auth::id())->where('starts_at', '>=', now()->startOfDay())
                ->orderBy('starts_at')
                ->get(),
            'past' => Event::where('user_id', Auth::id())->where('starts_at', '<', now()->startOfDay())
                ->orderByDesc('starts_at')
                ->limit(30)
                ->get(),
        ]);
    }

    public function create(): View
    {
        return view('events.form', [
            'event' => new Event(['starts_at' => now(), 'all_day' => false]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Event::create($this->validated($request) + ['user_id' => CurrentUser::id(), 'visibility' => 'private']);

        return redirect()->route('events.index')->with('success', 'Событие создано.');
    }

    public function edit(Event $event): View
    {
        $this->ensureOwnedByCurrentUser($event);
        return view('events.form', compact('event'));
    }

    public function update(Request $request, Event $event): RedirectResponse
    {
        $this->ensureOwnedByCurrentUser($event);
        $event->update($this->validated($request) + ['visibility' => 'private']);

        return redirect()->route('events.index')->with('success', 'Событие обновлено.');
    }

    public function destroy(Event $event): RedirectResponse
    {
        $this->ensureOwnedByCurrentUser($event);
        $event->delete();

        return redirect()->route('events.index')->with('success', 'Событие удалено.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'nullable|string',
            'starts_at' => 'required|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'all_day' => 'nullable|boolean',
            'location' => 'nullable|string|max:255',
        ]);
        $data['all_day'] = $request->boolean('all_day');

        return $data;
    }
}
