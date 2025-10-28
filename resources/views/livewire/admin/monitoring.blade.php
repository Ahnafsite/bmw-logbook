<div>
    <!-- Title and Breadcrumb -->
    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4 mb-8">
        <!-- Title and Description (Left) -->
        <div class="flex-1">
            <flux:heading size="xl" class="text-zinc-900 dark:text-zinc-100">
                Monitoring Logbook
            </flux:heading>
            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">
                Monitor aktivitas logbook dari semua pengguna.
            </p>
        </div>

        <!-- Breadcrumb (Right) -->
        <nav class="flex-shrink-0" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 text-sm text-zinc-500 dark:text-zinc-400">
                <li>
                    <span class="font-medium text-zinc-900 dark:text-zinc-100">Monitoring Logbook</span>
                </li>
            </ol>
        </nav>
    </div>

    @if (session()->has('message'))
        <div class="mb-6 rounded-md bg-green-50 dark:bg-green-900/20 p-4 border border-green-200 dark:border-green-800">
            <div class="flex">
                <div class="flex-shrink-0">
                    <flux:icon.check-circle class="h-5 w-5 text-green-400" />
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800 dark:text-green-200">
                        {{ session('message') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-6 rounded-md bg-red-50 dark:bg-red-900/20 p-4 border border-red-200 dark:border-red-800">
            <div class="flex">
                <div class="flex-shrink-0">
                    <flux:icon.x-circle class="h-5 w-5 text-red-400" />
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800 dark:text-red-200">
                        {{ session('error') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Search and Filters -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <!-- Left side: Search and Filters -->
        <div class="flex flex-col lg:flex-row lg:items-center gap-4 flex-1">
            <div class="flex-1 max-w-md">
                <flux:input
                    wire:model.live.debounce.300ms="search"
                    placeholder="Cari berdasarkan nama, pekerjaan atau deskripsi..."
                    icon="magnifying-glass"
                />
            </div>
            <div class="flex gap-3 items-center">
                <div class="w-48">
                    <flux:select wire:model.live="selectedUserId" placeholder="Pilih Pengguna" class="text-sm">
                        <flux:select.option value="">Semua</flux:select.option>
                        @foreach($users as $user)
                            <flux:select.option value="{{ $user->id }}">{{ $user->name }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </div>
                <flux:input
                    wire:model.live="dateFrom"
                    type="date"
                    class="w-32"
                />
                <flux:input
                    wire:model.live="dateTo"
                    type="date"
                    class="w-32"
                />
                <flux:button wire:click="resetAllFilters" variant="ghost" size="sm">
                    Reset
                </flux:button>
            </div>
        </div>

        <!-- Right side: Export Button -->
        <div class="flex-shrink-0">
            <flux:button wire:click="openExportModal" icon="arrow-down-tray" variant="primary">
                Export
            </flux:button>
        </div>
    </div>    <!-- Logbooks Table -->
    <div class="bg-white dark:bg-zinc-900 shadow-sm rounded-lg border border-zinc-200 dark:border-zinc-700">
        @if($logbooks->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                    <thead class="bg-zinc-50 dark:bg-zinc-800">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">No</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Pengguna</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Tanggal</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Waktu</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Pekerjaan</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Deskripsi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-zinc-900 divide-y divide-zinc-200 dark:divide-zinc-700">
                        @foreach($logbooks as $index => $logbook)
                            <tr wire:key="{{ $logbook->id }}" class="hover:bg-zinc-50 dark:hover:bg-zinc-800">
                                <td class="px-4 py-2 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">
                                    {{ $logbooks->firstItem() + $index }}
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap text-sm text-zinc-900 dark:text-zinc-100">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8">
                                            <div class="h-8 w-8 rounded-full bg-zinc-200 dark:bg-zinc-700 flex items-center justify-center">
                                                <span class="text-xs font-medium text-zinc-700 dark:text-zinc-300">
                                                    {{ $logbook->user->initials() }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                                {{ $logbook->user->name }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap text-sm text-zinc-900 dark:text-zinc-100">
                                    {{ $logbook->date->format('d/m/Y') }}
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap text-sm text-zinc-900 dark:text-zinc-100">
                                    {{ $logbook->start_time->format('H:i') }} - {{ $logbook->end_time->format('H:i') }}
                                </td>
                                <td class="px-4 py-2 text-sm text-zinc-900 dark:text-zinc-100">
                                    <div class="max-w-xs truncate" title="{{ $logbook->job }}">
                                        {{ $logbook->job }}
                                    </div>
                                </td>
                                <td class="px-4 py-2 text-sm text-zinc-500 dark:text-zinc-400">
                                    <div class="max-w-xs truncate" title="{{ $logbook->desc }}">
                                        {{ $logbook->desc ?: '-' }}
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-4 py-3 border-t border-zinc-200 dark:border-zinc-700">
                <x-custom-pagination :paginator="$logbooks" perPageModel="perPage" />
            </div>
        @else
            <div class="px-6 py-12 text-center">
                <flux:icon.chart-bar class="mx-auto h-12 w-12 text-zinc-400" />
                <h3 class="mt-2 text-sm font-medium text-zinc-900 dark:text-zinc-100">Belum ada logbook</h3>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                    @if($search || $selectedUserId)
                        Tidak ditemukan logbook yang sesuai dengan filter yang dipilih.
                    @else
                        Belum ada logbook yang dibuat untuk periode ini.
                    @endif
                </p>
                @if($search || $selectedUserId)
                    <div class="mt-6">
                        <flux:button wire:click="resetAllFilters" variant="ghost">
                            Reset Filter
                        </flux:button>
                    </div>
                @endif
            </div>
        @endif
    </div>

    <!-- Export Modal -->
    <flux:modal wire:model="showExportModal" class="space-y-6 max-w-2xl">
        <div>
            <flux:heading size="lg">Export Logbook</flux:heading>
            <flux:subheading>Pilih filter data yang ingin diekspor ke Excel.</flux:subheading>
        </div>

        <div class="space-y-6">
            <!-- User Filter -->
            <div>
                <flux:field>
                    <flux:label>Pengguna</flux:label>
                    <flux:select wire:model="exportUserId" placeholder="Pilih Pengguna">
                        <flux:select.option value="">Semua Pengguna</flux:select.option>
                        @foreach($users as $user)
                            <flux:select.option value="{{ $user->id }}">{{ $user->name }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </flux:field>
            </div>

            <!-- Date Range -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <flux:field>
                    <flux:label>Dari Tanggal</flux:label>
                    <flux:input
                        wire:model="exportDateFrom"
                        type="date"
                        required
                    />
                </flux:field>

                <flux:field>
                    <flux:label>Sampai Tanggal</flux:label>
                    <flux:input
                        wire:model="exportDateTo"
                        type="date"
                        required
                    />
                </flux:field>
            </div>
        </div>

        <div class="flex justify-end space-x-2">
            <flux:button variant="ghost" wire:click="closeExportModal">Batal</flux:button>
            <flux:button
                type="submit"
                variant="primary"
                wire:click="exportLogbook"
                :disabled="!$exportDateFrom || !$exportDateTo"
            >
                Export Excel
            </flux:button>
        </div>
    </flux:modal>
</div>
