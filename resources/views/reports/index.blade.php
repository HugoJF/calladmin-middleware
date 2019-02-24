@extends('layouts.app')

@section('content')
    <h2>Active reports</h2>
    @forelse($reports as $report)
        <div class="row border rounded bg-light p-3 mb-5">
            <div class="col d-flex align-items-center flex-column">
                <h2><a class="text-dark text-decoration-none" href="#">+</a></h2>
                <h2 class="text-success">+9</h2>
                <h2><a class="text-dark text-decoration-none" href="#">-</a></h2>
            </div>
            <div class="col d-flex" style="flex-grow: 50; flex-flow: column;">
                <div class="row flex-grow-1">
                    <div class="col">
                        <p class="mb-0">Teaguenho</p>
                        <pre class="text-muted ml-2">STEAM_1:1:479857020</pre>
                    </div>
                    <div class="col">
                        <p class="mb-0">zinAIMWARE</p>
                        <pre class="text-muted ml-2">STEAM_1:1:479857020</pre>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                    </div>
                    <div class="col">
                        <a class="btn btn-primary" href="#">Download demo</a>
                        <a class="btn btn-outline-success" href="#">Final decision</a>
                        <a class="btn btn-outline-danger" href="#">Delete</a>
                    </div>
                </div>
            </div>
        </div>
    @empty
    @endforelse
    
    <div class="text-center">
        {!! $reports->links() !!}
    </div>
@endsection