<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\IncomesController;
use App\Http\Controllers\SavingsController;
use App\Http\Controllers\SavingTransactionsController;
use App\Http\Controllers\BillsController;
use App\Http\Controllers\DetailBillsController;

// Home Route
Route::view('/', 'home.index')->name('home');

// Auth Routes
Route::group(['middleware' => 'web'], function () {
    Route::get('/login', [Auth\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [Auth\LoginController::class, 'login']);
    Route::get('/register', [Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [Auth\RegisterController::class, 'register']);
    Route::post('/logout', [Auth\LoginController::class, 'logout'])->name('logout');
});

// Kategori
Route::group(['middleware' => ['auth']], function () {
    Route::get('categories', [CategoriesController::class, 'index'])->name('categories');
    Route::get('categories/add', [CategoriesController::class, 'addPage'])->name('categories.addPage');
    Route::post('categories/insert', [CategoriesController::class, 'insert'])->name('categories.insert');
    Route::get('categories/edit/{id}', [CategoriesController::class, 'editPage'])->name('categories.editPage');
    Route::put('categories/update/{id}', [CategoriesController::class, 'update'])->name('categories.update');
    Route::get('categories/delete/{id}', [CategoriesController::class, 'delete'])->name('categories.delete');
});

// Incomes
Route::middleware(['auth'])->group(function () {
    Route::get('incomes', [IncomesController::class, 'index'])->name('incomes');
    Route::get('incomes/add', [IncomesController::class, 'addPage'])->name('incomes.addPage');
    Route::post('incomes/insert', [IncomesController::class, 'insert'])->name('incomes.insert');
    Route::put('incomes/update/{id}', [IncomesController::class, 'update'])->name('incomes.update');
    Route::get('incomes/delete/{id}', [IncomesController::class, 'delete'])->name('incomes.delete');
});

// Expenses
Route::middleware(['auth'])->group(function () {
    Route::get('expenses', [ExpensesController::class, 'index'])->name('expenses');
    Route::get('expenses/add', [ExpensesController::class, 'addPage'])->name('expenses.addPage');
    Route::post('expenses/insert', [ExpensesController::class, 'insert'])->name('expenses.insert');
    Route::get('expenses/edit/{id}', [ExpensesController::class, 'editPage'])->name('expenses.editPage');
    Route::put('expenses/update/{id}', [ExpensesController::class, 'update'])->name('expenses.update');
    Route::get('expenses/delete/{id}', [ExpensesController::class, 'delete'])->name('expenses.delete');
});

// Savings
Route::middleware(['auth'])->group(function () {
    Route::get('savings', [SavingsController::class, 'index'])->name('savings');
    Route::get('savings/add', [SavingsController::class, 'addPage'])->name('savings.addPage');
    Route::post('savings/insert', [SavingsController::class, 'insert'])->name('savings.insert');
    Route::get('/savings/{id}', [SavingsController::class, 'detail'])->name('savings.detail');
    Route::post('savings/transactions/insert', [SavingTransactionsController::class, 'insert'])->name('transactions.insert');
    Route::put('savings/transactions/update/{id}', [SavingTransactionsController::class, 'update'])->name('transactions.update');
    Route::delete('savings/transactions/delete/{id}', [SavingTransactionsController::class, 'delete'])->name('transactions.delete');
    Route::put('savings/update/{id}', [SavingsController::class, 'update'])->name('savings.update');
    Route::delete('savings/delete/{id}', [SavingsController::class, 'delete'])->name('savings.delete');
});

// Bills
Route::middleware(['auth'])->group(function () {
    Route::get('bills', [BillsController::class, 'index'])->name('bills');
    Route::get('bills/add', [BillsController::class, 'addPage'])->name('bills.addPage');
    Route::post('bills/insert', [BillsController::class, 'insert'])->name('bills.insert');
    Route::get('bills/edit/{id}', [BillsController::class, 'editPage'])->name('bills.editPage');
    Route::put('bills/update/{id}', [BillsController::class, 'update'])->name('bills.update');
    Route::get('bills/delete/{id}', [BillsController::class, 'delete'])->name('bills.delete');
});

// Detail Bills
Route::middleware(['auth'])->group(function () {
    Route::put('bills/detail/update/{id}', [DetailBillsController::class, 'update'])->name('bills.detail.update');
    Route::get('bills/detail/delete/{id}', [DetailBillsController::class, 'delete'])->name('bills.detail.delete');
});

// Dashboard Route
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/generate-pdf', [DashboardController::class, 'generatePdf'])->name('generate.pdf');


// Auth Routes
Auth::routes();
