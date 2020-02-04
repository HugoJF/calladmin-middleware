@php
    $badges = [
        -1 => 'danger',
        0 => 'dark',
        1 => 'success',
    ];
@endphp

<div class="row border rounded bg-light p-3 mb-3">
    <div class="col-12 mb-2 d-flex">

        <!-- Vote arrows -->
        @include('reports.partials.vote-arrows')

        <!-- Main report body -->
        @include('reports.partials.body')
    </div>

    @if($report->player_data)
        <div class="col-6">
            <h3>Players</h3>
            <table class="table table-sm">
                <tbody>
                @foreach ($report->player_data ?? [] as $player)
                    <tr>
                        <td><code>{{ $player['steamid'] }}</code></td>
                        <td>{{ $player['name'] }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif

    @if($report->chat)
        <div class="col-6">
            <h3>Chat messages</h3>
            <ul class="pl-4 list-unstyled">
                @foreach ($report->chat as $chat)
                    @php
                        $message = preg_split('/:/', $chat['message'])
                    @endphp
                    @if(count($message) === 2)
                        <li class="my-1"><strong>{{ preg_replace('/\*DEAD\*|\(Counter-Terrorist\)\s|\(Terrorist\)\s/', '', $message[0]) }}: </strong>{{ $message[1] }}</li>
                    @else
                        <li>{{ $chat['message'] }}</li>
                    @endif
                @endforeach
            </ul>
        </div>
    @endif

    @if($report->video_url)
        <div class="col-12">
            <h3>Demo recording</h3>
            <div class="p-4 w-100">
                <div class="embed-responsive embed-responsive-16by9">
                    @if(strncmp($report->video_url, 'youtube:', 8) === 0)
                        <iframe
                            id="ytplayer"
                            type="text/html"
                            width="100%"
                            allowfullscreen
                            src="https://www.youtube.com/embed/{{ str_replace('youtube:', '', $report->video_url) }}?autoplay=0&rel=0&showinfo=0"
                            frameborder="0"
                        >
                            @elseif(strncmp($report->video_url, 'html:', 5) === 0)
                                <video controls>
                                    <source src="{{ str_replace('html:', '', $report->video_url) }}" type="video/mp4">
                                </video>
                            @endif

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
