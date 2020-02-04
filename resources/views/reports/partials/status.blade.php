<h3>
    @if($report->correct)
        <span class="badge badge-success">CORRECT REPORT</span>
    @elseif($report->incorrect)
        <span class="badge badge-danger">INCORRECT REPORT</span>
    @elseif($report->ignored)
        <span class="badge badge-warning">REPORT IGNORED</span>
    @else
        <span class="badge badge-dark">PENDING DECISION</span>
    @endif
</h3>
