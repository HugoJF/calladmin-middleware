<h5 class="mb-2">{{ $role }}:</h5>
<p class="mb-0">
    {{ $name }}
    @include('components.user-stats', ['user' => $user])
</p>
<div class="ml-2">
    <pre class="text-muted">{{ $steamid }}</pre>
</div>
