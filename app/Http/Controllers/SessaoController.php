<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessaoController extends Controller
{
    //
    public function dashboard(){
    $user=Auth::user();    
    if($user->hasRole('estudante')){
        return view('app.private.dashboard.estudantes.dashboard');
    }else {
                return view('app.private.dashboard.othes.dashboard');
                
        }
    }
    public function home(){
        return view('app.home.index');
    }
}
