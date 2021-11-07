<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::post('global-message', function () {
    broadcast(new App\Events\EveryoneEvent(auth()->user()->name, request()->message));
})->name('global-message');

Route::post('private-message', function() {
    $user = \App\Models\User::find(request()->id);
    broadcast(new \App\Events\PrivateEvent($user, auth()->user()->name, request()->message));
})->name('private-message');
