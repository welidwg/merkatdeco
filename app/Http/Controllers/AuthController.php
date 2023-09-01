<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //

    function Auth(Request $req)
    {
        try {
            $cred = ["login" => $req->login, "password" => $req->password];
            if (Auth::attempt($cred)) {
                return response(json_encode(["type" => "success", "message" => "Bien connecté", "user" => Auth::user(), "role" => Auth::user()->role]), 200);
            } else {
                return response(json_encode(["type" => "error", "message" => "Login ou mot de passe non valides !"]), 500);
            }
        } catch (\Throwable $th) {
            return response(json_encode(["type" => "error", "message" => $th->getMessage()]), 500);
        }
    }
}
