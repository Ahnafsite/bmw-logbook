<div>
    <!-- Title and Breadcrumb -->
    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4 mb-8">
        <!-- Title and Description (Left) -->
        <div class="flex-1">
            <flux:heading size="xl" class="text-zinc-900 dark:text-zinc-100">
                Assalamu'alaikum {{ Auth::user()->name }}
            </flux:heading>
            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">
                Mari mulai hari dengan produktif! Catat setiap aktivitas dan raih pencapaian terbaikmu.
            </p>
        </div>

        <!-- Breadcrumb (Right) -->
        <nav class="flex-shrink-0" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 text-sm text-zinc-500 dark:text-zinc-400">
                <li>
                    <span class="font-medium text-zinc-900 dark:text-zinc-100">Dashboard</span>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Today's Logbooks -->
        <div class="bg-white dark:bg-zinc-900 shadow-sm rounded-lg border border-zinc-200 dark:border-zinc-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Semua Logbook Hari Ini</p>
                    <p class="text-3xl font-bold text-zinc-900 dark:text-zinc-100">{{ $totalLogbooksToday }}</p>
                </div>
                <div class="bg-blue-100 dark:bg-blue-900/20 p-3 rounded-full">
                    <flux:icon.calendar-days class="h-6 w-6 text-blue-600 dark:text-blue-400" />
                </div>
            </div>
        </div>

        <!-- This Week's Logbooks -->
        <div class="bg-white dark:bg-zinc-900 shadow-sm rounded-lg border border-zinc-200 dark:border-zinc-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Logbook Minggu Ini</p>
                    <p class="text-3xl font-bold text-zinc-900 dark:text-zinc-100">{{ $totalLogbooksThisWeek }}</p>
                </div>
                <div class="bg-green-100 dark:bg-green-900/20 p-3 rounded-full">
                    <flux:icon.chart-bar class="h-6 w-6 text-green-600 dark:text-green-400" />
                </div>
            </div>
        </div>

        <!-- This Month's Logbooks -->
        <div class="bg-white dark:bg-zinc-900 shadow-sm rounded-lg border border-zinc-200 dark:border-zinc-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Logbook Bulan Ini</p>
                    <p class="text-3xl font-bold text-zinc-900 dark:text-zinc-100">{{ $totalLogbooksThisMonth }}</p>
                </div>
                <div class="bg-purple-100 dark:bg-purple-900/20 p-3 rounded-full">
                    <flux:icon.clipboard-document-list class="h-6 w-6 text-purple-600 dark:text-purple-400" />
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Logbooks Summary Card -->
    <div class="mb-8">
        <div class="bg-white dark:bg-zinc-900 shadow-sm rounded-lg border border-zinc-200 dark:border-zinc-700">
            <div class="px-6 py-4 border-b border-zinc-200 dark:border-zinc-700">
                <h3 class="text-lg font-medium text-zinc-900 dark:text-zinc-100">Semua Logbook Staff Hari Ini</h3>
                <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">
                    Catatan aktivitas terbaru dari semua staff hari ini
                </p>
                <div class="mt-4">
                    <flux:button href="{{ route('logbook') }}" icon="plus" variant="primary" size="sm">
                        Buat Logbook
                    </flux:button>
                </div>
            </div>

            @if($todaysLogbooks->count() > 0)
                <div class="max-h-80 overflow-y-auto">
                    <div class="divide-y divide-zinc-200 dark:divide-zinc-700">
                        @foreach($todaysLogbooks->take(5) as $logbook)
                            <div class="p-4 hover:bg-zinc-50 dark:hover:bg-zinc-800">
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0 h-8 w-8">
                                        <div class="h-8 w-8 rounded-full bg-zinc-200 dark:bg-zinc-700 flex items-center justify-center">
                                            <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                                                {{ substr($logbook->user->name, 0, 1) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-medium text-zinc-900 dark:text-zinc-100 truncate">
                                                {{ $logbook->user->name }}
                                            </p>
                                            <p class="text-xs text-zinc-500 dark:text-zinc-400">
                                                {{ $logbook->start_time->format('H:i') }} - {{ $logbook->end_time->format('H:i') }}
                                            </p>
                                        </div>
                                        <p class="text-sm text-zinc-900 dark:text-zinc-100 mt-1 truncate" title="{{ $logbook->job }}">
                                            {{ $logbook->job }}
                                        </p>
                                        @if($logbook->desc)
                                            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1 truncate" title="{{ $logbook->desc }}">
                                                {{ $logbook->desc }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @if($todaysLogbooks->count() > 5)
                        <div class="p-4 text-center border-t border-zinc-200 dark:border-zinc-700">
                            <flux:button href="{{ route('logbook') }}" variant="ghost" size="sm">
                                Lihat Semua ({{ $todaysLogbooks->count() }})
                            </flux:button>
                        </div>
                    @endif
                </div>
            @else
                <div class="px-6 py-12 text-center">
                    <flux:icon.clipboard-document-list class="mx-auto h-8 w-8 text-zinc-400" />
                    <h3 class="mt-2 text-sm font-medium text-zinc-900 dark:text-zinc-100">Belum ada logbook hari ini</h3>
                    <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">
                        Belum ada catatan aktivitas yang dibuat hari ini.
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>
