<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DictionaryController;
use App\Http\Controllers\DictionaryEntryController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\GuideController;
use App\Http\Controllers\InboxController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\NoteQuestionController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TodayController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.store');
    Route::get('register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('register', [AuthController::class, 'register'])->name('register.store');
});

Route::post('logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('guide', [GuideController::class, 'index'])->name('guide.index');

Route::get('today', [TodayController::class, 'index'])->name('today.index');
Route::post('today/inbox', [TodayController::class, 'storeInbox'])->name('today.inbox');

Route::get('inbox', [InboxController::class, 'index'])->name('inbox.index');
Route::post('inbox', [InboxController::class, 'store'])->name('inbox.store');
Route::post('inbox/{inbox}/convert', [InboxController::class, 'convert'])->name('inbox.convert');
Route::delete('inbox/{inbox}', [InboxController::class, 'destroy'])->name('inbox.destroy');

Route::resource('topics', TopicController::class);
Route::resource('notes', NoteController::class);
Route::post('notes/{note}/questions', [NoteQuestionController::class, 'store'])->name('notes.questions.store');
Route::delete('notes/{note}/questions/{question}', [NoteQuestionController::class, 'destroy'])->name('notes.questions.destroy');

Route::resource('books', BookController::class);
Route::resource('movies', MovieController::class);
Route::resource('quotes', QuoteController::class)->except(['show']);
Route::resource('dictionaries', DictionaryController::class);
Route::resource('dictionaries.entries', DictionaryEntryController::class)->except(['show', 'index']);

Route::get('review', [ReviewController::class, 'index'])->name('review.index');
Route::get('review/exam', [NoteQuestionController::class, 'exam'])->name('review.exam');
Route::post('review/exam/{question}', [NoteQuestionController::class, 'examAnswer'])->name('review.exam.answer');
Route::get('review/{dictionary}', [ReviewController::class, 'session'])->name('review.session');
Route::post('review/{dictionary}/{entry}', [ReviewController::class, 'answer'])->name('review.answer');

Route::resource('reminders', ReminderController::class)->except(['show']);
Route::post('reminders/{reminder}/complete', [ReminderController::class, 'complete'])->name('reminders.complete');
Route::resource('events', EventController::class)->except(['show']);
Route::resource('journal', JournalController::class)->parameters(['journal' => 'entry']);
Route::get('users', [UserController::class, 'index'])->name('users.index');
Route::get('users/{user:username}', [UserController::class, 'show'])->name('users.show');
});
