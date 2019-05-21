<div class="media text-muted py-2">
    <img class="mr-2 rounded bg-primary" width="32" height="32" src="{{ $comment->user->avatar }}">
    <div class="media-body mb-0 small lh-125">
        <div class="d-flex justify-content-between mb-1">
            <strong class="text-dark">{{ '@' }}{{ $comment->user->username }}</strong>
            {!! Form::open(['url' => route('reports.comments.delete', ['report' => $report, 'comment' => $comment]), 'method' => 'DELETE', 'class' => 'd-inline']) !!}
            <button type="submit" class="btn btn-link btn-sm text-danger">Delete</button>
            {!! Form::close() !!}
        </div>
        {!! nl2br(e($comment->comment)) !!}
    </div>
</div>