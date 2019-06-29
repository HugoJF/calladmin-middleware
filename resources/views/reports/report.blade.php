@php
    $score = $report->score;
    $vote = $report->votes->where('user_id', Auth::id())->first();

    $voteUp = 'secondary';
    $voteDown = 'secondary';

    if($vote) {
        if($vote->type === true) {
            $voteUp = 'success';
        } else if($vote->type === false) {
            $voteDown = 'danger';
        }
    }
    
    $badges = [
        -1 => 'danger',
        0 => 'dark',
        1 => 'success',
    ];
@endphp

<div class="row border {{ $report->acked_at ? '' : 'border-primary' }} rounded bg-light p-3 mb-3">
    <div class="col-12 mb-4 d-flex">
        <div class="col d-flex align-items-center flex-column justify-content-center">
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
        <div class="col d-flex" style="flex-grow: 20; flex-flow: column;">
            <div class="row flex-grow-1">
                <div class="col">
                    <h5 class="mb-2">Reporter: @include('ui.badge', ['number' => $report->reporter->karma])</h5>
                    <p class="mb-0">{{ $report->reporter_name }}</p>
                    <pre class="text-muted ml-2">{{ $report->reporter_steam_id }}</pre>
                    <p>
                        Reason: <code>{{ $report->reason }}</code>
                    </p>
                </div>
                <div class="col">
                    <h5 class="mb-2">Target: @include('ui.badge', ['number' => $report->target->karma])</h5>
                    <p class="mb-0">{{ $report->target_name }}</p>
                    <pre class="text-muted ml-2">{{ $report->target_steam_id }}</pre>
                    <p>
                        Server: <code>{{ $report->server_ip }}:{{ $report->server_port }}</code>
                    </p>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col">
                    <h4>Final decision:
                        @if($report->correct)
                            <span class="badge badge-success">CORRECT</span>
                        @elseif($report->incorrect)
                            <span class="badge badge-danger">INCORRECT</span>
                        @elseif($report->ignored    )
                            <span class="badge badge-warning">IGNORED</span>
                        @else
                            <span class="badge badge-dark">PENDING</span>
                        @endif
                    </h4>
                </div>
                <div class="col">
                    <p class="pb-0 mb-1">
                        <a href="{{ route('reports.show', $report) }}">
                            <small title="{{ $report->created_at->toRfc7231String() }}" class="text-muted">
                                Created at: {{ $report->created_at->diffForHumans() }}
                            </small>
                        </a>
                    </p>
                    @auth
                        @if($report->reporter_id === Auth::id() && $report->decision === 0 && is_null($report->acked_at))
                            <a class="btn btn-warning"
                               title="#"
                               href="{{ route('my-reports.ack', $report) }}"
                            >Ack</a>
                        @endif
                        
                        <a class="btn btn-primary"
                           title="{{ $report->demoFilename }}"
                           href="{{ $report->demoUrl }}"
                        >Download demo</a>
                        
                        @if(Auth::user()->admin)
                            <a class="btn btn-outline-primary{{ $report->decided ? ' disabled' : '' }}"
                               href="#"
                               data-toggle="modal"
                               data-target="#report-attach-video-{{ $report->id }}"
                            >Attach video</a>
                            <a class="btn btn-outline-success{{ $report->decided ? ' disabled' : '' }}"
                               href="#"
                               data-toggle="modal"
                               data-target="#report-decision-correct-{{ $report->id }}"
                            >Correct</a>
                            <a class="btn btn-outline-danger{{ $report->decided ? ' disabled' : '' }}"
                               href="#"
                               data-toggle="modal"
                               data-target="#report-decision-incorrect-{{ $report->id }}"
                            >Incorrect</a>
                            <a class="btn btn-outline-warning{{ $report->decided ? ' disabled' : '' }}"
                               href="#"
                               data-toggle="modal"
                               data-target="#report-ignore-{{ $report->id }}"
                            >Ignore</a>
                            <a class="btn btn-outline-danger{{ $report->decided ? ' disabled' : '' }}"
                               href="#"
                               data-toggle="modal"
                               data-target="#report-delete-{{ $report->id }}"
                            >Delete</a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>
    @if($report->video_url)
        <div class="col-12">
            <h3>Demo recording</h3>
            <div class="p-4 w-100">
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe
                            id="ytplayer"
                            type="text/html"
                            width="100%"
                            allowfullscreen
                            src="https://www.youtube.com/embed/{{ $report->video_url }}?autoplay=0&rel=0&showinfo=0"
                            frameborder="0"
                    >
                    </iframe>
                </div>
            </div>
        </div>
    @endif
    
    @if(Auth::check())
        <div class="col-12">
            <h3>Comments</h3>
            <div>
                @foreach ($report->comments as $comment)
                    @include('comments.comment', ['comment' => $comment])
                @endforeach
            </div>
            {!! Form::open(['url' => route('reports.comments.store', $report), 'method' => 'POST']) !!}
            <div class="input-group mt-2 mb-1">
                <textarea rows="1" name="comment" class="form-control" placeholder="Write a comment..." aria-label="comment"></textarea>
                <div class="input-group-append">
                    <button class="btn btn-outline-primary" type="submit" id="comment-button">Submit comment</button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    @endif
</div>

