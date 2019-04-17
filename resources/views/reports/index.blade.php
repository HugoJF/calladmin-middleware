@extends('layouts.app')

@section('content')
    <h2 class="mb-2">Active reports: {{ $reports->count() }}</h2>
    <a class="btn btn-sm btn-secondary mb-4" href="?show-decided=true">Show decided reports</a>
    @forelse($reports as $report)
        @include('reports.report', $report)
    @empty
        <h4>No reports found!</h4>
    @endforelse
    
    <div class="d-flex justify-content-center">
        {!! $reports->appends(request()->query())->links() !!}
    </div>
@endsection