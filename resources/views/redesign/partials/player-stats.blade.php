<span class="py-1 px-2 mx-1 text-gray-100 bg-green-700 text-xs font-medium tracking-wide rounded">
    R: {{ $user->correct_report_count }} / {{ $user->decided_report_count }}
</span>
<span class="py-1 px-2 mx-1 text-gray-100 bg-green-700 text-xs font-medium tracking-wide rounded">
    V: {{ $user->correct_vote_count }} / {{ $user->vote_count }}
</span>
<span class="py-1 px-2 mx-1 text-gray-100 bg-green-700 text-xs font-medium tracking-wide rounded">
    T: {{ $user->correct_target_count }} / {{ $user->decided_target_count }}
</span>
