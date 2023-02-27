<?php

use App\Http\Controllers\TransactionController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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


Route::get('admin', function () {
    return view('admin');
});

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/home', function () { return view('home'); });
    Route::get('/deposit', function () { return view('deposit'); })->name('Deposit');
    Route::get('/withdraw', function () { return view('withdraw'); })->name('Withdraw');
    Route::get('/transfer', function () {
        $users =  User::where('id', '!=', Auth::user()->id)->get();
        return view('transfer')->with(['users' => $users]);
    })->name('Transfer');

    Route::resource('/transaction', TransactionController::class);
});
