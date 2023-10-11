<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DiversosController extends Controller
{
    public function closePopupConfirmacao(Request $request) {

        $record = DB::table('PessoasConfirmadas')->where('user_id', Auth::id())->first();

        if (empty($record)) throw new Exception("Não existe registro para esse usuário");

        return $record;
    }
}
