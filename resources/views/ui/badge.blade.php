@php
    $badges = [
        -1 => 'danger',
        0 => 'dark',
        1 => 'success',
    ];
@endphp

<span class="badge badge-{{ $badges[$number <=> 0] }}">{{ $number }}</span>