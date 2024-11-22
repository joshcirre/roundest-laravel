<?php

use function Livewire\Volt\{computed, placeholder};
use App\Models\Pokemon;

placeholder(<<<'HTML'
    <div class="container px-4 py-8 mx-auto text-white">
        <div class="grid gap-4">
            @foreach(range(1, 10) as $i)
                <div class="flex gap-6 items-center p-6 rounded-lg shadow animate-pulse bg-gray-800/40">
                    <div class="w-8 h-8 rounded bg-gray-700/40"></div>
                    <div class="w-20 h-20 rounded bg-gray-700/40"></div>
                    <div class="flex-grow">
                        <div class="mb-2 w-16 h-4 rounded bg-gray-700/40"></div>
                        <div class="w-32 h-6 rounded bg-gray-700/40"></div>
                    </div>
                    <div class="text-right">
                        <div class="mb-2 w-16 h-8 rounded bg-gray-700/40"></div>
                        <div class="w-24 h-4 rounded bg-gray-700/40"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
HTML);

$results = computed(function() {
    return Pokemon::query()
        ->selectRaw("*, up_votes, down_votes")
        ->orderByRaw("CASE WHEN up_votes + down_votes > 0 THEN up_votes / NULLIF(up_votes + down_votes, 0) * 100 ELSE 0 END DESC")
        ->orderByRaw("up_votes DESC")
        ->get()
        ->map(function($pokemon) {
            return [
                'dexId' => $pokemon->dex_id,
                'name' => $pokemon->name,
                'upVotes' => $pokemon->up_votes,
                'downVotes' => $pokemon->down_votes,
                'winPercentage' => round(($pokemon->up_votes / max(1, ($pokemon->up_votes + $pokemon->down_votes))) * 100, 1),
                'sprite' => $pokemon->sprite
            ];
        });});

?>

<div class="container px-4 py-8 mx-auto text-white">
    <div class="grid gap-4">
        @foreach($this->results as $index => $pokemon)
            <div class="flex gap-6 items-center p-6 rounded-lg shadow transition-shadow bg-gray-800/40 hover:shadow-md">
                <div class="w-8 text-2xl font-bold text-gray-400">
                    #{{ $index + 1 }}
                </div>

                <img src="{{ $pokemon['sprite'] }}" class="w-20 h-20" alt="{{ $pokemon['name'] }}">

                <div class="flex-grow">
                    <div class="text-sm text-gray-400">#{{ $pokemon['dexId'] }}</div>
                    <h2 class="text-xl font-semibold capitalize">{{ $pokemon['name'] }}</h2>
                </div>

                <div class="text-right">
                    <div class="text-2xl font-bold text-blue-400">
                        {{ $pokemon['winPercentage'] }}%
                    </div>
                    <div class="text-sm text-gray-400">
                        {{ $pokemon['upVotes'] }}W - {{ $pokemon['downVotes'] }}L
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
