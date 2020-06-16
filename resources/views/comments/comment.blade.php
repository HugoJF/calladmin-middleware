<div class="media text-muted py-2">
    @if($comment->user->avatar)
        <img class="mr-2 rounded" width="32" height="32" src="{{ $comment->user->avatar }}">
    @else
        <svg class="mr-2 rounded" enable-background="new 0 0 512 512" viewBox="0 0 512 512" width="32" height="32" xmlns="http://www.w3.org/2000/svg">
            <g>
                <path d="m256 0c-141.159 0-256 114.841-256 256s114.841 256 256 256 256-114.841 256-256-114.841-256-256-256zm0 482c-124.617 0-226-101.383-226-226s101.383-226 226-226 226 101.383 226 226-101.383 226-226 226z"/>
                <path d="m256 270c57.897 0 105-47.103 105-105s-47.103-105-105-105-105 47.103-105 105 47.103 105 105 105zm0-180c41.355 0 75 33.645 75 75s-33.645 75-75 75-75-33.645-75-75 33.645-75 75-75z"/>
                <path d="m424.649 335.443c-19.933-22.525-48.6-35.443-78.649-35.443h-180c-30.049 0-58.716 12.918-78.649 35.443l-7.11 8.035 5.306 9.325c34.817 61.187 100.131 99.197 170.453 99.197s135.636-38.01 170.454-99.198l5.306-9.325zm-168.649 86.557c-55.736 0-107.761-28.197-138.383-74.295 13.452-11.352 30.579-17.705 48.383-17.705h180c17.804 0 34.931 6.353 48.383 17.705-30.622 46.098-82.647 74.295-138.383 74.295z"/>
            </g>
        </svg>
    @endif
    <div class="media-body mb-0 small lh-125">
        <div class="d-flex justify-content-between mb-1">
            <strong class="text-dark">{{ '@' }}{{ $comment->user->username }}</strong>
            {!! Form::open(['url' => route('reports.comments.delete', ['report' => $report, 'comment' => $comment]), 'method' => 'DELETE', 'class' => 'd-inline']) !!}
            @if(auth()->check() && (auth()->user()->admin === true || auth()->id() === $comment->user_id))
                <button type="submit" class="btn btn-link btn-sm text-danger">Delete</button>
            @endif
            {!! Form::close() !!}
        </div>
        {!! nl2br(e($comment->comment)) !!}
    </div>
</div>
