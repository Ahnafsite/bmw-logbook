<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen antialiased relative overflow-hidden">
        <!-- Background Image -->
        <div class="absolute inset-0 bg-cover bg-center bg-no-repeat" style="background-image: url('{{ asset('assets/images/background.webp') }}');"></div>

        <!-- Background Overlay for better contrast -->
        <div class="absolute inset-0 bg-white/30 dark:bg-black/50 backdrop-blur-sm"></div>

        <!-- Decorative Elements -->
        <div class="absolute top-0 left-0 w-72 h-72 bg-blue-200/10 dark:bg-blue-500/5 rounded-full -translate-x-32 -translate-y-32 blur-3xl"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-indigo-200/10 dark:bg-indigo-500/5 rounded-full translate-x-32 translate-y-32 blur-3xl"></div>
        <div class="absolute top-1/2 left-1/2 w-64 h-64 bg-purple-200/5 dark:bg-purple-500/3 rounded-full -translate-x-1/2 -translate-y-1/2 blur-2xl"></div>

        <!-- Main Content -->
        <div class="relative z-10 flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10">
            <!-- Glass Card -->
            <div class="w-full max-w-sm backdrop-blur-2xl bg-white/90 dark:bg-neutral-900/90 border border-white/30 dark:border-neutral-700/40 rounded-2xl shadow-2xl shadow-black/10 dark:shadow-black/30 p-8">
                <div class="flex w-full flex-col gap-2">
                <a href="{{ route('home') }}" class="flex flex-col items-center gap-2 font-medium group transition-all duration-300" wire:navigate>
                    <span class="flex h-20 w-60 mb-1 items-center justify-center">
                        <x-app-logo-icon class="fill-current text-gray-800 dark:text-white transition-colors duration-300" />
                    </span>
                    <span class="sr-only">{{ config('app.name', 'Laravel') }}</span>
                </a>
                <div class="flex flex-col gap-6 mt-4">
                    {{ $slot }}
                </div>
                </div>
            </div>
        </div>
        @fluxScripts
    </body>
</html>
