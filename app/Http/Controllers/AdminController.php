<?php

namespace App\Http\Controllers;

use App\Models\Historico;
use App\Models\Presentes;
use App\Models\User;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    const MYSQL_DATE_FORMAT = 'Y-m-d';
    const MYSQL_DATETIME_FORMAT = 'Y-m-d H:i:s';

    public function updateUser (Request $request) {

        $request->validateWithBag('Validação Parâmetros', [
            'id' => ['required']
        ]);

        $user = User::where('id', $request->input('id'))->first();

        $fields = DB::select('DESC users');
        $update = false;
        foreach ($fields as $key) {
            $key = $key->Field;

            if ($request->input($key) && $key != "id" && $user->$key != $request->input($key)) {
                $user->$key = $request->input($key);
                $update = true;
            }
        };

        if ($update) return response()->json('Sem alterações', 200);

        else $user->save();

        return response()->json($user);
    }

    public function createUser (Request $request) {

        $roles = DB::table('roles')->select('id')->get()->pluck('id');

        $request->validate([
            'name' => ['required'],
            'chave' => ['required', 'unique:users'],
            'role' => ['required', Rule::in($roles)],
        ]);

        $user = new User();
        $user->name = $request->input('name');
        $user->chave = $request->input('chave');
        $user->role_id = $request->input('role');
        $user->save();

        return response()->json($user);
    }

    public function getNotificacao (Request $request) {

        if (isset($request->id)) {

            $registro = Historico::find($request->id);

            $registro->entregue = 1;
            if (isset($request->visto)) $registro->visto = 1;
            $registro->save();

            return response()->json($registro);

        } else {

            $notificacoes = Historico::where('visto', '0')->get();

            return response()->json($notificacoes);
        }

        return false;
    }

    public function presentes(Request $request) {

        $presentes = Presentes::where('flg_disponivel', '0')->get();

        $varPontos = 12;

        foreach ($presentes as $key) {
            $key->path = asset('images/presentes/' . $key->name_img);
            $key->valor = ($key->valor_min + $key->valor_max)/2;
            $key->pontos = $key->valor/$varPontos;
        }

        return $presentes->toArray();
    }

    public function desvincular(Request $request) {

        $registro = Presentes::find($request->id);
        $registroClone = clone $registro;

        $registro->flg_disponivel = 1;
        $registro->name_selected = null;
        $registro->selected_at = null;
        $registro->save();

        $historico = new Historico();
        $historico->title       = 'Presente Desvinculado';
        $historico->user_name   = Auth::user()->name;
        $historico->body        = 'Desvinculou o presente <b>' .  $registro->nome . '</b> que estava em posse do(a): <i>'. $registroClone->name_selected .'</i>.';
        $historico->created_at  = $this->toMySQL('now', true);
        $historico->save();

        return $registro->toArray();
    }

    public static function toMySQL($date, $time = FALSE, $fromTimeZone = 'UTC', $toTimeZone = 'America/Sao_Paulo') {
        if (empty(trim($date))) return NULL;
        $format = $time ? self::MYSQL_DATETIME_FORMAT : self::MYSQL_DATE_FORMAT;

        $dt = new DateTime($date, new DateTimeZone($fromTimeZone));

        $dt->setTimezone(new DateTimeZone($toTimeZone));

        return $dt->format($format);
    }
}
