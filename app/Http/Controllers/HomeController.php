<?php

namespace App\Http\Controllers;

use App\Model\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        $player = Player::all();
        $players = DB::table('players')
            // ->where('id', Auth::guard('player')->user())
            ->first();
        // \dd($players);
        return view('home', \compact('players'));
    }
}
