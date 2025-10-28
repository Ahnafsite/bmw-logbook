<div>
    <!-- Title and Breadcrumb -->
    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4 mb-8">
        <!-- Title and Description (Left) -->
        <div class="flex-1">
            <flux:heading size="xl" class="text-zinc-900 dark:text-zinc-100">
                Verifikasi Pendaftar
            </flux:heading>
            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">
                Verifikasi pendaftar untuk gelombang penerimaan {{ $admissionWave->name }}.
            </p>
        </div>

        <!-- Breadcrumb (Right) -->
        <nav class="flex-shrink-0" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 text-sm text-zinc-500 dark:text-zinc-400">
                <li>
                    <a href="{{ route('verification.list-admission') }}" class="hover:text-zinc-700 dark:hover:text-zinc-300">
                        Verifikasi
                    </a>
                </li>
                <li>
                    <flux:icon.chevron-right class="h-4 w-4" />
                </li>
                <li>
                    <span class="font-medium text-zinc-900 dark:text-zinc-100">{{ $admissionWave->name }}</span>
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

    <!-- Admission Wave Detail Card -->
    <div class="bg-white dark:bg-zinc-900 shadow-sm rounded-lg border border-zinc-200 dark:border-zinc-700 mb-6">
        <div class="px-6 py-4">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">{{ $admissionWave->name }}</h3>
                </div>
                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $admissionWave->status === 'Open' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                    {{ $admissionWave->status === 'Open' ? 'Buka' : 'Tutup' }}
                </span>
            </div>
            <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div>
                    <span class="font-medium text-zinc-900 dark:text-zinc-100">Tahun Akademik:</span>
                    <span class="text-zinc-600 dark:text-zinc-400">{{ $admissionWave->academicYear->year }} ({{ $admissionWave->academicYear->semester === 'Odd' ? 'Ganjil' : 'Genap' }})</span>
                </div>
                <div>
                    <span class="font-medium text-zinc-900 dark:text-zinc-100">Periode:</span>
                    <span class="text-zinc-600 dark:text-zinc-400">{{ \Carbon\Carbon::parse($admissionWave->start_date)->format('d F Y') }} - {{ \Carbon\Carbon::parse($admissionWave->end_date)->format('d F Y') }}</span>
                </div>
                <div>
                    <span class="font-medium text-zinc-900 dark:text-zinc-100">Biaya:</span>
                    <span class="text-zinc-600 dark:text-zinc-400">Rp {{ number_format($admissionWave->registration_fee, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Input -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div class="flex-1 max-w-md">
            <flux:input
                wire:model.live.debounce.300ms="search"
                placeholder="Cari berdasarkan nama, NIK, atau email..."
                icon="magnifying-glass"
            />
        </div>
    </div>

    <!-- Applicants Table -->
    <div class="bg-white dark:bg-zinc-900 shadow-sm rounded-lg border border-zinc-200 dark:border-zinc-700">
        @if($applicants->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                    <thead class="bg-zinc-50 dark:bg-zinc-800">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Pendaftar</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">NIK</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Program Studi Pilihan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Status Verifikasi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Tanggal Daftar</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-zinc-900 divide-y divide-zinc-200 dark:divide-zinc-700">
                        @foreach($applicants as $index => $applicant)
                            <tr wire:key="{{ $applicant->id }}" class="hover:bg-zinc-50 dark:hover:bg-zinc-800">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">
                                    {{ $applicants->firstItem() + $index }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <span class="font-medium text-zinc-900 dark:text-zinc-100">{{ $applicant->full_name }}</span>
                                        <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ $applicant->user->name ?? 'N/A' }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-900 dark:text-zinc-100">
                                    {{ $applicant->nik }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-900 dark:text-zinc-100">
                                    {{ $applicant->email }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm">
                                        @if($applicant->firstAdmissionStudyProgram)
                                            <div class="text-zinc-900 dark:text-zinc-100">1. {{ $applicant->firstAdmissionStudyProgram->studyProgram->name }}</div>
                                        @endif
                                        @if($applicant->secondAdmissionStudyProgram)
                                            <div class="text-zinc-600 dark:text-zinc-400">2. {{ $applicant->secondAdmissionStudyProgram->studyProgram->name }}</div>
                                        @endif
                                        @if($applicant->thirdAdmissionStudyProgram)
                                            <div class="text-zinc-600 dark:text-zinc-400">3. {{ $applicant->thirdAdmissionStudyProgram->studyProgram->name }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColor = match($applicant->registration_status->value ?? '') {
                                            'Pending Verification' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                            'Verified' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                            'Rejected' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                            default => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200'
                                        };
                                        $statusText = match($applicant->registration_status->value ?? '') {
                                            'Pending Verification' => 'Menunggu Verifikasi',
                                            'Verified' => 'Terverifikasi',
                                            'Rejected' => 'Ditolak',
                                            default => 'Tidak Diketahui'
                                        };
                                    @endphp
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusColor }}">
                                        {{ $statusText }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">
                                    {{ $applicant->created_at->format('d M Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center gap-2">
                                        <button
                                            wire:click="openVerificationModal({{ $applicant->id }})"
                                            class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                            title="Verifikasi"
                                        >
                                            <flux:icon.shield-check class="w-4 h-4" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Custom Pagination -->
            <div class="px-6 pb-4">
                <x-custom-pagination :paginator="$applicants" />
            </div>
        @else
            <div class="text-center py-12 p-6">
                <div class="mx-auto h-12 w-12 text-zinc-400 dark:text-zinc-500 mb-4">
                    <flux:icon.users class="w-12 h-12" />
                </div>
                <h3 class="mt-2 text-sm font-medium text-zinc-900 dark:text-zinc-100">Tidak ada pendaftar</h3>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                    @if($search)
                        Tidak ada pendaftar yang ditemukan untuk "{{ $search }}".
                    @else
                        Belum ada pendaftar pada gelombang penerimaan ini.
                    @endif
                </p>
            </div>
        @endif
    </div>

    <!-- Verification Modal -->
    <flux:modal wire:model="showVerificationModal" class="w-full max-w-6xl">
        @if($selectedApplicant)
        <div class="space-y-6">
            <!-- Header -->
            <div class="flex items-start justify-between">
                <div>
                    <flux:heading size="lg" class="text-zinc-900 dark:text-zinc-100">
                        Verifikasi Pendaftar
                    </flux:heading>
                    <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">
                        {{ $selectedApplicant->full_name }} ({{ $selectedApplicant->nik }})
                    </p>
                </div>
            </div>

            <!-- Applicant Information -->
            <div class="bg-zinc-50 dark:bg-zinc-800 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4">Informasi Pendaftar</h3>

                <!-- Photo Section -->
                @if($selectedApplicant->photo)
                    <div class="mb-6 flex items-center gap-4">
                        <img class="h-24 w-18 rounded-lg object-cover border-2 border-zinc-200 dark:border-zinc-700"
                             src="{{ asset('storage/' . $selectedApplicant->photo) }}"
                             alt="{{ $selectedApplicant->full_name }}"
                             style="aspect-ratio: 3/4;">
                        <div>
                            <h5 class="text-md font-medium text-zinc-800 dark:text-zinc-200">Foto Profil</h5>
                            <p class="text-sm text-zinc-600 dark:text-zinc-400">{{ $selectedApplicant->full_name }}</p>
                        </div>
                    </div>
                @endif

                <!-- Personal Data Section -->
                <div class="mb-6">
                    <h5 class="text-md font-medium text-zinc-800 dark:text-zinc-200 mb-4 pb-2 border-b border-zinc-200 dark:border-zinc-700">Data Pribadi</h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex">
                            <span class="w-32 text-sm font-medium text-zinc-600 dark:text-zinc-400 flex-shrink-0">Nama Lengkap</span>
                            <span class="text-sm text-zinc-600 dark:text-zinc-400 mx-2">:</span>
                            <span class="text-sm text-zinc-900 dark:text-zinc-100 flex-1">{{ $selectedApplicant->full_name ?: '-' }}</span>
                        </div>
                        <div class="flex">
                            <span class="w-32 text-sm font-medium text-zinc-600 dark:text-zinc-400 flex-shrink-0">NIK</span>
                            <span class="text-sm text-zinc-600 dark:text-zinc-400 mx-2">:</span>
                            <span class="text-sm text-zinc-900 dark:text-zinc-100 flex-1">{{ $selectedApplicant->nik ?: '-' }}</span>
                        </div>
                        <div class="flex">
                            <span class="w-32 text-sm font-medium text-zinc-600 dark:text-zinc-400 flex-shrink-0">Email</span>
                            <span class="text-sm text-zinc-600 dark:text-zinc-400 mx-2">:</span>
                            <span class="text-sm text-zinc-900 dark:text-zinc-100 flex-1">{{ $selectedApplicant->email ?: '-' }}</span>
                        </div>
                        <div class="flex">
                            <span class="w-32 text-sm font-medium text-zinc-600 dark:text-zinc-400 flex-shrink-0">Telepon</span>
                            <span class="text-sm text-zinc-600 dark:text-zinc-400 mx-2">:</span>
                            <span class="text-sm text-zinc-900 dark:text-zinc-100 flex-1">{{ $selectedApplicant->phone_number ? '+62' . $selectedApplicant->phone_number : '-' }}</span>
                        </div>
                        <div class="flex">
                            <span class="w-32 text-sm font-medium text-zinc-600 dark:text-zinc-400 flex-shrink-0">Tempat Lahir</span>
                            <span class="text-sm text-zinc-600 dark:text-zinc-400 mx-2">:</span>
                            <span class="text-sm text-zinc-900 dark:text-zinc-100 flex-1">{{ $selectedApplicant->place_of_birth ?: '-' }}</span>
                        </div>
                        <div class="flex">
                            <span class="w-32 text-sm font-medium text-zinc-600 dark:text-zinc-400 flex-shrink-0">Tanggal Lahir</span>
                            <span class="text-sm text-zinc-600 dark:text-zinc-400 mx-2">:</span>
                            <span class="text-sm text-zinc-900 dark:text-zinc-100 flex-1">{{ $selectedApplicant->date_of_birth ? $selectedApplicant->date_of_birth->format('d F Y') : '-' }}</span>
                        </div>
                        <div class="flex">
                            <span class="w-32 text-sm font-medium text-zinc-600 dark:text-zinc-400 flex-shrink-0">Jenis Kelamin</span>
                            <span class="text-sm text-zinc-600 dark:text-zinc-400 mx-2">:</span>
                            <span class="text-sm text-zinc-900 dark:text-zinc-100 flex-1">{{ $selectedApplicant->gender ? ($selectedApplicant->gender->value == 'Male' ? 'Laki-laki' : 'Perempuan') : '-' }}</span>
                        </div>
                        <div class="flex">
                            <span class="w-32 text-sm font-medium text-zinc-600 dark:text-zinc-400 flex-shrink-0">Kewarganegaraan</span>
                            <span class="text-sm text-zinc-600 dark:text-zinc-400 mx-2">:</span>
                            <span class="text-sm text-zinc-900 dark:text-zinc-100 flex-1">{{ $selectedApplicant->nationality ? strtoupper($selectedApplicant->nationality->value) : '-' }}</span>
                        </div>
                        <div class="flex">
                            <span class="w-32 text-sm font-medium text-zinc-600 dark:text-zinc-400 flex-shrink-0">Jenis Tinggal</span>
                            <span class="text-sm text-zinc-600 dark:text-zinc-400 mx-2">:</span>
                            <span class="text-sm text-zinc-900 dark:text-zinc-100 flex-1">{{ $selectedApplicant->type_of_resident ? ucwords(str_replace('_', ' ', $selectedApplicant->type_of_resident->value)) : '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Address Section -->
                <div class="mb-6">
                    <h5 class="text-md font-medium text-zinc-800 dark:text-zinc-200 mb-4 pb-2 border-b border-zinc-200 dark:border-zinc-700">Alamat</h5>
                    <div class="flex mb-3">
                        <span class="w-32 text-sm font-medium text-zinc-600 dark:text-zinc-400 flex-shrink-0">Alamat Lengkap</span>
                        <span class="text-sm text-zinc-600 dark:text-zinc-400 mx-2">:</span>
                        <span class="text-sm text-zinc-900 dark:text-zinc-100 flex-1">{{ $selectedApplicant->address ?: '-' }}</span>
                    </div>
                    @if($selectedApplicant->village)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex">
                                <span class="w-32 text-sm font-medium text-zinc-600 dark:text-zinc-400 flex-shrink-0">Provinsi</span>
                                <span class="text-sm text-zinc-600 dark:text-zinc-400 mx-2">:</span>
                                <span class="text-sm text-zinc-900 dark:text-zinc-100 flex-1">{{ $selectedApplicant->village->district->city->province->name }}</span>
                            </div>
                            <div class="flex">
                                <span class="w-32 text-sm font-medium text-zinc-600 dark:text-zinc-400 flex-shrink-0">Kota/Kabupaten</span>
                                <span class="text-sm text-zinc-600 dark:text-zinc-400 mx-2">:</span>
                                <span class="text-sm text-zinc-900 dark:text-zinc-100 flex-1">{{ $selectedApplicant->village->district->city->name }}</span>
                            </div>
                            <div class="flex">
                                <span class="w-32 text-sm font-medium text-zinc-600 dark:text-zinc-400 flex-shrink-0">Kecamatan</span>
                                <span class="text-sm text-zinc-600 dark:text-zinc-400 mx-2">:</span>
                                <span class="text-sm text-zinc-900 dark:text-zinc-100 flex-1">{{ $selectedApplicant->village->district->name }}</span>
                            </div>
                            <div class="flex">
                                <span class="w-32 text-sm font-medium text-zinc-600 dark:text-zinc-400 flex-shrink-0">Desa/Kelurahan</span>
                                <span class="text-sm text-zinc-600 dark:text-zinc-400 mx-2">:</span>
                                <span class="text-sm text-zinc-900 dark:text-zinc-100 flex-1">{{ $selectedApplicant->village->name }}</span>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Education Section -->
                <div class="mb-6">
                    <h5 class="text-md font-medium text-zinc-800 dark:text-zinc-200 mb-4 pb-2 border-b border-zinc-200 dark:border-zinc-700">Riwayat Pendidikan</h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex">
                            <span class="w-32 text-sm font-medium text-zinc-600 dark:text-zinc-400 flex-shrink-0">Sekolah Asal</span>
                            <span class="text-sm text-zinc-600 dark:text-zinc-400 mx-2">:</span>
                            <span class="text-sm text-zinc-900 dark:text-zinc-100 flex-1">{{ $selectedApplicant->previous_school ?: '-' }}</span>
                        </div>
                        <div class="flex">
                            <span class="w-32 text-sm font-medium text-zinc-600 dark:text-zinc-400 flex-shrink-0">Jenis Sekolah</span>
                            <span class="text-sm text-zinc-600 dark:text-zinc-400 mx-2">:</span>
                            <span class="text-sm text-zinc-900 dark:text-zinc-100 flex-1">{{ $selectedApplicant->previous_school_type ? strtoupper($selectedApplicant->previous_school_type->value) : '-' }}</span>
                        </div>
                        <div class="flex md:col-span-2">
                            <span class="w-32 text-sm font-medium text-zinc-600 dark:text-zinc-400 flex-shrink-0">Jurusan</span>
                            <span class="text-sm text-zinc-600 dark:text-zinc-400 mx-2">:</span>
                            <span class="text-sm text-zinc-900 dark:text-zinc-100 flex-1">{{ $selectedApplicant->previous_program_study ?: '-' }}</span>
                        </div>
                        <div class="flex md:col-span-2">
                            <span class="w-32 text-sm font-medium text-zinc-600 dark:text-zinc-400 flex-shrink-0">Bakat/Minat</span>
                            <span class="text-sm text-zinc-600 dark:text-zinc-400 mx-2">:</span>
                            <span class="text-sm text-zinc-900 dark:text-zinc-100 flex-1">{{ $selectedApplicant->talent ?: '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Study Program Choices -->
                <div>
                    <h5 class="text-md font-medium text-zinc-800 dark:text-zinc-200 mb-4 pb-2 border-b border-zinc-200 dark:border-zinc-700">Pilihan Program Studi</h5>
                    <div class="space-y-4">
                        <!-- Pilihan 1 -->
                        <div class="flex items-start gap-3">
                            <span class="w-6 h-6 bg-green-100 dark:bg-green-900/20 rounded-full flex items-center justify-center text-xs font-medium flex-shrink-0 mt-0.5">
                                1
                            </span>
                            <div class="flex-1">
                                <div class="flex">
                                    <span class="w-24 text-sm font-medium text-zinc-600 dark:text-zinc-400 flex-shrink-0">Pilihan 1</span>
                                    <span class="text-sm text-zinc-600 dark:text-zinc-400 mx-2">:</span>
                                    <span class="text-sm text-zinc-900 dark:text-zinc-100 flex-1">
                                        {{ $selectedApplicant->firstAdmissionStudyProgram ? $selectedApplicant->firstAdmissionStudyProgram->studyProgram->name : 'Tidak dipilih' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Pilihan 2 -->
                        <div class="flex items-start gap-3">
                            <span class="w-6 h-6 bg-green-100 dark:bg-green-900/20 rounded-full flex items-center justify-center text-xs font-medium flex-shrink-0 mt-0.5">
                                2
                            </span>
                            <div class="flex-1">
                                <div class="flex">
                                    <span class="w-24 text-sm font-medium text-zinc-600 dark:text-zinc-400 flex-shrink-0">Pilihan 2</span>
                                    <span class="text-sm text-zinc-600 dark:text-zinc-400 mx-2">:</span>
                                    <span class="text-sm text-zinc-900 dark:text-zinc-100 flex-1">
                                        {{ $selectedApplicant->secondAdmissionStudyProgram ? $selectedApplicant->secondAdmissionStudyProgram->studyProgram->name : 'Tidak dipilih' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Pilihan 3 -->
                        <div class="flex items-start gap-3">
                            <span class="w-6 h-6 bg-green-100 dark:bg-green-900/20 rounded-full flex items-center justify-center text-xs font-medium flex-shrink-0 mt-0.5">
                                3
                            </span>
                            <div class="flex-1">
                                <div class="flex">
                                    <span class="w-24 text-sm font-medium text-zinc-600 dark:text-zinc-400 flex-shrink-0">Pilihan 3</span>
                                    <span class="text-sm text-zinc-600 dark:text-zinc-400 mx-2">:</span>
                                    <span class="text-sm text-zinc-900 dark:text-zinc-100 flex-1">
                                        {{ $selectedApplicant->thirdAdmissionStudyProgram ? $selectedApplicant->thirdAdmissionStudyProgram->studyProgram->name : 'Tidak dipilih' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Parents Information -->
                <div class="mt-6">
                    <h5 class="text-md font-medium text-zinc-800 dark:text-zinc-200 mb-4 pb-2 border-b border-zinc-200 dark:border-zinc-700">Data Orang Tua</h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Father's Information -->
                        <div>
                            <h6 class="text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-3">Data Ayah</h6>
                            <div class="space-y-2">
                                <div class="flex">
                                    <span class="w-24 text-sm font-medium text-zinc-600 dark:text-zinc-400 flex-shrink-0">Nama</span>
                                    <span class="text-sm text-zinc-600 dark:text-zinc-400 mx-2">:</span>
                                    <span class="text-sm text-zinc-900 dark:text-zinc-100 flex-1">{{ $selectedApplicant->dad_name ?: '-' }}</span>
                                </div>
                                <div class="flex">
                                    <span class="w-24 text-sm font-medium text-zinc-600 dark:text-zinc-400 flex-shrink-0">Pekerjaan</span>
                                    <span class="text-sm text-zinc-600 dark:text-zinc-400 mx-2">:</span>
                                    <span class="text-sm text-zinc-900 dark:text-zinc-100 flex-1">{{ $selectedApplicant->dad_occupation ?: '-' }}</span>
                                </div>
                                <div class="flex">
                                    <span class="w-24 text-sm font-medium text-zinc-600 dark:text-zinc-400 flex-shrink-0">Pendidikan</span>
                                    <span class="text-sm text-zinc-600 dark:text-zinc-400 mx-2">:</span>
                                    <span class="text-sm text-zinc-900 dark:text-zinc-100 flex-1">{{ $selectedApplicant->dad_education ?: '-' }}</span>
                                </div>
                                <div class="flex">
                                    <span class="w-24 text-sm font-medium text-zinc-600 dark:text-zinc-400 flex-shrink-0">Penghasilan</span>
                                    <span class="text-sm text-zinc-600 dark:text-zinc-400 mx-2">:</span>
                                    <span class="text-sm text-zinc-900 dark:text-zinc-100 flex-1">{{ $selectedApplicant->dad_income ?: '-' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Mother's Information -->
                        <div>
                            <h6 class="text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-3">Data Ibu</h6>
                            <div class="space-y-2">
                                <div class="flex">
                                    <span class="w-24 text-sm font-medium text-zinc-600 dark:text-zinc-400 flex-shrink-0">Nama</span>
                                    <span class="text-sm text-zinc-600 dark:text-zinc-400 mx-2">:</span>
                                    <span class="text-sm text-zinc-900 dark:text-zinc-100 flex-1">{{ $selectedApplicant->mom_name ?: '-' }}</span>
                                </div>
                                <div class="flex">
                                    <span class="w-24 text-sm font-medium text-zinc-600 dark:text-zinc-400 flex-shrink-0">Pekerjaan</span>
                                    <span class="text-sm text-zinc-600 dark:text-zinc-400 mx-2">:</span>
                                    <span class="text-sm text-zinc-900 dark:text-zinc-100 flex-1">{{ $selectedApplicant->mom_occupation ?: '-' }}</span>
                                </div>
                                <div class="flex">
                                    <span class="w-24 text-sm font-medium text-zinc-600 dark:text-zinc-400 flex-shrink-0">Pendidikan</span>
                                    <span class="text-sm text-zinc-600 dark:text-zinc-400 mx-2">:</span>
                                    <span class="text-sm text-zinc-900 dark:text-zinc-100 flex-1">{{ $selectedApplicant->mom_education ?: '-' }}</span>
                                </div>
                                <div class="flex">
                                    <span class="w-24 text-sm font-medium text-zinc-600 dark:text-zinc-400 flex-shrink-0">Penghasilan</span>
                                    <span class="text-sm text-zinc-600 dark:text-zinc-400 mx-2">:</span>
                                    <span class="text-sm text-zinc-900 dark:text-zinc-100 flex-1">{{ $selectedApplicant->mom_income ?: '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Information -->
                @if($selectedApplicantPayments->count() > 0)
                    <div class="mt-6">
                        <h5 class="text-md font-medium text-zinc-800 dark:text-zinc-200 mb-4 pb-2 border-b border-zinc-200 dark:border-zinc-700">Informasi Pembayaran</h5>
                        <div class="space-y-4">
                            @foreach($selectedApplicantPayments as $payment)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="flex">
                                        <span class="w-32 text-sm font-medium text-zinc-600 dark:text-zinc-400 flex-shrink-0">Jumlah</span>
                                        <span class="text-sm text-zinc-600 dark:text-zinc-400 mx-2">:</span>
                                        <span class="text-sm text-zinc-900 dark:text-zinc-100 flex-1">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex">
                                        <span class="w-32 text-sm font-medium text-zinc-600 dark:text-zinc-400 flex-shrink-0">Bank</span>
                                        <span class="text-sm text-zinc-600 dark:text-zinc-400 mx-2">:</span>
                                        <span class="text-sm text-zinc-900 dark:text-zinc-100 flex-1">{{ $payment->bank_name }}</span>
                                    </div>
                                    <div class="flex">
                                        <span class="w-32 text-sm font-medium text-zinc-600 dark:text-zinc-400 flex-shrink-0">Nama Rekening</span>
                                        <span class="text-sm text-zinc-600 dark:text-zinc-400 mx-2">:</span>
                                        <span class="text-sm text-zinc-900 dark:text-zinc-100 flex-1">{{ $payment->account_name }}</span>
                                    </div>
                                    <div class="flex">
                                        <span class="w-32 text-sm font-medium text-zinc-600 dark:text-zinc-400 flex-shrink-0">No. Rekening</span>
                                        <span class="text-sm text-zinc-600 dark:text-zinc-400 mx-2">:</span>
                                        <span class="text-sm text-zinc-900 dark:text-zinc-100 flex-1">{{ $payment->account_number }}</span>
                                    </div>
                                    @if($payment->file)
                                        <div class="flex md:col-span-2">
                                            <span class="w-32 text-sm font-medium text-zinc-600 dark:text-zinc-400 flex-shrink-0">Bukti Transfer</span>
                                            <span class="text-sm text-zinc-600 dark:text-zinc-400 mx-2">:</span>
                                            <div class="flex-1">
                                                <a href="{{ asset('storage/' . $payment->file) }}" target="_blank" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                                    <flux:icon.document-arrow-down class="w-4 h-4 mr-1" />
                                                    Unduh Bukti Transfer
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                @if(!$loop->last)<hr class="border-zinc-200 dark:border-zinc-700">@endif
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Requirement Files -->
                @if($selectedApplicantUploads->count() > 0)
                    <div class="mt-6">
                        <h5 class="text-md font-medium text-zinc-800 dark:text-zinc-200 mb-4 pb-2 border-b border-zinc-200 dark:border-zinc-700">Berkas Persyaratan</h5>
                        <div class="space-y-3">
                            @foreach($selectedApplicantUploads as $upload)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @if($upload->file_path)
                                        <div class="flex">
                                            <span class="w-32 text-sm font-medium text-zinc-600 dark:text-zinc-400 flex-shrink-0">{{ $upload->requirementFile->name ?? 'File Requirement' }}</span>
                                            <span class="text-sm text-zinc-600 dark:text-zinc-400 mx-2">:</span>
                                            <div class="flex-1">
                                                <a href="{{ asset('storage/' . $upload->file_path) }}" target="_blank" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                                    <flux:icon.document-arrow-down class="w-4 h-4 mr-1" />
                                                    Unduh
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                @if(!$loop->last)<hr class="border-zinc-200 dark:border-zinc-700">@endif
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Verification Form -->
            <div class="bg-zinc-50 dark:bg-zinc-800 rounded-lg px-6">
                <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4">Status Verifikasi</h3>
                <div class="space-y-4">
                    <div>
                        <flux:select wire:model="verificationStatus" placeholder="Pilih Status Verifikasi">
                            @foreach($registrationStatusOptions as $value => $label)
                                <flux:select.option value="{{ $value }}">{{ $label }}</flux:select.option>
                            @endforeach
                        </flux:select>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-3 pt-4 border-t border-zinc-200 dark:border-zinc-700">
                <flux:button type="button" wire:click="closeVerificationModal" variant="ghost">
                    Batal
                </flux:button>
                <flux:button type="button" wire:click="saveVerification" variant="primary">
                    Simpan Verifikasi
                </flux:button>
            </div>
        </div>
        @endif
    </flux:modal>
</div>
