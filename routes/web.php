<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\BookThoughtController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DictionaryController;
use App\Http\Controllers\DictionaryEntryController;
use App\Http\Controllers\DictionaryEntryGroupController;
use App\Http\Controllers\FactController;
use App\Http\Controllers\FactGroupController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\GuideController;
use App\Http\Controllers\JokeController;
use App\Http\Controllers\MemoController;
use App\Http\Controllers\MemoEntryController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\NoteQuestionController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\TipController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('guide', [GuideController::class, 'index'])->name('guide.index');
Route::get('favorites', [FavoriteController::class, 'index'])->name('favorites.index');
Route::patch('favorites/thoughts/{thought}', [FavoriteController::class, 'toggleThought'])
    ->name('favorites.thoughts.toggle');
Route::patch('favorites/quotes/{quote}', [FavoriteController::class, 'toggleQuote'])
    ->name('favorites.quotes.toggle');

Route::resource('topics', TopicController::class);
Route::resource('notes', NoteController::class);
Route::post('notes/{note}/questions', [NoteQuestionController::class, 'store'])->name('notes.questions.store');
Route::delete('notes/{note}/questions/{question}', [NoteQuestionController::class, 'destroy'])->name('notes.questions.destroy');

Route::resource('books', BookController::class);
Route::patch('books/{book}/progress', [BookController::class, 'updateProgress'])->name('books.progress');
Route::patch('books/queued/reorder', [BookController::class, 'reorderQueued'])->name('books.queued.reorder');
Route::resource('books.thoughts', BookThoughtController::class)->except(['index', 'show']);
Route::get('movies/status/{status}', [MovieController::class, 'status'])->name('movies.status');
Route::resource('movies', MovieController::class);
Route::resource('memos', MemoController::class);
Route::resource('memos.entries', MemoEntryController::class)->except(['index']);
Route::resource('quotes', QuoteController::class)->except(['index', 'show']);
Route::resource('tips', TipController::class)->except(['index', 'show']);
Route::resource('facts', FactController::class)->except(['show']);
Route::resource('fact-groups', FactGroupController::class)->except(['index', 'show']);
Route::resource('jokes', JokeController::class)->except(['show']);
Route::resource('dictionaries', DictionaryController::class);
Route::resource('dictionaries.entries', DictionaryEntryController::class)->except(['show', 'index']);
Route::resource('dictionaries.groups', DictionaryEntryGroupController::class)->except(['index', 'show']);
Route::delete(
    'dictionaries/{dictionary}/groups/{group}/attachments/{attachment}',
    [DictionaryEntryGroupController::class, 'destroyAttachment']
)->name('dictionaries.groups.attachments.destroy');

Route::get('exam', [NoteQuestionController::class, 'exam'])->name('exam');
Route::post('exam/{question}', [NoteQuestionController::class, 'examAnswer'])->name('exam.answer');

Route::get('review', [ReviewController::class, 'index'])->name('review.index');
Route::get('review/all', [ReviewController::class, 'allSession'])->name('review.all');
Route::post('review/all/{entry}', [ReviewController::class, 'allAnswer'])->name('review.all.answer');
Route::get('review/facts', [ReviewController::class, 'factsSession'])->name('review.facts');
Route::post('review/facts/{fact}', [ReviewController::class, 'factsAnswer'])->name('review.facts.answer');
Route::get('review/{dictionary}', [ReviewController::class, 'session'])->name('review.session');
Route::post('review/{dictionary}/{entry}', [ReviewController::class, 'answer'])->name('review.answer');