@php
    $minute = 60;
    $hour = 60 * $minute;
    $day = 24 * $hour;
    $month = 30 * $day;
    
    $durationsHours = [
        [
            'title' => '1 hour',
            'duration' => $hour,
        ],
        [
            'title' => '2 hours',
            'duration' => 2 * $hour,
        ],
        [
            'title' => '4 hours',
            'duration' => 4 * $hour,
        ],
        [
            'title' => '12 hours',
            'duration' => 12 * $hour,
        ],
    ];

    $durationsDays = [
        [
            'title' => '1 day',
            'duration' => $day,
        ],
        [
            'title' => '2 days',
            'duration' => 2 * $day,
        ],
        [
            'title' => '3 days',
            'duration' => 3 * $day,
        ],
        [
            'title' => '4 days',
            'duration' => 4 * $day,
        ],
        [
            'title' => '7 days',
            'duration' => 7 * $day,
        ],
        [
            'title' => '14 days',
            'duration' => 14 * $day,
        ],
    ];

    $durationMonths = [
        [
            'title' => '1 month',
            'duration' => 1 * $month,
        ],
        [
            'title' => '2 months',
            'duration' => 2 * $month,
        ],
        [
            'title' => '3 months',
            'duration' => 3 * $month,
        ],
        [
            'title' => '4 months',
            'duration' => 4 * $month,
        ],
        [
            'title' => 'Permanent',
            'duration' => 0,
        ]
    ];
    
    $durations = [$durationsHours, $durationsDays, $durationMonths];
@endphp

@if(Auth::check() && Auth::user()->admin)
    @push('modals')
        <div class="modal fade" id="report-attach-video-{{ $report->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                {!! Form::open(['url' => route('reports.attach-video', $report), 'method' => 'PATCH']) !!}
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Final decision for report {{ $report->id }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h6>Attach video to report {{ $report->id }}</h6>
                        <div class="form-group">
                            <input name="url" class="form-control" type="text" placeholder="Video URL">
                        </div>
                    </div>
                    <div class="modal-footer border-top-0">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    @endpush
@endif

@push('modals')
    <div class="modal fade" id="report-decision-correct-{{ $report->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            {!! Form::open(['url' => route('reports.decision', $report), 'method' => 'PATCH']) !!}
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Final decision for report {{ $report->id }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h6>Ban duration</h6>
                    <div class="row">
                        @foreach ($durations as $a => $durationGroups)
                            <div class="col-4">
                                @foreach ($durationGroups as $b => $duration)
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="duration-{{ $report->id }}-{{ $a . '-' . $b }}" name="duration" value="{{ $duration['duration'] }}" class="custom-control-input">
                                        <label class="custom-control-label" for="duration-{{ $report->id }}-{{ $a . '-' . $b }}">{{ $duration['title'] }}</label>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                    <br>
                    <h6>Final reason</h6>
                    <div class="form-group">
                        <input name="reason" class="form-control" type="text" placeholder="Reason">
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    {!! Form::hidden('decision', 'correct') !!}
                    <button type="submit" class="btn btn-primary">Correct</button>
                    
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endpush

@push('modals')
    <div class="modal fade" id="report-decision-incorrect-{{ $report->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Final decision for report {{ $report->id }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-footer border-top-0">
                    {!! Form::open(['url' => route('reports.decision', $report), 'method' => 'PATCH']) !!}
                    {!! Form::hidden('decision', 'incorrect') !!}
                    <button type="submit" class="btn btn-danger">Incorrect</button>
                    {!! Form::close() !!}
                    
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('modals')
    <div class="modal fade" id="report-ignore-{{ $report->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Deleting report {{ $report->id }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to ignore report {{ $report->id }}?
                </div>
                <div class="modal-footer">
                    {!! Form::open(['url' => route('reports.ignore', $report), 'method' => 'PATCH']) !!}
                    <button type="submit" class="btn btn-danger">Ignore</button>
                    {!! Form::close() !!}
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('modals')
    <div class="modal fade" id="report-delete-{{ $report->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Deleting report {{ $report->id }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete report {{ $report->id }}?
                </div>
                <div class="modal-footer">
                    {!! Form::open(['url' => route('reports.delete', $report), 'method' => 'DELETE']) !!}
                    <button type="submit" class="btn btn-danger">Delete</button>
                    {!! Form::close() !!}
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('modals')
    <div class="modal fade" id="report-vote-up-{{ $report->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Voting report {{ $report->id }} as CORRECT</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to vote report {{ $report->id }} as <strong>CORRECT</strong>?
                </div>
                <div class="modal-footer">
                    {!! Form::open(['url' => route('reports.vote', $report), 'method' => 'POST']) !!}
                    {!! Form::hidden('decision', 'correct') !!}
                    <button type="submit" class="btn btn-primary">This report is CORRECT</button>
                    {!! Form::close() !!}
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('modals')
    <div class="modal fade" id="report-vote-down-{{ $report->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Voting report {{ $report->id }} as INCORRECT</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to vote report {{ $report->id }} as <strong>INCORRECT</strong>?
                </div>
                <div class="modal-footer">
                    {!! Form::open(['url' => route('reports.vote', $report), 'method' => 'POST']) !!}
                    {!! Form::hidden('decision', 'incorrect') !!}
                    <button type="submit" class="btn btn-danger">This report is incorrect</button>
                    {!! Form::close() !!}
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
@endpush
