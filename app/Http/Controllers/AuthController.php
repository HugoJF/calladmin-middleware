<?php

namespace App\Http\Controllers;

use App\Classes\SteamID;
use App\User;
use App\UserService;
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

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        Auth::logout();

        return redirect()->route('home');
    }

    /**
     * @param UserService $service
     * @param Request     $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function login(UserService $service, Request $request)
    {
        if (!$this->steam->validate()) {
            return $this->redirectToSteam();
        }

        $info = $this->steam->getUserInfo();

        if (is_null($info)) {
            return $this->redirectToSteam();
        }

        $user = $service->findOrCreate([
            'steamid'  => $info->steamID64,
            'username' => $info->personaname,
            'avatar'   => $info->avatarfull,
        ]);

        if ($user === null) {
            return response()->json(['message' => 'BANNED'], 401);
        }

        auth()->login($user, true);

        if ($previous = $request->session()->pull('redirect')) {
            return redirect($previous);
        }

        return redirect($this->redirectURL); // redirect to site
    }

    /**
     * Redirect the user to the authentication page.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToSteam(Request $request)
    {
        $request->session()->flash('redirect', url()->previous());

        return $this->steam->redirect();
    }
}
