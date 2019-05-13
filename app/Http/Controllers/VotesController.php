<?php

namespace App\Http\Controllers;

use App\Report;
use App\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VotesController extends Controller
{
	public function index()
	{
		if (Auth::user()->admin) {
			return $this->userIndex();
			//			return $this->adminIndex();
		} else {
			return $this->userIndex();
		}
	}

	protected function userIndex()
	{
		$reports = Report::whereHas('votes', function ($query) {
			$query->where('user_id', '=', Auth::id());
		})->paginate(5);

		return view('reports.index', [
			'reports' => $reports,
		]);
	}

	public function store()
	{

	}
}
