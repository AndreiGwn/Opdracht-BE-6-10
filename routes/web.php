<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\InstructeurController;

Route::get('/', function () {
    return redirect()->route('instructeur.index');
});

Route::get('/instructeur', [InstructeurController::class, 'index'])->name('instructeur.index');
Route::get('/instructeur/{id}/edit', [InstructeurController::class, 'edit'])->name('instructeur.edit');
Route::put('/instructeur/{id}', [InstructeurController::class, 'update'])->name('instructeur.update');
Route::get('/instructeur/{id}/voertuigen', [InstructeurController::class, 'voertuigen'])->name('instructeur.voertuigen');
Route::post('/instructeur/{id}/toggle-status', [InstructeurController::class, 'toggleStatus'])->name('instructeur.toggle-status');
Route::delete('/instructeur/{id}', [InstructeurController::class, 'destroy'])->name('instructeur.destroy');

// Vehicle assignments (Opdracht 8)
Route::get('/voertuigen/beschikbaar', [InstructeurController::class, 'beschikbaarVoertuigen'])->name('voertuigen.beschikbaar');
Route::post('/instructeur/{instructeur_id}/voertuigen/assign', [InstructeurController::class, 'assignVoertuig'])->name('instructeur.voertuigen.assign');
Route::delete('/instructeur/{instructeur_id}/voertuigen/{voertuig_id}/release', [InstructeurController::class, 'releaseVoertuig'])->name('instructeur.voertuigen.release');
Route::post('/instructeur/{instructeur_id}/voertuigen/{voertuig_id}/reassign', [InstructeurController::class, 'reassignVoertuig'])->name('instructeur.voertuigen.reassign');
