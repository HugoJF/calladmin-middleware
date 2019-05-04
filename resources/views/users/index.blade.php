@extends('layouts.app')

@section('content')
    <h2 class="mb-4">Users list</h2>
    <table class="table table-sm">
        <thead>
        <tr>
            <th>Username</th>
            <th>Steam2ID</th>
            <th>Score</th>
            <th>Karma</th>
            <th title="Votes">V</th>
            <th title="Vote precision">VP</th>
            <th title="Reports">R</th>
            <th title="Report precision">RP</th>
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