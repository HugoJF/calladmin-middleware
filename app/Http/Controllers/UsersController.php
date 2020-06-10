<?php

namespace App\Http\Controllers;

use App\Forms\UserSettingsForm;
use App\User;
use Illuminate\Http\Request;
use Kris\LaravelFormBuilder\FormBuilder;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::with(['reports', 'targets', 'votes', 'votes.report'])->paginate(5);

        return view('users.index', [
            'users' => $users,
        ]);
    }

    public function admin(User $user)
    {
        $user->admin = !((bool) $user->admin);

        $user->save();

        if ($user->admin) {
            flash()->success("User {$user->steamid} is now admin!");
        } else {
            flash()->success("User {$user->steamid} is now regular user!");
        }

        return back();
    }

    public function ban(User $user)
    {
        $user->banned = !((bool) $user->banned);

        $user->save();

        if ($user->banned) {
            flash()->success("User {$user->steamid} is now banned!");
        } else {
            flash()->success("User {$user->steamid} is now unbanned!");
        }

        return back();
    }

    public function ignoreReports(User $user)
    {
        $user->ignore_reports = !((bool) $user->ignore_reports);

        $user->save();

        if ($user->ignore_reports) {
            flash()->success("Reports from {$user->steamid} will be ignored!");
        } else {
            flash()->success("Reports from {$user->steamid} will be accepted!");
        }

        return back();
    }

    public function ignoreTargets(User $user)
    {
        $user->ignore_targets = !((bool) $user->ignore_targets);

        $user->save();

        if ($user->ignore_targets) {
            flash()->success("Reports that target {$user->steamid} as target will be ignored!");
        } else {
            flash()->success("Reports that target {$user->steamid} as target will be accepted!");
        }

        return back();
    }

    public function settings(FormBuilder $builder)
    {
        $form = $builder->create(UserSettingsForm::class, [
            'method' => 'PATCH',
            'model'  => auth()->user(),
            'url'    => route('users.settings.update'),
        ]);

        return view('form', [
            'title'       => 'User settings',
            'form'        => $form,
            'submit_text' => 'Update',
        ]);
    }

    public function updateSettings(Request $request)
    {
        $user = auth()->user();

        $user->fill($request->only(['email']));
        $user->save();

        flash()->success('Email updated!');

        return redirect()->route('home');
    }
}
