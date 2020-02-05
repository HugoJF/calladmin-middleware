@extends('layouts.app')

@section('content')
    <div class="pb-2 d-flex justify-content-between align-items-center">
        <h2>Notifications</h2>
        <a href="{{ route('notifications.clear') }}" class="btn btn-outline-primary">Mark all as read</a>
    </div>

    <table class="table">
        @forelse ($notifications ?? [] as $notification)
            <tr>
                <!-- Unread dot -->
                <td class="align-middle text-primary">
                    @if(!$notification->read_at)
                        <i class="fas fa-circle"></i>
                    @endif
                </td>

                <!-- Date -->
                <td class="align-middle">
                    <div class="d-flex flex-column align-items-center justify-content-center" title="{{ $notification->created_at }}">
                        <h4 class="m-0">{{ $notification->created_at->day }}</h4>
                        <p class="m-0">{{ $notification->created_at->format('M') }}</p>
                    </div>
                </td>

                <!-- Icon -->
                <td class="align-middle">
                    <h4>
                        <i class="{{ $notification->data['icon'] ?? 'fas fa-question' }}"></i>
                    </h4>
                </td>

                <!-- Text -->
                <td class="align-middle">
                    <h4 class="m-0{{ $notification->read_at ? ' font-weight-light' : '' }}">
                        {{ $notification->data['title'] ?? 'Notification without title' }}
                    </h4>
                    <p class="m-0">
                        {!! $notification->data['body'] ?? 'Notification without body' !!}
                    </p>
                </td>

                <!-- Actions -->
                <td class="align-middle">
                    <div class="btn-group">
                        @if(array_key_exists('view_url', $notification->data))
                            <a href="{{ $notification->data['view_url'] ?? '#' }}" class="btn btn-primary btn-sm">View</a>
                        @endif
                        @if(!$notification->read_at)
                            <a href="{{ route('notifications.read', $notification->id) }}" class="btn btn-outline-success btn-sm">Mark as viewed</a>
                        @endif
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td class="text-center" colspan="5">
                    <h3 class="py-4">There are no notifications for you!</h3>
                </td>
            </tr>
        @endforelse
    </table>

@endsection
