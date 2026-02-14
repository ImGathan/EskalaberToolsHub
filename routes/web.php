<?php

use App\Http\Controllers\LandingController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\PlaceController;
use App\Http\Controllers\Admin\TypeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ToolsmanController;
use App\Http\Controllers\Admin\ToolController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Toolsman\UserController as ToolsmanUserController;
use App\Http\Controllers\Toolsman\ToolController as ToolsmanToolController;
use App\Http\Controllers\Toolsman\MaintenanceToolController as ToolsmanMaintenanceToolController;
use App\Http\Controllers\Toolsman\LoanController as ToolsmanLoanController;
use App\Http\Controllers\Toolsman\FineController as ToolsmanFineController;
use App\Http\Controllers\Toolsman\DashboardController as ToolsmanDashboardController;
use App\Http\Controllers\User\UserController as UserUserController;
use App\Http\Controllers\User\ToolController as UserToolController;
use App\Http\Controllers\User\LoanController as UserLoanController;
use App\Http\Controllers\User\FineController as UserFineController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('landing');

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'doLogin'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Users Routes
Route::middleware('auth', 'role:SUPERADMIN')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/add', [UserController::class, 'add'])->name('add');
        Route::post('/create', [UserController::class, 'doCreate'])->name('create');
        Route::get('/detail/{id}', [UserController::class, 'detail'])->name('detail');
        Route::get('/update/{id}', [UserController::class, 'update'])->name('update');
        Route::post('/update/{id}', [UserController::class, 'doUpdate'])->name('doUpdate');
        Route::delete('/delete/{id}', [UserController::class, 'delete'])->name('delete');
        Route::post('/reset-password/{id}', [UserController::class, 'resetPassword'])->name('resetPassword');
    });

    Route::prefix('toolsmans')->name('toolsmans.')->group(function () {
        Route::get('/', [ToolsmanController::class, 'index'])->name('index');
        Route::get('/add', [ToolsmanController::class, 'add'])->name('add');
        Route::post('/create', [ToolsmanController::class, 'doCreate'])->name('create');
        Route::get('/detail/{id}', [ToolsmanController::class, 'detail'])->name('detail');
        Route::get('/update/{id}', [ToolsmanController::class, 'update'])->name('update');
        Route::post('/update/{id}', [ToolsmanController::class, 'doUpdate'])->name('doUpdate');
        Route::delete('/delete/{id}', [ToolsmanController::class, 'delete'])->name('delete');
        Route::post('/reset-password/{id}', [ToolsmanController::class, 'resetPassword'])->name('resetPassword');
    });

    Route::prefix('places')->name('places.')->group(function () {
        Route::get('/', [PlaceController::class, 'index'])->name('index');
        Route::get('/add', [PlaceController::class, 'add'])->name('add');
        Route::post('/create', [PlaceController::class, 'doCreate'])->name('create');
        Route::get('/update/{id}', [PlaceController::class, 'update'])->name('update');
        Route::post('/update/{id}', [PlaceController::class, 'doUpdate'])->name('doUpdate');
        Route::delete('/delete/{id}', [PlaceController::class, 'delete'])->name('delete');
    });

    Route::prefix('types')->name('types.')->group(function () {
        Route::get('/', [TypeController::class, 'index'])->name('index');
        Route::get('/add', [TypeController::class, 'add'])->name('add');
        Route::post('/create', [TypeController::class, 'doCreate'])->name('create');
        Route::get('/update/{id}', [TypeController::class, 'update'])->name('update');
        Route::post('/update/{id}', [TypeController::class, 'doUpdate'])->name('doUpdate');
        Route::delete('/delete/{id}', [TypeController::class, 'delete'])->name('delete');
    });

    Route::prefix('departments')->name('departments.')->group(function () {
        Route::get('/', [DepartmentController::class, 'index'])->name('index');
        Route::get('/add', [DepartmentController::class, 'add'])->name('add');
        Route::post('/create', [DepartmentController::class, 'doCreate'])->name('create');
        Route::get('/update/{id}', [DepartmentController::class, 'update'])->name('update');
        Route::post('/update/{id}', [DepartmentController::class, 'doUpdate'])->name('doUpdate');
        Route::delete('/delete/{id}', [DepartmentController::class, 'delete'])->name('delete');
    });

    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/add', [CategoryController::class, 'add'])->name('add');
        Route::post('/create', [CategoryController::class, 'doCreate'])->name('create');
        Route::get('/update/{id}', [CategoryController::class, 'update'])->name('update');
        Route::post('/update/{id}', [CategoryController::class, 'doUpdate'])->name('doUpdate');
        Route::delete('/delete/{id}', [CategoryController::class, 'delete'])->name('delete');
    });

    Route::prefix('tools')->name('tools.')->group(function () {
        Route::get('/', [ToolController::class, 'index'])->name('index');
        Route::get('/add', [ToolController::class, 'add'])->name('add');
        Route::post('/create', [ToolController::class, 'doCreate'])->name('create');
        Route::get('/update/{id}', [ToolController::class, 'update'])->name('update');
        Route::post('/update/{id}', [ToolController::class, 'doUpdate'])->name('doUpdate');
        Route::delete('/delete/{id}', [ToolController::class, 'delete'])->name('delete');
        Route::get('/{id}/generate-qr', [ToolController::class, 'generateQR'])->name('generate-qr');
    });

    Route::prefix('activity_logs')->name('activity_logs.')->group(function () {
        Route::get('/', [ActivityLogController::class, 'index'])->name('index');
    });

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/change-password', [UserController::class, 'changePassword'])->name('change_password');
        Route::post('/change-password', [UserController::class, 'doChangePassword'])->name('do_change_password');
    });
});

