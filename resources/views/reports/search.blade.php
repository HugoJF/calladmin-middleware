@extends('layouts.app')

@section('content')
    <h2 class="mb-3">Search results for: <small class="text-monospace">{{ request()->input('search') }}</small></h2>
    @forelse($reports as $report)
        @include('reports.report', $report)
    @empty
        <h4>No reports found!</h4>
    @endforelse
@endsection