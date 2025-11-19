<?php

use App\Http\Controllers\{
    AccountController,
    RegisterController,
    TransactionController,
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('register', [RegisterController::class, 'register'])->name('register');

Route::middleware(['auth:api'])->group(function () {
    Route::prefix('accounts')
        ->name('accounts.')
        ->controller(AccountController::class)
        ->group(function () {
            Route::post('', 'create')->name('create');
            Route::get('', 'list')->name('list');
            Route::get('/{accountId}', 'show')->name('show');
        });

    // REFACTOR: clean up these routes, should be nested above
    Route::controller(TransactionController::class)
        ->prefix('/accounts/{accountId}/transactions')
        ->name('transactions.')
        ->group(function () {
            Route::get('', 'list')->name('list');
            Route::post('', 'create')->name('create');
        });
});
