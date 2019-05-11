@extends('layouts.app')

@section('content')
    <h2 class="mb-2">Incorrect report acknowledgement</h2>
    
    @include('reports.report', $report)
    @include('reports.ack-text', $report)
@endsection