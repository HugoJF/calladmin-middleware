<div class="pr-4 w-1/2">
    <div class="mb-4 flex items-center">
        <h3 class="mr-5 text-2xl font-medium">Players</h3>
        <div class="flex-grow border-b-2 border-gray-800 border-dashed"></div>
    </div>
    <div class="pl-4">
        <table class="w-full">
            <tbody>
            @foreach ($report->player_data ?? [] as $player)
                @php
                    $steamid = $player['steamid'];
                    $name = $player['name'];
                    $target = $steamid === $report->reporter_steam_id;
                    $reporter = $steamid === $report->target_steam_id;
                @endphp

                @if($target)
                    <tr class="text-gray-200 border-b border-gray-800">
                        <td class="flex items-center py-2 tracking-tighter select-all">
                            <div class="w-2 h-2 mr-3 bg-red-700 rounded-full shadow"></div>
                            <code>{{ $steamid }}</code>
                        </td>
                        <td>{{ $name }}</td>
                    </tr>
                @elseif($reporter)
                    <tr class="text-gray-200 border-b border-gray-800">
                        <td class="flex items-center py-2 tracking-tighter select-all">
                            <div class="w-2 h-2 mr-3 bg-blue-700 rounded-full shadow"></div>
                            <code>{{ $steamid }}</code>
                        </td>
                        <td>{{ $name }}</td>
                    </tr>
                @else
                    <tr class="text-gray-400 border-b border-gray-800">
                        <td class="flex items-center py-2 tracking-tighter select-all">
                            <div class="w-2 h-2 mr-3 bg-gray-700 rounded-full shadow"></div>
                            <code>{{ $steamid }}</code>
                        </td>
                        <td>{{ $name }}</td>
                    </tr>
                @endif
            @endforeach
            </tbody>
        </table>
    </div>
</div>
