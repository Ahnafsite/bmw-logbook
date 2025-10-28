<div>
    <!-- Title and Breadcrumb -->
    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4 mb-8">
        <!-- Title and Description (Left) -->
        <div class="flex-1">
            <flux:heading size="xl" class="text-zinc-900 dark:text-zinc-100">
                Logbook
            </flux:heading>
            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">
                Kelola catatan aktivitas harian Anda.
            </p>
        </div>

        <!-- Breadcrumb (Right) -->
        <nav class="flex-shrink-0" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 text-sm text-zinc-500 dark:text-zinc-400">
                <li>
                    <span class="font-medium text-zinc-900 dark:text-zinc-100">Logbook</span>
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

    <!-- Search, Filters and Add Button -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <!-- Left side: Search and Date Filters -->
        <div class="flex flex-col sm:flex-row sm:items-center gap-4 flex-1">
            <div class="flex-1 max-w-md">
                <flux:input
                    wire:model.live.debounce.300ms="search"
                    placeholder="Cari berdasarkan pekerjaan atau deskripsi..."
                    icon="magnifying-glass"
                />
            </div>
            <div class="flex gap-3 items-center">
                <flux:input
                    wire:model.live="dateFrom"
                    type="date"
                    class="w-36"
                />
                <flux:input
                    wire:model.live="dateTo"
                    type="date"
                    class="w-36"
                />
                <flux:button wire:click="resetDateFilter" variant="filled" size="sm">
                    Hari Ini
                </flux:button>
            </div>
        </div>

        <!-- Right side: Add Button -->
        <div class="flex-shrink-0">
            <flux:button wire:click="openCreateModal" icon="plus" variant="primary">
                Tambah Logbook
            </flux:button>
        </div>
    </div>

    <!-- Logbooks Table -->
    <div class="bg-white dark:bg-zinc-900 shadow-sm rounded-lg border border-zinc-200 dark:border-zinc-700">
        @if($logbooks->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                    <thead class="bg-zinc-50 dark:bg-zinc-800">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">No</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Tanggal</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Waktu</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Pekerjaan</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Deskripsi</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-zinc-900 divide-y divide-zinc-200 dark:divide-zinc-700">
                        @foreach($logbooks as $index => $logbook)
                            <tr wire:key="{{ $logbook->id }}" class="hover:bg-zinc-50 dark:hover:bg-zinc-800">
                                <td class="px-4 py-2 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">
                                    {{ $logbooks->firstItem() + $index }}
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
                                <td class="px-4 py-2 whitespace-nowrap text-sm">
                                    @if($logbook->date->format('Y-m-d') === now()->format('Y-m-d'))
                                        <div class="flex items-center gap-1">
                                            <flux:button
                                                size="sm"
                                                variant="primary"
                                                color="green"
                                                icon="pencil-square"
                                                wire:click="editLogbook({{ $logbook->id }})"
                                            >
                                            </flux:button>
                                            <flux:button
                                                size="sm"
                                                variant="primary"
                                                icon="trash"
                                                color="red"
                                                wire:click="deleteLogbook({{ $logbook->id }})"
                                            >
                                            </flux:button>
                                        </div>
                                    @else
                                        <span class="text-xs text-zinc-400">-</span>
                                    @endif
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
                <flux:icon.clipboard-document-list class="mx-auto h-12 w-12 text-zinc-400" />
                <h3 class="mt-2 text-sm font-medium text-zinc-900 dark:text-zinc-100">Belum ada logbook</h3>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                    @if($search)
                        Tidak ditemukan logbook yang sesuai dengan pencarian "{{ $search }}".
                    @else
                        Mulai dengan membuat catatan aktivitas pertama Anda.
                    @endif
                </p>
                @if(!$search)
                    <div class="mt-6">
                        <flux:button wire:click="openCreateModal" icon="plus" variant="primary">
                            Tambah Logbook
                        </flux:button>
                    </div>
                @endif
            </div>
        @endif
    </div>

    <!-- Create Logbook Modal -->
    <flux:modal wire:model="showCreateModal" class="space-y-6 max-w-4xl w-full">
        <div>
            <flux:heading size="lg">Tambah Logbook</flux:heading>
            <flux:subheading>Buat catatan aktivitas baru untuk hari ini.</flux:subheading>
        </div>

        <div class="space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <flux:input
                    wire:model="date"
                    label="Tanggal"
                    type="date"
                    disabled
                />
                <flux:input
                    wire:model="start_time"
                    label="Waktu Mulai"
                    type="time"
                    required
                />
                <flux:input
                    wire:model="end_time"
                    label="Waktu Selesai"
                    type="time"
                    required
                />
            </div>

            <flux:input
                wire:model="job"
                label="Pekerjaan"
                placeholder="Masukkan jenis pekerjaan..."
                required
            />

            <flux:textarea
                wire:model="desc"
                label="Deskripsi"
                placeholder="Masukkan deskripsi pekerjaan (opsional)..."
                rows="5"
            />
        </div>

        <div class="flex justify-end space-x-2">
            <flux:button variant="ghost" wire:click="closeCreateModal">Batal</flux:button>
            <flux:button type="submit" variant="primary" wire:click="createLogbook">Simpan</flux:button>
        </div>
    </flux:modal>

    <!-- Edit Logbook Modal -->
    <flux:modal wire:model="showEditModal" class="space-y-6 max-w-4xl w-full">
        <div>
            <flux:heading size="lg">Edit Logbook</flux:heading>
            <flux:subheading>Perbarui catatan aktivitas Anda.</flux:subheading>
        </div>

        <div class="space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <flux:input
                    wire:model="editDate"
                    label="Tanggal"
                    type="date"
                    required
                    disabled
                />
                <flux:input
                    wire:model="editStartTime"
                    label="Waktu Mulai"
                    type="time"
                    required
                />
                <flux:input
                    wire:model="editEndTime"
                    label="Waktu Selesai"
                    type="time"
                    required
                />
            </div>

            <flux:input
                wire:model="editJob"
                label="Pekerjaan"
                placeholder="Masukkan jenis pekerjaan..."
                required
            />

            <flux:textarea
                wire:model="editDesc"
                label="Deskripsi"
                placeholder="Masukkan deskripsi pekerjaan (opsional)..."
                rows="5"
            />
        </div>

        <div class="flex justify-end space-x-2">
            <flux:button variant="ghost" wire:click="closeEditModal">Batal</flux:button>
            <flux:button type="submit" variant="primary" wire:click="updateLogbook">Perbarui</flux:button>
        </div>
    </flux:modal>

    <!-- Delete Confirmation Modal -->
    <flux:modal wire:model="showDeleteModal" class="space-y-6">
        <div>
            <flux:heading size="lg">Hapus Logbook</flux:heading>
            <flux:subheading>Apakah Anda yakin ingin menghapus catatan ini? Tindakan ini tidak dapat dibatalkan.</flux:subheading>
        </div>

        @if($deleteLogbookModel)
            <div class="bg-zinc-50 dark:bg-zinc-800 rounded-lg p-4">
                <div class="text-sm">
                    <div class="font-medium text-zinc-900 dark:text-zinc-100">{{ $deleteLogbookModel->job }}</div>
                    <div class="text-zinc-500 dark:text-zinc-400">
                        {{ $deleteLogbookModel->date->format('d/m/Y') }} •
                        {{ $deleteLogbookModel->start_time->format('H:i') }} - {{ $deleteLogbookModel->end_time->format('H:i') }}
                    </div>
                </div>
            </div>
        @endif

        <div class="flex justify-end space-x-2">
            <flux:button variant="ghost" wire:click="closeDeleteModal">Batal</flux:button>
            <flux:button variant="danger" wire:click="confirmDelete">Hapus</flux:button>
        </div>
    </flux:modal>
</div>
