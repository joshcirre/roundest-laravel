<?php

use function Livewire\Volt\{with, usesPagination, state, placeholder};
use App\Models\Pokemon;

// Add pagination support
usesPagination(theme: 'tailwind');

// Add search state
state(['search' => '']);

placeholder(
    <<<'HTML'
                <div class="container px-4 py-8 mx-auto text-white">
                    <div class="mb-6">
                        <input type="text" class="px-4 py-2 w-full placeholder-gray-400 text-white rounded-lg bg-gray-800/40" placeholder="Search Pokémon...">
                    </div>
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

with(function () {
    return [
        'results' => Pokemon::query()
            ->when($this->search, function ($query, $search) {
                $query
                    ->where('name', 'like', '%' . $search . '%')
                    ->orWhere('dex_id', '=', (int) $search)
                    ->orWhere('dex_id', 'like', $search . '%');
            })
            ->selectRaw('*, up_votes, down_votes')
            ->orderByRaw('CASE WHEN up_votes + down_votes > 0 THEN up_votes / NULLIF(up_votes + down_votes, 0) * 100 ELSE 0 END DESC')
            ->orderByRaw('up_votes DESC')
            ->paginate(10)
            ->through(function ($pokemon) {
                return [
                    'dexId' => $pokemon->dex_id,
                    'name' => $pokemon->name,
                    'upVotes' => $pokemon->up_votes,
                    'downVotes' => $pokemon->down_votes,
                    'winPercentage' => round(($pokemon->up_votes / max(1, $pokemon->up_votes + $pokemon->down_votes)) * 100, 1),
                    'sprite' => $pokemon->sprite,
                ];
            }),
    ];
});

?>

<div class="container px-4 py-8 mx-auto text-white">
    <!-- Add search input -->
    <div class="mb-6">
        <input wire:model.live="search" type="search"
            class="px-4 py-2 w-full placeholder-gray-400 text-white rounded-lg border border-gray-700 bg-gray-800/40 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none"
            placeholder="Search by name or Pokédex number...">
    </div>

    <div class="grid gap-4">
        @if ($results->isEmpty())
            <div class="py-8 text-center text-gray-400">
                No Pokémon found matching your search.
            </div>
        @else
            @foreach ($results as $index => $pokemon)
                <div
                    class="flex gap-6 items-center p-6 rounded-lg shadow transition-shadow bg-gray-800/40 hover:shadow-md">
                    <div class="w-8 text-2xl font-bold text-gray-400">
                        #{{ ($results->currentPage() - 1) * $results->perPage() + $index + 1 }}
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
        @endif
    </div>

    <!-- Add pagination links -->
    <div class="mt-6">
        {{ $results->links() }}
    </div>
</div>
