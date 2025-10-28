<div>
    <!-- Title and Breadcrumb -->
    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4 mb-8">
        <!-- Title and Description (Left) -->
        <div class="flex-1">
            <flux:heading size="xl" class="text-zinc-900 dark:text-zinc-100">
                Divisi dan Jabatan
            </flux:heading>
            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">
                Kelola data divisi dan jabatan untuk sistem organisasi.
            </p>
        </div>

        <!-- Breadcrumb (Right) -->
        <nav class="flex-shrink-0" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 text-sm text-zinc-500 dark:text-zinc-400">
                <li>
                    <span class="text-zinc-900 dark:text-zinc-100">Referensi</span>
                </li>
                <li>
                    <flux:icon.chevron-right class="h-4 w-4" />
                </li>
                <li>
                    <span class="font-medium text-zinc-900 dark:text-zinc-100">Divisi dan Jabatan</span>
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

    <!-- Tabs -->
    <div class="mb-6">
        <nav class="flex space-x-8" aria-label="Tabs">
            <button
                wire:click="switchTab('positions')"
                class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'positions' ? 'border-green-500 text-green-600 dark:text-green-400' : 'border-transparent text-zinc-500 hover:text-zinc-700 hover:border-zinc-300 dark:text-zinc-400 dark:hover:text-zinc-300' }}"
            >
                Jabatan
            </button>
            <button
                wire:click="switchTab('divisions')"
                class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'divisions' ? 'border-green-500 text-green-600 dark:text-green-400' : 'border-transparent text-zinc-500 hover:text-zinc-700 hover:border-zinc-300 dark:text-zinc-400 dark:hover:text-zinc-300' }}"
            >
                Divisi
            </button>
        </nav>
    </div>

    <!-- Positions Tab -->
    @if($activeTab === 'positions')
        <!-- Search and Add Button for Positions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <!-- Left side: Search -->
            <div class="flex-1 max-w-md">
                <flux:input
                    wire:model.live.debounce.300ms="positionSearch"
                    placeholder="Cari jabatan..."
                    icon="magnifying-glass"
                />
            </div>

            <!-- Right side: Add Button -->
            <div class="flex-shrink-0">
                <flux:button wire:click="openPositionCreateModal" icon="plus" variant="primary">
                    Tambah Jabatan
                </flux:button>
            </div>
        </div>

        <!-- Positions Table -->
        <div class="bg-white dark:bg-zinc-900 shadow-sm rounded-lg border border-zinc-200 dark:border-zinc-700">
            @if($positions->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                        <thead class="bg-zinc-50 dark:bg-zinc-800">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">No</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Nama Jabatan</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Deskripsi</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-zinc-900 divide-y divide-zinc-200 dark:divide-zinc-700">
                            @foreach($positions as $index => $position)
                                <tr wire:key="position-{{ $position->id }}" class="hover:bg-zinc-50 dark:hover:bg-zinc-800">
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-zinc-900 dark:text-zinc-100">
                                        {{ $positions->firstItem() + $index }}
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                        {{ $position->name }}
                                    </td>
                                    <td class="px-4 py-2 text-sm text-zinc-500 dark:text-zinc-400">
                                        {{ $position->desc ?: '-' }}
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            <flux:button wire:click="editPosition({{ $position->id }})" size="sm" variant="primary" color="green" icon="pencil"></flux:button>
                                            <flux:button wire:click="deletePosition({{ $position->id }})" size="sm" variant="danger" icon="trash"></flux:button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-4 py-3 border-t border-zinc-200 dark:border-zinc-700">
                    <x-custom-pagination :paginator="$positions" perPageModel="positionPerPage" />
                </div>
            @else
                <div class="px-6 py-12 text-center">
                    <flux:icon.building-office class="mx-auto h-12 w-12 text-zinc-400" />
                    <h3 class="mt-2 text-sm font-medium text-zinc-900 dark:text-zinc-100">Belum ada jabatan</h3>
                    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                        @if($positionSearch)
                            Tidak ditemukan jabatan yang sesuai dengan pencarian "{{ $positionSearch }}".
                        @else
                            Mulai dengan membuat jabatan pertama.
                        @endif
                    </p>
                    @if(!$positionSearch)
                        <div class="mt-6">
                            <flux:button wire:click="openPositionCreateModal" icon="plus" variant="primary">
                                Tambah Jabatan
                            </flux:button>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    @endif

    <!-- Divisions Tab -->
    @if($activeTab === 'divisions')
        <!-- Search and Add Button for Divisions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <!-- Left side: Search -->
            <div class="flex-1 max-w-md">
                <flux:input
                    wire:model.live.debounce.300ms="divisionSearch"
                    placeholder="Cari divisi..."
                    icon="magnifying-glass"
                />
            </div>

            <!-- Right side: Add Button -->
            <div class="flex-shrink-0">
                <flux:button wire:click="openDivisionCreateModal" icon="plus" variant="primary">
                    Tambah Divisi
                </flux:button>
            </div>
        </div>

        <!-- Divisions Table -->
        <div class="bg-white dark:bg-zinc-900 shadow-sm rounded-lg border border-zinc-200 dark:border-zinc-700">
            @if($divisions->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                        <thead class="bg-zinc-50 dark:bg-zinc-800">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">No</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Nama Divisi</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Deskripsi</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-zinc-900 divide-y divide-zinc-200 dark:divide-zinc-700">
                            @foreach($divisions as $index => $division)
                                <tr wire:key="division-{{ $division->id }}" class="hover:bg-zinc-50 dark:hover:bg-zinc-800">
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-zinc-900 dark:text-zinc-100">
                                        {{ $divisions->firstItem() + $index }}
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                        {{ $division->name }}
                                    </td>
                                    <td class="px-4 py-2 text-sm text-zinc-500 dark:text-zinc-400">
                                        {{ $division->desc ?: '-' }}
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            <flux:button wire:click="editDivision({{ $division->id }})" size="sm" variant="primary" color="green" icon="pencil"></flux:button>
                                            <flux:button wire:click="deleteDivision({{ $division->id }})" size="sm" variant="danger" icon="trash"></flux:button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-4 py-3 border-t border-zinc-200 dark:border-zinc-700">
                    <x-custom-pagination :paginator="$divisions" perPageModel="divisionPerPage" />
                </div>
            @else
                <div class="px-6 py-12 text-center">
                    <flux:icon.building-office class="mx-auto h-12 w-12 text-zinc-400" />
                    <h3 class="mt-2 text-sm font-medium text-zinc-900 dark:text-zinc-100">Belum ada divisi</h3>
                    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                        @if($divisionSearch)
                            Tidak ditemukan divisi yang sesuai dengan pencarian "{{ $divisionSearch }}".
                        @else
                            Mulai dengan membuat divisi pertama.
                        @endif
                    </p>
                    @if(!$divisionSearch)
                        <div class="mt-6">
                            <flux:button wire:click="openDivisionCreateModal" icon="plus" variant="primary">
                                Tambah Divisi
                            </flux:button>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    @endif

    <!-- Position Create Modal -->
    <flux:modal wire:model="showPositionCreateModal" class="space-y-6 max-w-2xl w-full">
        <div>
            <flux:heading size="lg">Tambah Jabatan</flux:heading>
            <flux:subheading>Buat jabatan baru untuk sistem organisasi.</flux:subheading>
        </div>

        <div class="space-y-6">
            <flux:input
                wire:model="positionName"
                label="Nama Jabatan"
                placeholder="Masukkan nama jabatan..."
                required
            />

            <flux:textarea
                wire:model="positionDesc"
                label="Deskripsi"
                placeholder="Masukkan deskripsi jabatan (opsional)..."
                rows="4"
            />
        </div>

        <div class="flex justify-end space-x-2">
            <flux:button variant="ghost" wire:click="closePositionCreateModal">Batal</flux:button>
            <flux:button type="submit" variant="primary" wire:click="createPosition">Simpan</flux:button>
        </div>
    </flux:modal>

    <!-- Position Edit Modal -->
    <flux:modal wire:model="showPositionEditModal" class="space-y-6 max-w-2xl w-full">
        <div>
            <flux:heading size="lg">Edit Jabatan</flux:heading>
            <flux:subheading>Perbarui informasi jabatan.</flux:subheading>
        </div>

        <div class="space-y-6">
            <flux:input
                wire:model="editPositionName"
                label="Nama Jabatan"
                placeholder="Masukkan nama jabatan..."
                required
            />

            <flux:textarea
                wire:model="editPositionDesc"
                label="Deskripsi"
                placeholder="Masukkan deskripsi jabatan (opsional)..."
                rows="4"
            />
        </div>

        <div class="flex justify-end space-x-2">
            <flux:button variant="ghost" wire:click="closePositionEditModal">Batal</flux:button>
            <flux:button type="submit" variant="primary" wire:click="updatePosition">Perbarui</flux:button>
        </div>
    </flux:modal>

    <!-- Position Delete Confirmation Modal -->
    <flux:modal wire:model="showPositionDeleteModal" class="space-y-6">
        <div>
            <flux:heading size="lg">Hapus Jabatan</flux:heading>
            <flux:subheading>Apakah Anda yakin ingin menghapus jabatan ini? Tindakan ini tidak dapat dibatalkan.</flux:subheading>
        </div>

        @if($deletePositionModel)
            <div class="bg-zinc-50 dark:bg-zinc-800 rounded-lg p-4">
                <div class="text-sm">
                    <div class="font-medium text-zinc-900 dark:text-zinc-100">{{ $deletePositionModel->name }}</div>
                    <div class="text-zinc-500 dark:text-zinc-400">
                        {{ $deletePositionModel->desc ?: 'Tidak ada deskripsi' }}
                    </div>
                </div>
            </div>
        @endif

        <div class="flex justify-end space-x-2">
            <flux:button variant="ghost" wire:click="closePositionDeleteModal">Batal</flux:button>
            <flux:button variant="danger" wire:click="confirmPositionDelete">Hapus</flux:button>
        </div>
    </flux:modal>

    <!-- Division Create Modal -->
    <flux:modal wire:model="showDivisionCreateModal" class="space-y-6 max-w-2xl w-full">
        <div>
            <flux:heading size="lg">Tambah Divisi</flux:heading>
            <flux:subheading>Buat divisi baru untuk sistem organisasi.</flux:subheading>
        </div>

        <div class="space-y-6">
            <flux:input
                wire:model="divisionName"
                label="Nama Divisi"
                placeholder="Masukkan nama divisi..."
                required
            />

            <flux:textarea
                wire:model="divisionDesc"
                label="Deskripsi"
                placeholder="Masukkan deskripsi divisi (opsional)..."
                rows="4"
            />
        </div>

        <div class="flex justify-end space-x-2">
            <flux:button variant="ghost" wire:click="closeDivisionCreateModal">Batal</flux:button>
            <flux:button type="submit" variant="primary" wire:click="createDivision">Simpan</flux:button>
        </div>
    </flux:modal>

    <!-- Division Edit Modal -->
    <flux:modal wire:model="showDivisionEditModal" class="space-y-6 max-w-2xl w-full">
        <div>
            <flux:heading size="lg">Edit Divisi</flux:heading>
            <flux:subheading>Perbarui informasi divisi.</flux:subheading>
        </div>

        <div class="space-y-6">
            <flux:input
                wire:model="editDivisionName"
                label="Nama Divisi"
                placeholder="Masukkan nama divisi..."
                required
            />

            <flux:textarea
                wire:model="editDivisionDesc"
                label="Deskripsi"
                placeholder="Masukkan deskripsi divisi (opsional)..."
                rows="4"
            />
        </div>

        <div class="flex justify-end space-x-2">
            <flux:button variant="ghost" wire:click="closeDivisionEditModal">Batal</flux:button>
            <flux:button type="submit" variant="primary" wire:click="updateDivision">Perbarui</flux:button>
        </div>
    </flux:modal>

    <!-- Division Delete Confirmation Modal -->
    <flux:modal wire:model="showDivisionDeleteModal" class="space-y-6">
        <div>
            <flux:heading size="lg">Hapus Divisi</flux:heading>
            <flux:subheading>Apakah Anda yakin ingin menghapus divisi ini? Tindakan ini tidak dapat dibatalkan.</flux:subheading>
        </div>

        @if($deleteDivisionModel)
            <div class="bg-zinc-50 dark:bg-zinc-800 rounded-lg p-4">
                <div class="text-sm">
                    <div class="font-medium text-zinc-900 dark:text-zinc-100">{{ $deleteDivisionModel->name }}</div>
                    <div class="text-zinc-500 dark:text-zinc-400">
                        {{ $deleteDivisionModel->desc ?: 'Tidak ada deskripsi' }}
                    </div>
                </div>
            </div>
        @endif

        <div class="flex justify-end space-x-2">
            <flux:button variant="ghost" wire:click="closeDivisionDeleteModal">Batal</flux:button>
            <flux:button variant="danger" wire:click="confirmDivisionDelete">Hapus</flux:button>
        </div>
    </flux:modal>
</div>
