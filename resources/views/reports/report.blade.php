@php
    $score = $report->score;
    $vote = $report->votes->first();

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

<div class="row border rounded bg-light p-3 mb-3">
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
                    <a class="btn btn-primary"
                       title="{{ $report->demoFilename }}"
                       href="{{ $report->demoUrl }}"
                    >Download demo</a>
                    <a class="btn btn-outline-success{{ $report->decided ? ' disabled' : '' }}"
                       href="#"
                       data-toggle="modal"
                       data-target="#report-decision-{{ $report->id }}"
                    >Final decision</a>
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
                @endauth
            </div>
        </div>
    </div>
</div>

@push('modals')
    <div class="modal fade" id="report-decision-{{ $report->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                    {!! Form::hidden('decision', 'correct') !!}
                    <button type="submit" class="btn btn-primary">Correct</button>
                    {!! Form::close() !!}
                    
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
