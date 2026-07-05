<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DogController;
use App\Http\Controllers\SpotController;
use App\Http\Controllers\VisitController;
use App\Http\Controllers\CommentController;



Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('spots', SpotController::class)->except(['index', 'show']);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('dogs', DogController::class);
    Route::resource('spots', SpotController::class)->only(['index', 'show']);
    Route::post('/spots/{spot}/paw', [SpotController::class, 'togglePaw'])->name('spots.paw');
    Route::post('/spots/{spot}/visits', [VisitController::class, 'store'])->name('visits.store');
    Route::post('/visits/{visit}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});

require __DIR__.'/auth.php';



