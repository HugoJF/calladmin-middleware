@if($report->decision !== null)
    <h3>
        @if($report->acked_at)
            <span class="badge badge-success">ACKED</span>
        @else
            <span class="badge badge-danger">ACK PENDING</span>
        @endif
    </h3>
@endif
