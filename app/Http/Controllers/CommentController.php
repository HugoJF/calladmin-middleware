<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Notifications\NewComment;
use App\Report;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Notification;

class CommentController extends Controller
{
    public function store(Request $request, Report $report)
    {
        $comment = Comment::make();

        $comment->comment = $request->input('comment');
        $comment->report()->associate($report);
        $comment->user()->associate(Auth::user());

        $comment->save();

        $admins = User::where('admin', true)->get();
        Notification::send($admins, new NewComment($comment));

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
