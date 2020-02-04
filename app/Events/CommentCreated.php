<?php

namespace App\Events;

use App\Comment;
use App\Contracts\NotifiesAssociatedUsers;
use App\Notifications\NewComment;
use App\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Support\Collection;

class CommentCreated implements NotifiesAssociatedUsers
{
    use Dispatchable, SerializesModels;

    /**
     * @var Comment
     */
    public $comment;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * @inheritDoc
     */
    public function getAssociatedUsers()
    {
        return admins()
            ->add($this->comment->report->reporter)
            ->add($this->comment->report->target);
    }

    /**
     * @inheritDoc
     */
    public function getNotification()
    {
        return new NewComment($this->comment);
    }
}
