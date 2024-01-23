<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ConfigurationController;
use App\Http\Controllers\DepartementController;
use App\Http\Controllers\EmployerController;
use App\Http\Controllers\ProfileController;
use App\Models\Configuration;
use App\Models\Departement;
use App\Models\Employer;
use App\Models\User;
use Carbon\Carbon;
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

Route::get('/', function () {
    return view('home');
});

Route::get('/dashboard', function () {
    $defaultPayementDate = null;
    $payementNotification = '';

    $currentDate = Carbon::now()->day;
    
    $defaultPayementDateQuery = Configuration::where('type', 'PAYEMENT_DATE')->first();
    
    if ($defaultPayementDateQuery) {
        $defaultPayementDate = $defaultPayementDateQuery->value;
        $convertedPayementDate = intval($defaultPayementDate);
        
        if ($currentDate < $convertedPayementDate) {
            $payementNotification = 'Le paiement doit avoir lieu le '. $defaultPayementDate .' de ce mois.';
        } else {
            $nexMonth = Carbon::now()->addMonth();
            $nexMonthName = $nexMonth->format('F');

            $payementNotification = 'Le paiement doit avoir lieu le '. $defaultPayementDate .' du mois de '. $nexMonthName .' .';
        }
        
    }

    return view('dashboard', [
        'totalDepartements' => Departement::all()->count(),
        'totalEmployers' => Employer::all()->count(),
        'totalAdministrateurs' => User::all()->count(),
        'paymentNotification' => $payementNotification,
        // $appName = Configuration::where('type', 'APP_NAME')->first(),
        
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->prefix('departements')->name('departement.')->controller(DepartementController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/create', 'create')->name('create');
    Route::post('/create', 'store')->name('store');
    Route::get('/edit/{departement}', 'edit')->name('edit');
    Route::put('/edit/{departement}', 'update')->name('update');
    Route::get('/{departement}', 'delete')->name('delete');

});

Route::middleware('auth')->prefix('employers')->name('employer.')->controller(EmployerController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/create', 'create')->name('create');
    Route::post('/create', 'store')->name('store');
    Route::get('/edit/{employer}', 'edit')->name('edit');
    Route::put('/edit/{employer}', 'update')->name('update');
    Route::get('/{employer}', 'delete')->name('delete');
});

Route::prefix('configurations')->name('configuration.')->controller(ConfigurationController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/create', 'create')->name('create');
    Route::post('/create', 'store')->name('store');
    Route::get('/{config}', 'delete')->name('delete');
});

Route::prefix('administrateurs')->name('administrateur.')->controller(AdminController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/create', 'create')->name('create');
    Route::post('/create', 'store')->name('store');
    Route::get('/edit/{administrateur}', 'edit')->name('edit');
    Route::put('/edit/{administrateur}', 'update')->name('update');
    Route::get('/{administrateur}', 'delete')->name('delete');
});

Route::prefix('/validate-account/{email}', [AdminController::class, 'defineAccess']);
Route::prefix('/validate-account/{email}', [AdminController::class, 'submitDefineAccess'])->name('submitDefineAccess');

require __DIR__.'/auth.php';
