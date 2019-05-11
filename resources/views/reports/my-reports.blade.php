@extends('layouts.app')

@section('content')
    <h2 class="mb-2">My reports</h2>
    @forelse($reports as $report)
        @include('reports.report', $report)
    @empty
        <h4>No reports found!</h4>
    @endforelse
    
    <div class="d-flex justify-content-center">
        {!! $reports->appends(request()->query())->links() !!}
    </div>
@endsection