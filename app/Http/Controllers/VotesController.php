<?php

namespace App\Http\Controllers;

use App\Report;
use Illuminate\Support\Facades\Auth;

class VotesController extends Controller
{
    public function index()
    {
        if (auth()->user()->admin) {
            return $this->userIndex();
        } else {
            return $this->userIndex();
        }
    }

    protected function userIndex()
    {
        $reports = Report::whereHas('votes', function ($query) {
            $query->where('user_id', '=', auth()->id());
        })->paginate(5);

        return view('reports.index', [
            'reports' => $reports,
        ]);
    }
}
