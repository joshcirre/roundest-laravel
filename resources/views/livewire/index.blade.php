<?php

use function Livewire\Volt\{state, with, placeholder, layout, mount};
use App\Models\Pokemon;
use Illuminate\Support\Collection;

layout('components.layouts.app');

state(['pairs' => null, 'currentIndex' => 0, 'voteCount' => 0]);

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

$getPairs = function($count = 10) {
    $pairs = new Collection();
    for ($i = 0; $i < $count; $i++) {
        $pairs->push(Pokemon::getRandomPair());
    }
    return $pairs;
};

mount(function() {
    $this->pairs = $this->getPairs();
});

$getCurrentPair = function() {
    if ($this->voteCount % 5 === 0 && $this->voteCount > 0) {
        $this->pairs = $this->getPairs();
        $this->currentIndex = 0;
    }
    return $this->pairs[$this->currentIndex];
};

$handleVote = function(Pokemon $winner, Pokemon $loser) {
    $winner->increment('up_votes');
    $loser->increment('down_votes');
    
    $this->voteCount++;
    $this->currentIndex = ($this->currentIndex + 1) % count($this->pairs);
};

?>

<div class="flex min-h-[80vh] items-center justify-center gap-16">
    @foreach($this->getCurrentPair() as $pokemon)
        <div class="flex flex-col gap-4 items-center">
            <img 
                src="{{ $pokemon->sprite }}" 
                class="w-64 h-64" 
                alt="{{ $pokemon->name }}"
                loading="eager"
                fetchpriority="high"
            >
            <div class="text-center">
                <span class="text-lg text-gray-500">#{{ $pokemon->dex_id }}</span>
                <h2 class="text-2xl font-bold capitalize">{{ $pokemon->name }}</h2>
                <button
                    wire:click="handleVote({{ $pokemon->id }}, {{ $this->getCurrentPair()->where('id', '!=', $pokemon->id)->first()->id }})"
                    class="px-8 py-3 text-lg font-semibold text-white bg-blue-500 rounded-lg transition-colors hover:bg-blue-600"
                >
                    Vote
                </button>
            </div>
        </div>
    @endforeach

    {{-- Preload next pair images --}}
    @if(isset($this->pairs[$this->currentIndex + 1]))
        @foreach($this->pairs[$this->currentIndex + 1] as $pokemon)
            <link rel="preload" as="image" href="{{ $pokemon->sprite }}">
        @endforeach
    @endif
</div>