@extends('layouts.app')

@section('content')
    <h2>Statistics</h2>
    <ul>
        <li><h4 class="mb-2">Active reports: {{ $reports->toArray()['total'] }} <small>({{ \App\Report::whereNotNull('video_url')->whereNull('decision')->count() }} with video)</small></h4></li>
        <li><h4>Correct reports: {{ round(\App\Report::correctness() * 100) }}%</h4></li>
    </ul>
    <a class="btn btn-sm btn-secondary mb-4" href="?show-decided=true">Show decided reports</a>
    
    @include('reports.reports', ['reports' => $reports])
    
    <div class="d-flex justify-content-center">
        {!! $reports->appends(request()->query())->links() !!}
    </div>
@endsection