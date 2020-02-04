<div class="col d-flex flex-grow-1">
    <div class="row flex-grow-1">
        <!-- Reporter information card -->
        <div class="col">
            <!-- Reporter information -->
            @include('reports.partials.user-info', [
                'role' => 'Reporter',
                'user' => $report->reporter,
                'name' => $report->reporter_name,
                'steamid' => $report->reporter_steam_id
            ])

            <!-- Reason -->
            <p>Reason: <code>{{ $report->reason }}</code></p>

            <!-- Decider -->
            @if($report->decision !== null && $report->decider_id)
                <p>Decider: <code>{{ $report->decider->username ?? $report->decider->name }}</code></p>
            @endif

            <!-- Status -->
            @include('reports.partials.status')

            <!-- Ack -->
            @include('reports.partials.ack')
        </div>

        <!-- Target information card -->
        <div class="col">
            <!-- Target information -->
            @include('reports.partials.user-info', [
                'role' => 'Target',
                'user' => $report->target,
                'name' => $report->target_name,
                'steamid' => $report->target_steam_id
            ])

            <!-- Server information -->
            <p>
                Server: <code>{{ $report->server_ip }}:{{ $report->server_port }}</code>
            </p>

            <!-- Report date -->
            <p class="pb-0 mb-1">
                <a href="{{ route('reports.show', $report) }}">
                    <small title="{{ $report->created_at->toRfc7231String() }}" class="text-muted">
                        Created at: {{ $report->created_at->diffForHumans() }}
                    </small>
                </a>
            </p>

            <!-- Actions -->
            @include('reports.partials.actions')
        </div>
    </div>
</div>
