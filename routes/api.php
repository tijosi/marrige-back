<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DiversosController;
use App\Http\Controllers\PresentesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;

// Faz Login do Usuário
Route::post('/login', function( Request $request ) {

    $request->validateWithBag('Validação Parâmetros', [
        'telefone'              => ['required']
    ], [
        'telefone.required'     => 'O campo de telefone é obrigatório.'
    ]);

    $user = User::where('telefone', $request->telefone)->first();

    if (empty($user)) {
        return response()->json([ 'message' => 'Usuário Não Encontrado'], 401);
    }

    return response()->json([
        'token' => JWTAuth::fromUser($user),
        'token_type' => 'bearer'
    ]);

});

Route::get('/teste-deploy', function( Request $request ) {

    return response()->json([
        'status' => 'success',
        'data' => 'Deu Certo Moleque!'
    ]);

});


Route::middleware(['valida.token'])->group(function () {

    // Valida o Token enviado
    Route::get('/valida-token', function () { return response()->json(true); });

    Route::middleware(['valida.role'])->group(function () {

        // Valida se o Usuário é Admin
        Route::get('/valida-admin', function () { return response()->json(true); });

        # ADMIN
        Route::post('/create-user', [AdminController::class, 'createUser']);
        Route::post('/update-user', [AdminController::class, 'updateUser']);
        Route::get('/admin', [AdminController::class, 'getNotificacao']);
        Route::get('/admin/presentes', [AdminController::class, 'presentes']);
        Route::get('/admin/desvincular-presente', [AdminController::class, 'desvincular']);



    });

    # DIVERSOS
    Route::get('/close-popup-confirmacao', [DiversosController::class, 'closePopupConfirmacao']);

    # PRESENTES
    Route::get('/presentes', [PresentesController::class, 'getPresentes']);
    Route::post('/presentes/confirm-presente', [PresentesController::class, 'confirmPresente']);
});


