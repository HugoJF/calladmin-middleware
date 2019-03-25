@extends('layouts.app')

@section('content')
    <h2 class="mb-2">Report {{ $report->id }}</h2>
    
    @include('reports.report', $report)
@endsection