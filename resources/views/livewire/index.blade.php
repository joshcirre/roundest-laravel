<?php

use function Livewire\Volt\{with, placeholder, layout};
use App\Models\Pokemon;

placeholder(<<<'HTML'
    <div class="flex min-h-[80vh] items-center justify-center gap-16">
        @foreach(range(1, 2) as $i)
            <div class="flex flex-col gap-4 items-center">
                <div class="w-64 h-64 rounded-lg animate-pulse bg-gray-800/10"></div>
                <div class="flex flex-col justify-center items-center space-y-2 text-center">
                    <div class="w-16 h-6 rounded animate-pulse bg-gray-800/10"></div>
                    <div class="w-32 h-8 rounded animate-pulse bg-gray-800/10"></div>
                    <div class="w-24 h-12 rounded animate-pulse bg-gray-800/10"></div>
                </div>
            </div>
        @endforeach
    </div>
HTML);

with(fn () => ['pokemon' => Pokemon::getRandomPair()]);

$handleVote = function(Pokemon $winner, Pokemon $loser) {
    $winner->increment('up_votes');
    $loser->increment('down_votes');
};

?>

<div class="flex min-h-[80vh] items-center justify-center gap-16">
    @foreach($pokemon as $mon)
        <div class="flex flex-col gap-4 items-center">
            <img 
                src="{{ $mon->sprite }}" 
                class="w-64 h-64" 
                alt="{{ $mon->name }}"
                loading="eager"
                fetchpriority="high"
            >
            <div class="text-center">
                <span class="text-lg text-gray-500">#{{ $mon->dex_id }}</span>
                <h2 class="text-2xl font-bold capitalize">{{ $mon->name }}</h2>
                <button
                    wire:click="handleVote({{ $mon->id }}, {{ $pokemon->where('id', '!=', $mon->id)->first()->id }})"
                    class="px-8 py-3 text-lg font-semibold text-white bg-blue-500 rounded-lg transition-colors hover:bg-blue-600"
                >
                    Vote
                </button>
            </div>
        </div>
    @endforeach
</div>