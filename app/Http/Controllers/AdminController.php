<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{

    public function updateUser (Request $request) {

        $request->validateWithBag('Validação Parâmetros', [
            'id' => ['required'],
            'name' => ['unique:users']
        ]);

        $fields = DB::select('DESC users');
        $update = [];
        foreach ($fields as $key) {
            if ($request->input($key->Field) && $key->Field != "id")

            if ($key->Field == "password") {

                $update[$key->Field] = bcrypt($request->input($key->Field));

            } else {

                $update[$key->Field] = $request->input($key->Field);

            }
        };


        $user = DB::table('users')
                    ->where('id', $request->input('id'))
                    ->update($update);

        $user = DB::table('users')->where('id', $request->input('id'))->get();

        return response()->json($user);
    }

    public function createUser (Request $request) {

        $roles = DB::table('roles')->select('id')->get();
        $roles = $roles->pluck('id');

        $request->validate([
            'name' => ['required', 'unique:users'],
            'password' => ['required'],
            'role' => ['required', Rule::in($roles)],
        ]);

        $user = new User();
        $user->name = $request->input('name');
        $user->password = bcrypt($request->input('password'));
        $user->role_id = $request->input('role');
        $user->save();

        return response()->json($user);

    }
}
