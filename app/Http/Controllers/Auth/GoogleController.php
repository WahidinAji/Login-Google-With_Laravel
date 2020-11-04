<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Model\Player;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Exception;
use App\User;
use Illuminate\Support\Facades\DB;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }
    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
            // \dd($user);
            // $finduser = User::where('google_id', $user->id)->get();
            $finduser = DB::table('users')
                ->where('google_id', $user->id)->first();
            // \dd($finduser);
            if ($finduser) {

                Auth::login($finduser);

                return redirect('/home');
            } else {
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'google_id' => $user->id,
                    'password' => encrypt('Superman_test')
                ]);

                Auth::login($newUser);

                return redirect('/home');
            }
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }

    public function callback()
    {

        // jika user masih login lempar ke home
        if (Auth::check()) {
            return redirect('/home');
        }

        $oauthUser = Socialite::driver('google')->user();
        $user = User::where('google_id', $oauthUser->id)->first();
        if ($user) {
            Auth::loginUsingId($user->id);
            return redirect('/home');
        } else {
            $newUser = User::create([
                'name' => $oauthUser->name,
                'email' => $oauthUser->email,
                'google_id' => $oauthUser->id,
                // password tidak akan digunakan ;)
                'password' => md5($oauthUser->token),
            ]);
            Auth::login($newUser);
            return redirect('/home');
        }
    }
    public function callbackPlayer()
    {

        // jika user masih login lempar ke home
        if (Auth::guard('player')->check()) {
            return redirect('/home');
        }

        $oauthUser = Socialite::driver('google')->user();
        $player = Player::where('google_id', $oauthUser->id)->first();
        if ($player) {
            Auth::loginUsingId($player->id);
            return redirect('/home');
        } else {
            $newUser = Player::create([
                'google_id' => $oauthUser->id,
                'name' => $oauthUser->name,
                'email' => $oauthUser->email,
                'ava_url' => $oauthUser->avatar,
                // password tidak akan digunakan ;)
                'password' => md5($oauthUser->token),
            ]);
            Auth::login($newUser);
            return redirect('/home');
        }
    }
}
