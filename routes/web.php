<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DictionaryController;
use App\Http\Controllers\DictionaryEntryController;
use App\Http\Controllers\FactController;
use App\Http\Controllers\GuideController;
use App\Http\Controllers\JokeController;
use App\Http\Controllers\InboxController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\NoteQuestionController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TopicController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('guide', [GuideController::class, 'index'])->name('guide.index');

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
Route::resource('facts', FactController::class)->except(['show']);
Route::resource('jokes', JokeController::class)->except(['show']);
Route::resource('dictionaries', DictionaryController::class);
Route::resource('dictionaries.entries', DictionaryEntryController::class)->except(['show', 'index']);

Route::get('review', [ReviewController::class, 'index'])->name('review.index');
Route::get('review/exam', [NoteQuestionController::class, 'exam'])->name('review.exam');
Route::post('review/exam/{question}', [NoteQuestionController::class, 'examAnswer'])->name('review.exam.answer');
Route::get('review/facts', [ReviewController::class, 'factsSession'])->name('review.facts');
Route::post('review/facts/{fact}', [ReviewController::class, 'factsAnswer'])->name('review.facts.answer');
Route::get('review/{dictionary}', [ReviewController::class, 'session'])->name('review.session');
Route::post('review/{dictionary}/{entry}', [ReviewController::class, 'answer'])->name('review.answer');
