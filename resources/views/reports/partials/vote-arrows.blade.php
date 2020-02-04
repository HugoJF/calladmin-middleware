@php
    $score = $report->score;
    $vote = $report->votes->where('user_id', auth()->id())->first();

    $voteUp = 'secondary';
    $voteDown = 'secondary';

    if($vote) {
        if($vote->type === true) {
            $voteUp = 'success';
        } else if($vote->type === false) {
            $voteDown = 'danger';
        }
    }
@endphp

<div class="col d-flex flex-grow-0 align-items-center flex-column justify-content-center">
    @auth
        <h1 title="Vote report as CORRECT">
            <a class="text-{{ $voteUp }} text-decoration-none" href="#" data-toggle="modal" data-target="#report-vote-up-{{ $report->id }}">
                <i class="fas fa-chevron-up"></i>
            </a>
        </h1>
    @endauth
    <h1 title="{{ $report->votes->count() }} total votes" class="text-{{ $score == 0 ? 'dark' : ($score < 0 ? 'danger' : 'success') }}">{{ $score }}</h1>
    @auth
        <h1 title="Vote report as INCORRECT">
            <a class="text-{{ $voteDown }} text-decoration-none" href="#" data-toggle="modal" data-target="#report-vote-down-{{ $report->id }}">
                <i class="fas fa-chevron-down"></i>
            </a>
        </h1>
    @endauth
</div>
