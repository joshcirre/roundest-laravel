<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ $title ?? 'Roundest Pokemon' }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="flex flex-col justify-between min-h-screen antialiased text-white border-t-2 border-purple-600 min-w-screen bg-gray-950">
        <header class="px-8 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-baseline">
                    <a href="/" class="text-3xl font-bold">
                        round<span class="text-red-500">est</span>
                        <span class="pl-2 text-2xl font-extralight text-gray-400">
                            (<a href="https://tallstack.dev" target="_blank" rel="noopener noreferrer" class="hover:underline">TALL Stack</a> &nbsp;Version)
                        </span>
                    </a>
                </div>
                <nav class="flex flex-row gap-8 items-center">
                    <a href="/results" class="text-lg hover:underline">
                        Results
                    </a>
                </nav>
            </div>
        </header>

        <main class="flex-1">
            {{ $slot }}
        </main>

        <footer class="py-3 font-light text-center text-gray-500">
            <a href="https://github.com/t3dotgg/1app5stacks" target="_blank" rel="noopener noreferrer">
                GitHub
            </a>
        </footer>
    </body>
</html>