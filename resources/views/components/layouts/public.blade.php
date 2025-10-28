<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <div class="min-h-screen bg-zinc-50 dark:bg-zinc-900">
            {{ $slot }}
        </div>

        @stack('scripts')
        @fluxScripts
    </body>
</html>
