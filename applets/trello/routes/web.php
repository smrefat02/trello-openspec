<?php

use Illuminate\Support\Facades\Route;
use Trello\Http\Controllers\BoardController;
use Trello\Http\Controllers\ListController;
use Trello\Http\Controllers\CardController;
use Trello\Http\Controllers\LabelController;
use Trello\Http\Controllers\ChecklistController;
use Trello\Http\Controllers\ChecklistItemController;
use Trello\Http\Controllers\CommentController;

/*
|--------------------------------------------------------------------------
| Trello Package Routes
|--------------------------------------------------------------------------
*/

Route::prefix('trello')->middleware(['web', 'auth'])->group(function () {
    
    // Board routes
    Route::resource('boards', BoardController::class);
    
    // List routes
    Route::post('lists', [ListController::class, 'store'])->name('trello.lists.store');
    Route::put('lists/{list}', [ListController::class, 'update'])->name('trello.lists.update');
    Route::delete('lists/{list}', [ListController::class, 'destroy'])->name('trello.lists.destroy');
    
    // Card routes
    Route::get('cards/{card}', [CardController::class, 'show'])->name('trello.cards.show');
    Route::post('cards', [CardController::class, 'store'])->name('trello.cards.store');
    Route::put('cards/{card}', [CardController::class, 'update'])->name('trello.cards.update');
    Route::delete('cards/{card}', [CardController::class, 'destroy'])->name('trello.cards.destroy');
    Route::patch('cards/{card}/move', [CardController::class, 'move'])->name('trello.cards.move');
    
    // Label routes
    Route::post('labels', [LabelController::class, 'store'])->name('trello.labels.store');
    Route::delete('labels/{label}', [LabelController::class, 'destroy'])->name('trello.labels.destroy');
    
    // Checklist routes
    Route::post('checklists', [ChecklistController::class, 'store'])->name('trello.checklists.store');
    Route::delete('checklists/{checklist}', [ChecklistController::class, 'destroy'])->name('trello.checklists.destroy');
    
    // Checklist item routes
    Route::post('checklist-items', [ChecklistItemController::class, 'store'])->name('trello.checklist-items.store');
    Route::patch('checklist-items/{item}/toggle', [ChecklistItemController::class, 'toggle'])->name('trello.checklist-items.toggle');
    Route::delete('checklist-items/{item}', [ChecklistItemController::class, 'destroy'])->name('trello.checklist-items.destroy');
    
    // Comment routes
    Route::post('comments', [CommentController::class, 'store'])->name('trello.comments.store');
    Route::delete('comments/{comment}', [CommentController::class, 'destroy'])->name('trello.comments.destroy');
});

