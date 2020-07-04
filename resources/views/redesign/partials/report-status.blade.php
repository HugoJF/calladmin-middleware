<h3>
    @if($report->correct)
    <div class="inline-block px-3 py-1 text-gray-200 bg-green-800 text-2xl font-medium shadow-lg rounded">CORRECT</div>
    @elseif($report->incorrect)
    <div class="inline-block px-3 py-1 text-gray-200 bg-red-800 text-2xl font-medium shadow-lg rounded">INCORRECT</div>
    @elseif($report->ignored)
    <div class="inline-block px-3 py-1 text-gray-200 bg-yellow-800 text-2xl font-medium shadow-lg rounded">IGNORED</div>
    @else
    <div class="inline-block px-3 py-1 text-gray-200 bg-gray-800 text-2xl font-medium shadow-lg rounded">PENDING DECISION</div>
    @endif
</h3>
