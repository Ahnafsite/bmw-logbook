<div>
    <!-- Title and Breadcrumb -->
    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4 mb-8">
        <!-- Title and Description (Left) -->
        <div class="flex-1">
            <flux:heading size="xl" class="text-zinc-900 dark:text-zinc-100">
                Verifikasi Gelombang Penerimaan
            </flux:heading>
            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">
                Verifikasi pendaftar untuk setiap gelombang penerimaan universitas.
            </p>
        </div>

        <!-- Breadcrumb (Right) -->
        <nav class="flex-shrink-0" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 text-sm text-zinc-500 dark:text-zinc-400">
                <li>
                    <span class="font-medium text-zinc-900 dark:text-zinc-100">Verifikasi</span>
                </li>
                <li>
                    <flux:icon.chevron-right class="h-4 w-4" />
                </li>
                <li>
                    <span class="font-medium text-zinc-900 dark:text-zinc-100">Gelombang Penerimaan</span>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Search Input -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div class="flex-1 max-w-md">
            <flux:input
                wire:model.live.debounce.300ms="search"
                placeholder="Cari berdasarkan nama, deskripsi, atau tahun akademik..."
                icon="magnifying-glass"
            />
        </div>
    </div>

    <!-- Admission Waves Table -->
    <div class="bg-white dark:bg-zinc-900 shadow-sm rounded-lg border border-zinc-200 dark:border-zinc-700">
        @if($admissionWaves->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                    <thead class="bg-zinc-50 dark:bg-zinc-800">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Gelombang Penerimaan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Tahun Akademik</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Periode</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Program Studi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Biaya Pendaftaran</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-zinc-900 divide-y divide-zinc-200 dark:divide-zinc-700">
                        @foreach($admissionWaves as $index => $admissionWave)
                            <tr wire:key="{{ $admissionWave->id }}" class="hover:bg-zinc-50 dark:hover:bg-zinc-800">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">
                                    {{ $admissionWaves->firstItem() + $index }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <span class="font-medium text-zinc-900 dark:text-zinc-100">{{ $admissionWave->name }}</span>
                                        <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1 max-w-xs truncate">{{ strip_tags($admissionWave->desc) }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-zinc-900 dark:text-zinc-100">{{ $admissionWave->academicYear->year }}</span>
                                    <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ $admissionWave->academicYear->semester === 'Odd' ? 'Ganjil' : 'Genap' }}</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm">
                                        <div class="text-zinc-900 dark:text-zinc-100">{{ \Carbon\Carbon::parse($admissionWave->start_date)->format('d M Y') }}</div>
                                        <div class="text-xs text-zinc-500 dark:text-zinc-400">{{ \Carbon\Carbon::parse($admissionWave->start_date)->format('H:i') }}</div>
                                        <div class="text-zinc-500 dark:text-zinc-400 mt-1">sampai {{ \Carbon\Carbon::parse($admissionWave->end_date)->format('d M Y') }}</div>
                                        <div class="text-xs text-zinc-500 dark:text-zinc-400">{{ \Carbon\Carbon::parse($admissionWave->end_date)->format('H:i') }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $admissionWave->status === 'Open' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                        {{ $admissionWave->status === 'Open' ? 'Buka' : 'Tutup' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                        {{ $admissionWave->admissionStudyPrograms->count() }} Program
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-900 dark:text-zinc-100">
                                    Rp {{ number_format($admissionWave->registration_fee, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center gap-2">
                                        <a
                                            href="{{ route('verification.list-applicant', $admissionWave->id) }}"
                                            class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                            title="Verifikasi Pendaftar"
                                        >
                                            <flux:icon.shield-check class="w-4 h-4" />
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Custom Pagination -->
            <div class="px-6 pb-4">
                <x-custom-pagination :paginator="$admissionWaves" />
            </div>
        @else
            <div class="text-center py-12 p-6">
                <div class="mx-auto h-12 w-12 text-zinc-400 dark:text-zinc-500 mb-4">
                    <svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
                <h3 class="mt-2 text-sm font-medium text-zinc-900 dark:text-zinc-100">Tidak ada gelombang penerimaan</h3>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                    @if($search)
                        Tidak ada gelombang penerimaan yang ditemukan untuk "{{ $search }}".
                    @else
                        Belum ada gelombang penerimaan yang perlu diverifikasi.
                    @endif
                </p>
            </div>
        @endif
    </div>
</div>
