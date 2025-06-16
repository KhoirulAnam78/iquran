<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', App\Livewire\Front\Home::class)->name('home');
Route::get('/prediksi-gerakan-isyarat-quran', function () {
    return view('gerakan-isyarat');
});

Route::get('phpinfo', function () {
    return phpinfo();
});

// Route::get('/dashboard',App\Livewire\Dashboard\Index::class )->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', App\Livewire\Dashboard\Index::class)->name('dashboard');
    Route::get('role', \App\Livewire\Role\Index::class)->name('role.index');
    Route::get('role/add', \App\Livewire\Role\Create::class)->name('role.create');
    Route::get('role/edit/{id}', \App\Livewire\Role\Edit::class)->name('role.edit');
    Route::get('permission', \App\Livewire\Permission\Index::class)->name('permission.index');
    Route::get('permission/add', \App\Livewire\Permission\Create::class)->name('permission.create');
    Route::get('permission/edit/{id}', \App\Livewire\Permission\Edit::class)->name('permission.edit');
    Route::get('menu', \App\Livewire\Menu\Index::class)->name('menu.index');
    Route::get('menu/add', \App\Livewire\Menu\Create::class)->name('menu.create');
    Route::get('menu/edit/{id}', \App\Livewire\Menu\Edit::class)->name('menu.edit');

    Route::get('konten', \App\Livewire\KontenIquran\Index::class)->name('konten.index');
    Route::get('konten/add', \App\Livewire\KontenIquran\Create::class)->name('konten.create');
    Route::get('konten/edit/{id}', \App\Livewire\KontenIquran\Edit::class)->name('konten.edit');

    Route::get('user', \App\Livewire\Users\Index::class)->name('user.index');
    Route::get('user/add', \App\Livewire\Users\Create::class)->name('user.create');
    Route::get('user/edit/{id}', \App\Livewire\Users\Edit::class)->name('user.edit');

    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
