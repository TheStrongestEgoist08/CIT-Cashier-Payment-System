<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\User\PayablesController as Payables;
use App\Http\Controllers\User\DashboardController as Dashboard;
use App\Http\Controllers\User\StudentController as Student;
use App\Http\Controllers\User\TransactionController as Transaction;
use App\Http\Controllers\User\SOAController as SOA;
use App\Http\Controllers\User\ReportController as Report;
use App\Http\Controllers\User\AccountController as Account;
use App\Http\Controllers\User\ActivityLogController as Activity;
use App\Http\Controllers\User\BackupController as Backup;

Route::get('/', function () {
    return view('welcome');
})->named('welcome');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

# Dashboard Routes
Route::controller(Dashboard::class)->middleware(['auth', 'verified', 'role:both', 'activity.log'])->group(function() {

    Route::get('/dashboard', 'index')
        ->name('dashboard');

});

# Payables Routes
Route::controller(Payables::class)->middleware(['auth', 'verified', 'role:admin', 'activity.log'])->group(function() {

    Route::get('/payables', 'index')
        ->name('payables');

    Route::post('/payables/store', 'store')
        ->name('payables.store');

    Route::delete('/payables/{payable}', 'destroy')
        ->name('payables.destroy');

    Route::put('/payables/{payable}', 'update')
        ->name('payables.update');

    Route::post('penalties', 'storePenalty')
        ->name('penalties.store');

    Route::put('penalties/{penalty}', 'updatePenalty')
        ->name('penalties.update');

    Route::delete('penalties/{penalty}', 'destroyPenalty')
        ->name('penalties.destroy');

});



# Students Routes
Route::controller(Student::class)->middleware(['auth', 'verified', 'role:both', 'activity.log'])->group(function() {

    Route::get('/students', 'index')
        ->name('students');

    Route::post('/students/import', 'import')
        ->name('students.import');

    Route::get('/students/{student}/payables', 'getPayables')
        ->name('students.getPayables');

    Route::post('/students/store', 'store')
        ->name('students.store');

});

# Transaction Routes
Route::controller(Transaction::class)->middleware(['auth', 'verified', 'role:both', 'activity.log'])->group(function() {

    Route::get('/transactions', 'index')
        ->name('transactions');

    Route::get('/transactions/{transaction}', 'show')
        ->name('transactions.show');

    Route::get('/transactions/print/{id}', 'print')
        ->name('transactions.print')
        ->middleware('auth');

});

# SOA Routes
Route::controller(SOA::class)->middleware(['auth', 'verified', 'role:both', 'activity.log'])->group(function() {

    Route::get('/summary', 'index')
        ->name('summary');


    Route::post('/summary/export-statement', 'exportStudentSOA')
         ->name('summary.exportStudentSOA');

    Route::post('/summary/export-summary', 'exportStudentSummaryOfAccount')
         ->name('summary.exportStudentSummaryOfAccount');

    Route::post('/summary/print-statement', 'printStudentSOA')
         ->name('summary.printStudentSOA');

    Route::post('/summary/print-summary', 'printStudentSummaryOfAccount')
         ->name('summary.printStudentSummaryOfAccount');

});

# Report Routes
Route::controller(Report::class)->middleware(['auth', 'verified', 'role:both', 'activity.log'])->group(function() {

    Route::get('/reports', 'index')
        ->name('reports');

    Route::get('/reports/export/excel', 'exportExcel')
        ->name('reports.export.excel');

    Route::get('/reports/export/pdf', 'exportPdf')
        ->name('reports.export.pdf');

});

# Account Routes
Route::controller(Account::class)->middleware(['auth', 'verified', 'role:admin', 'activity.log'])->group(function() {

    Route::get('/accounts', 'index')
        ->name('accounts');

    Route::post('/accounts', 'store')
        ->name('accounts.store');

    Route::patch('/accounts/{user}/activate', 'activate')
        ->name('accounts.activate');

    Route::patch('/accounts/{user}/deactivate', 'deactivate')
        ->name('accounts.deactivate');

    Route::delete('/accounts/{user}', 'destroy')
        ->name('accounts.destroy');

});

# Activity Routes
Route::controller(Activity::class)->middleware(['auth', 'verified', 'role:admin', 'activity.log'])->group(function() {

    Route::get('/activities', 'index')
        ->name('activities');

    Route::post('/activities/export/pdf', 'exportPdf')
        ->name('activities.export.pdf');

    Route::post('/activities/print/pdf', 'printPdf')
        ->name('activities.print.pdf');

});

# Backup Routes
Route::controller(Backup::class)->middleware(['auth', 'verified', 'role:admin'])->group(function() {

    Route::get('/backup', 'index')
        ->name('backup');

    Route::post('/backup/database', 'backup')
        ->name('backup.backup');

    Route::post('/backup/verify-path', 'verifyPath')
        ->name('backup.verify');

    Route::get('/backup/download/{filename}', 'download')
        ->name('backup.download');

    Route::get('/backup/restore-file/{filename}', 'restoreFromFile')
        ->name('backup.restore.file');

    Route::get('/backup/delete/{filename}', 'delete')
        ->name('backup.delete');

    Route::post('/backup/path/store', 'storePath')
        ->name('backup.path.store');

    Route::delete('/backup/path/{id}', 'destroyPath')
        ->name('backup.path.destroy');

    Route::post('/backup/path/{id}/default', 'setDefaultPath')
        ->name('backup.path.default');

    Route::get('/backup/schedule', 'scheduleIndex')
         ->name('backup.schedule');

    Route::post('/backup/schedule', 'updateSchedule')
         ->name('backup.schedule.update');

});

