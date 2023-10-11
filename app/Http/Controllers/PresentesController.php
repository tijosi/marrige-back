<?php

namespace App\Http\Controllers;

use App\Models\Historico;
use App\Models\Presentes;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use DateTimeZone;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PresentesController extends Controller {

    const MYSQL_DATE_FORMAT = 'Y-m-d';
    const MYSQL_DATETIME_FORMAT = 'Y-m-d H:i:s';

    public function getPresentes() {
        $presentes = Presentes::all();

        $varPontos = 12;

        foreach ($presentes as $key) {
            $key->path = asset('images/presentes/' . $key->name_img);
            $key->valor = ($key->valor_min + $key->valor_max)/2;
            $key->pontos = $key->valor/$varPontos;
        }

        return $presentes->toArray();
    }

    public function confirmPresente(Request $request) {

        $record = Presentes::find($request->id);

        if ($record->flg_disponivel != 1) throw new Exception('Item já foi Selecionado');

        $user = User::find(Auth::user()->id);

        $record->flg_disponivel     = 0;
        $record->name_selected      = $user->name;
        $record->selected_at        = $this->toMySQL('now', true);
        $record->save();

        $historico = new Historico();
        $historico->title       = 'Presente Selecionado';
        $historico->user_name   = $user->name;
        $historico->body        = 'Confirmou o presente <b>' .  $request->nome . '</b>. Vamos Comemorar!';
        $historico->created_at  = $this->toMySQL('now', true);
        $historico->save();

        $user->pontos += $request->pontos;
        $user->save();

        return $historico;
    }

    public static function toMySQL($date, $time = FALSE, $fromTimeZone = 'UTC', $toTimeZone = 'America/Sao_Paulo') {
        if (empty(trim($date))) return NULL;
        $format = $time ? self::MYSQL_DATETIME_FORMAT : self::MYSQL_DATE_FORMAT;

        $dt = new DateTime($date, new DateTimeZone($fromTimeZone));

        $dt->setTimezone(new DateTimeZone($toTimeZone));

        return $dt->format($format);
    }
}
