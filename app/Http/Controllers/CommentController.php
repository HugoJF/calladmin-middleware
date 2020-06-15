<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Events\CommentCreated;
use App\Report;
use App\Services\CommentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(CommentService $service, Request $request, Report $report)
    {
        $comment = $service->create($report, $request->input());

        event(new CommentCreated($comment));

        flash()->success('Comment saved successfully!');

        return back();
    }

    public function destroy(Report $report, Comment $comment)
    {
        $comment->delete();

        flash()->success('Comment was deleted successfully!');

        return back();
    }
}
