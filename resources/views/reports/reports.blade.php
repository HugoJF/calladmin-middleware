@forelse($reports as $report)
    @include('reports.report', $report)
@empty
    <h4>No reports found!</h4>
@endforelse