Route::middleware('auth', 'role:TOOLSMAN')->prefix('toolsman')->name('toolsman.')->group(function () {
    Route::get('/dashboard', [ToolsmanDashboardController::class, 'index'])->name('dashboard');

    Route::prefix('tools')->name('tools.')->group(function () {
        Route::get('/', [ToolsmanToolController::class, 'index'])->name('index');
        Route::get('/add', [ToolsmanToolController::class, 'add'])->name('add');
        Route::post('/create', [ToolsmanToolController::class, 'doCreate'])->name('create');
        Route::get('/update/{id}', [ToolsmanToolController::class, 'update'])->name('update');
        Route::post('/update/{id}', [ToolsmanToolController::class, 'doUpdate'])->name('do_update');
        Route::delete('/delete/{id}', [ToolsmanToolController::class, 'delete'])->name('delete');
        Route::get('/{id}/generate-qr', [ToolController::class, 'generateQR'])->name('generate-qr');
        Route::patch('{id}/move-to-broken', [ToolsmanToolController::class, 'moveToBroken'])->name('move-to-broken');
    });

    Route::prefix('maintenance-tools')->name('maintenance-tools.')->group(function () {
        Route::get('/', [ToolsmanMaintenanceToolController::class, 'index'])->name('index');
        Route::patch('{id}/update-qty', [ToolsmanMaintenanceToolController::class, 'updateQty'])->name('update-qty');
        Route::patch('{id}/restore', [ToolsmanMaintenanceToolController::class, 'restore'])->name('restore');
    });

    Route::prefix('loans')->name('loans.')->group(function () {
        Route::get('/', [ToolsmanLoanController::class, 'index'])->name('index');
        Route::get('/detail/{id}', [ToolsmanLoanController::class, 'detail'])->name('detail');
        Route::patch('/{id}/approve', [ToolsmanLoanController::class, 'approve'])->name('approve');
        Route::patch('/{id}/reject', [ToolsmanLoanController::class, 'reject'])->name('reject');
        Route::patch('/{id}/returned', [ToolsmanLoanController::class, 'returned'])->name('returned');
        Route::get('/{id}/late-report', [ToolsmanLoanController::class, 'downloadLateReport'])->name('late-report');
        Route::get('/export-history', [ToolsmanLoanController::class, 'exportHistoryExcel'])->name('export-history');
    });

    Route::prefix('fines')->name('fines.')->group(function () {
        Route::get('/', [ToolsmanFineController::class, 'index'])->name('index');
        Route::patch('/pay/{id}', [ToolsmanFineController::class, 'pay'])->name('pay');
        Route::patch('/paid/{id}', [ToolsmanFineController::class, 'paid'])->name('paid');
        Route::get('/{id}/unpaid-report', [ToolsmanFineController::class, 'downloadUnpaidReport'])->name('unpaid-report');
        Route::get('/{id}/paid-report', [ToolsmanFineController::class, 'downloadPaidReport'])->name('paid-report');
        Route::get('/export-paid-fine', [ToolsmanFineController::class, 'exportPaidFineExcel'])->name('export-paid-fine');
    });

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/change-password', [ToolsmanUserController::class, 'changePassword'])->name('change_password');
        Route::post('/change-password', [ToolsmanUserController::class, 'doChangePassword'])->name('do_change_password');
    });
});


Route::middleware('auth', 'role:USER')->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/change-password', [UserUserController::class, 'changePassword'])->name('change_password');
        Route::post('/change-password', [UserUserController::class, 'doChangePassword'])->name('do_change_password');
    });

    Route::prefix('tools')->name('tools.')->group(function () {
        Route::get('/', [UserToolController::class, 'index'])->name('index');
    });

    Route::prefix('loans')->name('loans.')->group(function () {
        Route::get('/', [UserLoanController::class, 'index'])->name('index');
        Route::get('/add', [UserLoanController::class, 'add'])->name('add');
        Route::post('/create', [UserLoanController::class, 'doCreate'])->name('create');
        Route::get('/detail/{id}', [UserLoanController::class, 'detail'])->name('detail');
        Route::get('/update/{id}', [UserLoanController::class, 'update'])->name('update');
        Route::post('/update/{id}', [UserLoanController::class, 'doUpdate'])->name('do_update');
        Route::delete('/delete/{id}', [UserLoanController::class, 'delete'])->name('delete');
        Route::patch('/{id}/returning', [UserLoanController::class, 'returning'])->name('returning');
        Route::get('/scan', [UserLoanController::class, 'scan'])->name('scan');
    });

    Route::prefix('fines')->name('fines.')->group(function () {
        Route::get('/', [UserFineController::class, 'index'])->name('index');
        Route::get('/pay/{id}', [UserFineController::class, 'pay'])->name('pay');
        Route::get('/{id}/paid-report', [UserFineController::class, 'downloadPaidReport'])->name('paid-report');
    });
    
});
