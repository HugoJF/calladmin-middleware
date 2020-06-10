<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Events\CommentCreated;
use App\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Report $report)
    {
        $comment = Comment::make();

        $comment->comment = $request->input('comment');
        $comment->report()->associate($report);
        $comment->user()->associate(Auth::user());

        $comment->save();

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
