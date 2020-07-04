<div class="pl-4 w-1/2">
    <div class="mb-4 flex items-center">
        <h3 class="mr-5 text-2xl font-medium">Chat</h3>
        <div class="flex-grow border-b-2 border-gray-800 border-dashed"></div>
    </div>
    <ul class="pl-4 list-unstyled">
        @foreach ($report->chat as $chat)
            @php
                $steamid = $chat['steamid'];
                $message = preg_split('/:/', $chat['message']);
                $name = preg_replace('/\*DEAD\*|\(Counter-Terrorist\)\s|\(Terrorist\)\s/', '', $message[0]);
                $text = $message[1];
                $reporter = $steamid === $report->reporter_steam_id;
                $target = $steamid === $report->target_steam_id;
                $color = null;
                $color = $reporter ? 'blue' : $color;
                $color = $target ? 'red' : $color;
                $related = $reporter || $target;
            @endphp

            @if(count($message) === 2)
                <li class="flex items-center my-2">
                    @if($color)
                        <div class="w-2 h-2 mr-3 bg-{{ $color }}-700 rounded-full shadow"></div>
                    @else
                        <div class="w-2 h-2 mr-3 bg-transparent"></div>
                    @endif

                    <span class="mr-1 text-gray-{{ $related ? 300 : 400 }} font-medium">{{ $name }}:</span>
                    <span class="text-gray-400">{{ $text }}</span>
                </li>
            @else
                <li>{{ $chat['message'] }}</li>
            @endif
        @endforeach
    </ul>
</div>
