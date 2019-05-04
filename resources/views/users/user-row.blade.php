<tr>
    <!-- Username -->
    <td>
        {{ $user->username ?? $user->name }}
    </td>
    
    <!-- SteamID -->
    <td>
        <pre>{{ $user->steamid }}</pre>
    </td>
    
    <!-- Score -->
    <td title="Score">@include('ui.badge', ['number' => $user->score])</td>
    
    <!-- Karma -->
    <td title="Karma">@include('ui.badge', ['number' => $user->karma])</td>
    
    <!-- Votes -->
    <td title="Votes">{{ $user->votes()->count() }}</td>
    
    <!-- Vote precision -->
    <td title="Vote precision">{{ round($user->vote_precision * 100) }}%</td>
    
    <!-- Reports -->
    <td title="Reports">{{ $user->reports()->count() }}</td>
    
    <!-- Report precision -->
    <td title="Report precision">{{ round($user->report_precision * 100) }}%</td>
    
    <!-- Targets -->
    <td title="Targets">{{ $user->targets()->count() }}</td>
    
    <!-- Ignore reports -->
    <td>
        @if($user->ignore_reports)
            <i class="text-danger far fa-check-circle"></i>
        @else
            <i class="far fa-times-circle"></i>
        @endif
    </td>
    
    <!-- Ignore targets -->
    <td>
        @if($user->ignore_targets)
            <i class="text-danger far fa-check-circle"></i>
        @else
            <i class="far fa-times-circle"></i>
        @endif
    </td>
    
    <!-- Admin -->
    <td>
        @if($user->admin)
            <i class="text-danger far fa-check-circle"></i>
        @else
            <i class="far fa-times-circle"></i>
        @endif
    </td>
    
    <!-- Banned -->
    <td>
        @if($user->banned)
            <i class="text-danger far fa-check-circle"></i>
        @else
            <i class="far fa-times-circle"></i>
        @endif
    </td>
    
    <!-- Actions -->
    <td>
        {!! Form::open(['url' => route('users.ignore-reports', $user), 'method' => 'PATCH', 'style' => 'display: inline']) !!}
        <button type="submit" title="Ignore reports" class="btn btn-secondary btn-sm">IR</button>
        {!! Form::close() !!}
        
        {!! Form::open(['url' => route('users.ignore-targets', $user), 'method' => 'PATCH', 'style' => 'display: inline']) !!}
        <button type="submit" title="Ignore targets" class="btn btn-secondary btn-sm">IT</button>
        {!! Form::close() !!}
        
        {!! Form::open(['url' => route('users.admin', $user), 'method' => 'PATCH', 'style' => 'display: inline']) !!}
        <button type="submit" title="Admin user" class="btn btn-secondary btn-sm">ADM</button>
        {!! Form::close() !!}
        
        {!! Form::open(['url' => route('users.ban', $user), 'method' => 'PATCH', 'style' => 'display: inline']) !!}
        <button type="submit" title="Ban user" class="btn btn-secondary btn-sm">BAN</button>
        {!! Form::close() !!}
    </td>
</tr>