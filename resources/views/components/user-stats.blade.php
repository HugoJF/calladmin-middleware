<span class="badge badge-{{ $user->report_state }}">
    R:
    {{ $user->correct_report_count }}
    /
    {{ $user->decided_report_count }}
</span>
<span class="badge badge-{{ $user->vote_state }}">
    V:
    {{ $user->correct_vote_count }}
    /
    {{ $user->vote_count }}
</span>
<span class="badge badge-{{ $user->target_state }}">
    T:
    {{ $user->correct_target_count }}
    /
    {{ $user->decided_target_count }}
</span>
