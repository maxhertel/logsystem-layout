<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NetworkController extends Controller
{
    public function showMain() {
        return view('pages.main');
    }

    public function showUsers() {
        //return view('users');
    }

    public function showPppoe() {
        //return view('pppoe');
    }

    public function showIpoe() {
        //return view('ipoe');
    }

    public function showLogs() {
        //return view('logs');
    }

    public function logout() {
        // Logica de logout aqui
    }
}

