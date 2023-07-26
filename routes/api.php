<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\PresentesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



// Faz Login do Usuário
Route::post('/login', function( Request $request ) {
    $credentials = $request->only('name', 'password');

    if (!$token = auth()->attempt($credentials)) {
        return response()->json(['message' => 'Uauário Não Autorizado'], 401);
    }

    return response()->json([
        'token' => $token,
        'token_type' => 'bearer',
        'expires_in' => auth()->factory()->getTTl() * 60
    ]);
});


Route::middleware(['valida.token'])->group(function () {

    # ADMIN
    Route::post('/update-user', [AdminController::class, 'updateUser']);
    Route::post('/create-user', [AdminController::class, 'createUser']);

    // Valida o Token enviado
    Route::get('/valida-token', function () { return response()->json(true); });

    # PRESENTES
    Route::get('/presentes', [PresentesController::class, 'getPresentes']);
});


