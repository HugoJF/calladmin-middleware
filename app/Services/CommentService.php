<?php

namespace App\Services;

use App\Comment;
use App\Report;

class CommentService
{
    public function create(Report $report, array $data)
    {
        $comment = new Comment;

        $comment->fill($data);

        $comment->report()->associate($report);
        $comment->user()->associate(auth()->user());

        $comment->save();

        return $comment;
    }
}
