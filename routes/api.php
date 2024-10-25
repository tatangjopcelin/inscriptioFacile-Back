<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/eleve', [AuthController::class, 'getEtudiant']);
Route::put('/eleve/update', [AuthController::class, 'updateEtudiant']);
Route::delete('/eleve/delete', [AuthController::class, 'deleteEtudiant']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
   


    
 
  