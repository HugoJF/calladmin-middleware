@extends('layouts.app')

@section('content')
    <h2 class="mb-2">Active reports: {{ $reports->toArray()['total'] }}</h2>
    <a class="btn btn-sm btn-secondary mb-4" href="?show-decided=true">Show decided reports</a>
    
    @include('reports.reports', ['reports' => $reports])
    
    <div class="d-flex justify-content-center">
        {!! $reports->appends(request()->query())->links() !!}
    </div>
@endsection