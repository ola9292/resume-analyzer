<?php

use App\Http\Controllers\CreditController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResumeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', function () {
    return Inertia::render('Home');
})->name('home');

// Route::post('/resume', function(Request $request){

// });

Route::get('/pricing', [CreditController::class, 'pricing']);
Route::get('/support', [ResumeController::class, 'support']);
Route::post('/support', [ResumeController::class, 'support_email'])->name('support');

// resume analysis endpoints

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/create', [ResumeController::class, 'create'])->middleware('has.credit');
    Route::post('/resume', [ResumeController::class, 'index']);
    Route::post('/resume/download', [ResumeController::class, 'download']);

    Route::post('/checkout', [CreditController::class, 'checkout']);
    Route::get('/success', [CreditController::class, 'success'])->name('success');
    Route::get('/cancel', [CreditController::class, 'cancel'])->name('cancel');
    Route::post('/webhook', [CreditController::class, 'webhook'])->name('webhook');

});

require __DIR__.'/auth.php';
