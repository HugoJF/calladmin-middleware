@extends('layouts.app')

@section('content')
    <h2 class="mb-2">Incorrect report acknowledgement</h2>

    @include('reports.report', ['commentsDisabled' => true])
    @include('reports.ack-text')
@endsection
