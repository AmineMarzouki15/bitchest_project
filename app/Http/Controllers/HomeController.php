<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (!session()->has('user_role')) {
            $role       =   array_key_first(Auth::user()->user_role);
            session()->put('user_role', $role);
        }
        if (!session()->has('balance')) {
            $balance    =   Auth::user()->balance;
            session()->put('balance', $balance);
        }
        return view('home');
    }
}
