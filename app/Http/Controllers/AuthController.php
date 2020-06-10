<?php

namespace App\Http\Controllers;

use App\Classes\SteamID;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Invisnik\LaravelSteamAuth\SteamAuth;

class AuthController extends Controller
{
    /**
     * The SteamAuth instance.
     *
     * @var SteamAuth
     */
    protected $steam;
    /**
     * The redirect URL.
     *
     * @var string
     */
    protected $redirectURL = '/dashboard';

    /**
     * AuthController constructor.
     *
     * @param SteamAuth $steam
     */
    public function __construct(SteamAuth $steam)
    {
        $this->steam = $steam;
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('home');
    }

    public function login(Request $request)
    {
        if ($this->steam->validate()) {
            $info = $this->steam->getUserInfo();
            if (!is_null($info)) {
                $user = $this->findOrNewUser($info);

                if ($user === null) {
                    return 'Banned from system';
                }

                Auth::login($user, true);

                $previous = $request->session()->pull('redirect');
                if ($previous) {
                    return redirect($previous);
                } else {
                    return redirect($this->redirectURL); // redirect to site
                }
            }
        }

        return $this->redirectToSteam();
    }

    /**
     * Getting user by info or created if not exists.
     *
     * @param $info
     *
     * @return User
     */
    protected function findOrNewUser($info)
    {
        $id = SteamID::normalizeSteamID64($info->steamID64);
        $user = User::where('steamid', $id)->first();

        if (!is_null($user)) {
            return $user;
        }

        $user = User::make([
            'username' => $info->personaname,
            'avatar'   => $info->avatarfull,
        ]);
        $user->steamid = $id;

        $user->save();

        return $user;
    }

    /**
     * Redirect the user to the authentication page.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function redirectToSteam(Request $request)
    {
        $request->session()->flash('redirect', url()->previous());

        return $this->steam->redirect();
    }
}
