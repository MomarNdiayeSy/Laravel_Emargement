<?php
use App\Http\Controllers\UserController;
use App\Http\Controllers\SalleController;
use App\Http\Controllers\CoursController;
use App\Http\Controllers\EmargementController;
use App\Http\Controllers\RapportController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

Route::get('/', function () {
    if (auth()->check()) {
        $role = auth()->user()->role;
        switch ($role) {
            case 'admin':
                return redirect()->route('admin.users.index');
            case 'professeur':
                return redirect()->route('professeur.cours.index');
            case 'gestionnaire':
                return redirect()->route('gestionnaire.cours.index');
            default:
                return redirect()->route('login');
        }
    }
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/test-auth', function () {
        return 'Connecté : ' . auth()->user()->email . ' (Rôle : ' . auth()->user()->role . ')';
    })->name('test-auth');

    Route::prefix('admin')->middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class)->names('admin.users');
        Route::resource('salles', SalleController::class)->names('admin.salles');
        Route::resource('cours', CoursController::class)->names('admin.cours');
        Route::resource('emargements', EmargementController::class)->names('admin.emargements');
        Route::resource('notifications', NotificationController::class)->names('admin.notifications');
        Route::get('/rapports', [RapportController::class, 'index'])->name('admin.rapports.index');
        Route::get('/rapports/statistiques', [RapportController::class, 'statistiques'])->name('admin.rapports.statistiques');
        Route::get('/rapports/export-pdf', [RapportController::class, 'exportPdf'])->name('admin.rapports.export.pdf');
        Route::get('/rapports/export-excel', [RapportController::class, 'exportExcel'])->name('admin.rapports.export.excel');
    });

    Route::prefix('professeur')->middleware('role:professeur')->group(function () {
        Route::get('/cours/{cours}', [CoursController::class, 'show'])->name('professeur.cours.show');
        Route::get('/cours', [CoursController::class, 'index'])->name('professeur.cours.index');
        Route::get('/emargements', [EmargementController::class, 'index'])->name('professeur.emargements.index');
        Route::get('/emargements/create', [EmargementController::class, 'create'])->name('professeur.emargements.create');
        Route::get('/emargements/{emargement}', [EmargementController::class, 'show'])->name('professeur.emargements.show');
        Route::post('/emargements', [EmargementController::class, 'store'])->name('professeur.emargements.store');
        Route::get('/emargements/{emargement}/edit', [EmargementController::class, 'edit'])->name('professeur.emargements.edit');
        Route::put('/emargements/{emargement}', [EmargementController::class, 'update'])->name('professeur.emargements.update');
        Route::get('/rapports', [RapportController::class, 'index'])->name('professeur.rapports.index');
        Route::get('/rapports/statistiques', [RapportController::class, 'statistiques'])->name('professeur.rapports.statistiques');
        Route::get('/rapports/export-pdf', [RapportController::class, 'exportPdf'])->name('professeur.rapports.export.pdf');
        Route::get('/rapports/export-excel', [RapportController::class, 'exportExcel'])->name('professeur.rapports.export.excel');
    });

    Route::prefix('gestionnaire')->middleware('role:gestionnaire')->group(function () {
        Route::resource('cours', CoursController::class)->names('gestionnaire.cours');
        Route::get('/rapports', [RapportController::class, 'index'])->name('gestionnaire.rapports.index');
        Route::get('/rapports/statistiques', [RapportController::class, 'statistiques'])->name('gestionnaire.rapports.statistiques');
        Route::get('/rapports/export-pdf', [RapportController::class, 'exportPdf'])->name('gestionnaire.rapports.export.pdf');
        Route::get('/rapports/export-excel', [RapportController::class, 'exportExcel'])->name('gestionnaire.rapports.export.excel');
    });
});

Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// Routes pour Register
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/admin/emargements/{id}/validate', [App\Http\Controllers\EmargementController::class, 'validateEmargement'])->name('admin.emargements.validate');

//passwords
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
