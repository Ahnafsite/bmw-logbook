<div>
    <!-- Title and Breadcrumb -->
    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4 mb-8">
        <!-- Title and Description (Left) -->
        <div class="flex-1">
            <flux:heading size="xl" class="text-zinc-900 dark:text-zinc-100">
                Manajemen Pengguna
            </flux:heading>
            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">
                Kelola pengguna sistem dan atur hak akses mereka.
            </p>
        </div>

        <!-- Breadcrumb (Right) -->
        <nav class="flex-shrink-0" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 text-sm text-zinc-500 dark:text-zinc-400">
                <li>
                    <span class="font-medium text-zinc-900 dark:text-zinc-100">Manajemen Pengguna</span>
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

    <!-- Search -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div class="flex-1 max-w-md">
            <flux:input
                wire:model.live.debounce.300ms="search"
                placeholder="Cari berdasarkan nama, email, atau role..."
                icon="magnifying-glass"
            />
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-white dark:bg-zinc-900 shadow-sm rounded-lg border border-zinc-200 dark:border-zinc-700">
        @if($users->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                    <thead class="bg-zinc-50 dark:bg-zinc-800">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Posisi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Divisi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-zinc-900 divide-y divide-zinc-200 dark:divide-zinc-700">
                        @foreach($users as $index => $user)
                            <tr wire:key="{{ $user->id }}" class="hover:bg-zinc-50 dark:hover:bg-zinc-800">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">
                                    {{ $users->firstItem() + $index }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-zinc-200 dark:bg-zinc-700 flex items-center justify-center">
                                                <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                                                    {{ $user->initials() }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="flex items-center gap-2">
                                                <div class="text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                                    {{ $user->name }}
                                                </div>
                                                @if($user->id === auth()->id())
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                        Saya
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-900 dark:text-zinc-100">
                                    {{ $user->email }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($user->roles as $role)
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                                @if($role->name === 'admin') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                                @elseif($role->name === 'verificator') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                                @else bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 @endif">
                                                {{ ucfirst($role->name) }}
                                            </span>
                                        @endforeach
                                        @if($user->roles->count() === 0)
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
                                                Tidak ada role
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-zinc-900 dark:text-zinc-100">
                                        @if($user->userPositionAndDivision && $user->userPositionAndDivision->position)
                                            {{ $user->userPositionAndDivision->position->name }}
                                        @else
                                            <span class="text-zinc-500 dark:text-zinc-400">-</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-zinc-900 dark:text-zinc-100">
                                        @if($user->userPositionAndDivision && $user->userPositionAndDivision->division)
                                            {{ $user->userPositionAndDivision->division->name }}
                                        @else
                                            <span class="text-zinc-500 dark:text-zinc-400">-</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center gap-2">
                                        <button
                                            wire:click="editUser({{ $user->id }})"
                                            class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300"
                                            title="Edit Pengguna"
                                        >
                                            <flux:icon.pencil class="w-4 h-4" />
                                        </button>
                                        <button
                                            wire:click="manageRole({{ $user->id }})"
                                            class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                            title="Kelola Role, Posisi & Divisi"
                                        >
                                            <flux:icon.user-group class="w-4 h-4" />
                                        </button>
                                        @if($user->id !== auth()->id())
                                            <button
                                                wire:click="deleteUser({{ $user->id }})"
                                                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                                title="Hapus Pengguna"
                                            >
                                                <flux:icon.trash class="w-4 h-4" />
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Custom Pagination -->
            <div class="px-6 pb-4">
                <x-custom-pagination :paginator="$users" />
            </div>
        @else
            <div class="text-center py-12 p-6">
                <div class="mx-auto h-12 w-12 text-zinc-400 dark:text-zinc-500 mb-4">
                    <svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                    </svg>
                </div>
                <h3 class="mt-2 text-sm font-medium text-zinc-900 dark:text-zinc-100">Tidak ada pengguna ditemukan</h3>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                    @if($search)
                        Tidak ada pengguna yang ditemukan untuk "{{ $search }}".
                    @else
                        Belum ada pengguna yang terdaftar.
                    @endif
                </p>
            </div>
        @endif
    </div>

    <!-- Edit User Modal -->
    <flux:modal wire:model="showEditModal" class="md:w-96">
        <div class="space-y-6">
            <div class="text-center sm:text-left">
                <flux:heading size="lg" class="text-zinc-900 dark:text-zinc-100">
                    Edit Pengguna
                </flux:heading>
                <div class="mt-2">
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">
                        Perbarui informasi pengguna. Kosongkan password jika tidak ingin mengubah.
                    </p>
                </div>
            </div>

            <form wire:submit.prevent="updateUser" class="space-y-4">
                <div>
                    <flux:field>
                        <flux:label>Nama</flux:label>
                        <flux:input wire:model="editName" placeholder="Masukkan nama pengguna" />
                        <flux:error name="editName" />
                    </flux:field>
                </div>

                <div>
                    <flux:field>
                        <flux:label>Email</flux:label>
                        <flux:input type="email" wire:model="editEmail" placeholder="Masukkan email pengguna" />
                        <flux:error name="editEmail" />
                    </flux:field>
                </div>

                <div>
                    <flux:field>
                        <flux:label>Password (Opsional)</flux:label>
                        <flux:input
                            type="password"
                            wire:model="editPassword"
                            placeholder="Kosongkan jika tidak ingin mengubah password"
                        />
                        <flux:description>Minimal 8 karakter. Kosongkan jika tidak ingin mengubah password.</flux:description>
                        <flux:error name="editPassword" />
                    </flux:field>
                </div>

                <div class="flex gap-2 pt-4">
                    <flux:spacer />
                    <flux:button type="button" wire:click="closeEditModal" variant="ghost">
                        Batal
                    </flux:button>
                    <flux:button type="submit" variant="primary">
                        Simpan Perubahan
                    </flux:button>
                </div>
            </form>
        </div>
    </flux:modal>

    <!-- Role Management Modal -->
    <flux:modal wire:model="showRoleModal" class="md:w-[500px]">
        <div class="space-y-6">
                <div class="text-center sm:text-left">
                <flux:heading size="lg" class="text-zinc-900 dark:text-zinc-100">
                    Kelola Role, Posisi & Divisi
                </flux:heading>
                <div class="mt-2">
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">
                        Pilih role, posisi, dan divisi yang akan diberikan kepada pengguna
                        @if($roleUser)
                            <strong>{{ $roleUser->name }}</strong>.
                        @endif
                    </p>
                </div>
            </div>            @if($roleUser)
                <div class="bg-zinc-50 dark:bg-zinc-800 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                            <div class="h-10 w-10 rounded-full bg-zinc-200 dark:bg-zinc-700 flex items-center justify-center">
                                <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                                    {{ $roleUser->initials() }}
                                </span>
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                {{ $roleUser->name }}
                            </div>
                            <div class="text-sm text-zinc-500 dark:text-zinc-400">
                                {{ $roleUser->email }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <form wire:submit.prevent="updateRoles" class="space-y-4">
                <div class="space-y-3">
                    <label class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Role yang Tersedia</label>

                    @foreach($availableRoles as $role)
                        <div class="flex items-center">
                            <flux:checkbox
                                wire:model="selectedRoles"
                                value="{{ $role }}"
                                id="role-{{ $role }}"
                            />
                            <label for="role-{{ $role }}" class="ml-2 text-sm text-zinc-700 dark:text-zinc-300 cursor-pointer">
                                {{ ucfirst($role) }}
                            </label>
                        </div>
                    @endforeach
                </div>

                <!-- Position Selection -->
                <div class="space-y-3">
                    <flux:field>
                        <flux:label>Posisi</flux:label>
                        <flux:select wire:model="selectedPosition" placeholder="Pilih posisi...">
                            <flux:select.option value="">-- Pilih Posisi --</flux:select.option>
                            @foreach($availablePositions as $position)
                                <flux:select.option value="{{ $position->id }}">{{ $position->name }}</flux:select.option>
                            @endforeach
                        </flux:select>
                    </flux:field>
                </div>

                <!-- Division Selection -->
                <div class="space-y-3">
                    <flux:field>
                        <flux:label>Divisi</flux:label>
                        <flux:select wire:model="selectedDivision" placeholder="Pilih divisi...">
                            <flux:select.option value="">-- Pilih Divisi --</flux:select.option>
                            @foreach($availableDivisions as $division)
                                <flux:select.option value="{{ $division->id }}">{{ $division->name }}</flux:select.option>
                            @endforeach
                        </flux:select>
                    </flux:field>
                </div>

                <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-md p-3">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <flux:icon.exclamation-triangle class="h-5 w-5 text-yellow-400" />
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-800 dark:text-yellow-200">
                                Pastikan untuk memberikan role, posisi, dan divisi yang sesuai dengan tanggung jawab pengguna.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex gap-2 pt-4">
                    <flux:spacer />
                    <flux:button type="button" wire:click="closeRoleModal" variant="ghost">
                        Batal
                    </flux:button>
                    <flux:button type="submit" variant="primary">
                        Simpan Data
                    </flux:button>
                </div>
            </form>
        </div>
    </flux:modal>

    <!-- Delete User Confirmation Modal -->
    <flux:modal wire:model="showDeleteModal" class="md:w-96">
        <div class="space-y-6">
            <div class="text-center sm:text-left">
                <div class="flex items-center gap-3 mb-4">
                    <div class="flex-shrink-0">
                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/20">
                            <flux:icon.exclamation-triangle class="h-6 w-6 text-red-600 dark:text-red-400" />
                        </div>
                    </div>
                    <div>
                        <flux:heading size="lg" class="text-zinc-900 dark:text-zinc-100">
                            Hapus Pengguna
                        </flux:heading>
                    </div>
                </div>
                <div class="mt-2">
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">
                        Tindakan ini tidak dapat dibatalkan. Untuk mengonfirmasi penghapusan, ketik nama pengguna yang akan dihapus.
                    </p>
                </div>
            </div>

            @if($deleteUserModel)
                <div class="bg-zinc-50 dark:bg-zinc-800 rounded-lg p-4 border border-zinc-200 dark:border-zinc-700">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                            <div class="h-10 w-10 rounded-full bg-zinc-200 dark:bg-zinc-700 flex items-center justify-center">
                                <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                                    {{ $deleteUserModel->initials() }}
                                </span>
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                {{ $deleteUserModel->name }}
                            </div>
                            <div class="text-sm text-zinc-500 dark:text-zinc-400">
                                {{ $deleteUserModel->email }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <form wire:submit.prevent="confirmDelete" class="space-y-4">
                <div>
                    <flux:field>
                        <flux:label>Konfirmasi dengan mengetik nama pengguna</flux:label>
                        <flux:input
                            wire:model="confirmDeleteName"
                            placeholder="{{ $deleteUserModel ? $deleteUserModel->name : 'Nama pengguna' }}"
                            autocomplete="off"
                        />
                        <flux:description>
                            Ketik "<strong>{{ $deleteUserModel ? $deleteUserModel->name : '' }}</strong>" untuk mengonfirmasi penghapusan.
                        </flux:description>
                        <flux:error name="confirmDeleteName" />
                    </flux:field>
                </div>

                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-md p-3">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <flux:icon.exclamation-triangle class="h-5 w-5 text-red-400" />
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-800 dark:text-red-200">
                                <strong>Peringatan:</strong> Tindakan ini akan menghapus pengguna secara permanen dan tidak dapat dibatalkan.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex gap-2 pt-4">
                    <flux:spacer />
                    <flux:button type="button" wire:click="closeDeleteModal" variant="ghost">
                        Batal
                    </flux:button>
                    <flux:button type="submit" variant="danger">
                        Hapus Pengguna
                    </flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>
