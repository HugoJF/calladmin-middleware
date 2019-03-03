@extends('layouts.app')

@section('content')
    <h2 class="mb-4">Users list</h2>
    <table class="table table-sm">
        <thead>
        <tr>
            <th>Username</th>
            <th>Steam2ID</th>
            <th>Score</th>
            <th>Votes</th>
            <th>Reports</th>
            <th>Targets</th>
            <th title="Ignore reports from user">IR</th>
            <th title="Ignore reports of user">IT</th>
            <th title="Is admin">ADM</th>
            <th title="User banned">BAN</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($users as $user)
            @include('users.user-row', $user)
        @endforeach
        </tbody>
    </table>
@endsection