<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Card - {{ $applicant->full_name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
            .print-card {
                width: 210mm; /* A5 Landscape width */
                height: 148mm; /* A5 Landscape height */
                break-inside: avoid;
                page-break-inside: avoid;
            }
            @page {
                size: A5 landscape;
                margin: 0;
            }
        }

        /* Screen styles for preview */
        .print-card {
            width: 210mm;
            height: 148mm;
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Print Button (Hidden in print) -->
    <div class="no-print fixed top-4 right-4 z-50">
        <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 shadow-lg">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
            </svg>
            Print Card
        </button>
    </div>

    <div class="min-h-screen flex items-center justify-center">
        <!-- Registration Card A5 Landscape -->
        <div class="bg-gray-50 print-card flex flex-col">
            <!-- Card Header -->
            <div class="bg-gradient-to-r from-green-700 to-green-800 text-white p-2 text-center">
                <div class="flex items-center justify-between">
                    <div class="text-left">
                        <h1 class="text-xl font-bold">KARTU REGISTRASI PENDAFTAR</h1>
                        <p class="text-sm mt-1">STAIBMW PMB {{ $applicant->admissionWave->academicYear->year }} {{ $applicant->admissionWave->academicYear->semester === 'Odd' ? 'GANJIL' : 'GENAP' }}</p>
                    </div>

                    <!-- Registration Number -->
                    <div class="text-right">
                        <div class="bg-white text-green-800 px-4 py-2 rounded-lg">
                            <p class="text-xs font-medium">No. Registrasi</p>
                            <p class="text-sm font-bold">{{ $applicant->registration_number ?: 'PENDING' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Body A5 Layout -->
            <div class="p-1 flex gap-2 flex-1">
                <!-- Left Side: Photo and QR Code -->
                <div class="flex-shrink-0 space-y-1">
                    <!-- Photo Section -->
                    @if($applicant->photo)
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($applicant->photo) }}"
                             alt="{{ $applicant->full_name }}"
                             class="w-32 h-40 object-cover rounded border border-gray-300"
                             style="aspect-ratio: 3/4;">
                    @else
                        <div class="w-32 h-40 bg-gray-200 rounded border border-gray-300 flex items-center justify-center">
                            <svg class="w-16 h-16 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    @endif

                    <!-- QR Code Section -->
                    <div class="text-center bg-gray-50">
                        <img src="{{ $qrCodeDataUri }}" alt="QR Code" class="w-32 h-32 mx-auto border rounded">
                    </div>
                </div>

                <!-- Right Side: Personal Information -->
                <div class="flex-1 space-y-2">
                    <!-- Personal Data -->
                    <div class="bg-gray-50 p-3 rounded">
                        <h3 class="text-sm font-bold text-gray-800 mb-2 border-b pb-1">DATA PRIBADI</h3>
                        <div class="grid grid-cols-2 gap-x-4 gap-y-1 text-xs">
                            <div>
                                <p class="font-semibold text-gray-600">Nama:</p>
                                <p class="font-bold text-gray-900">{{ strtoupper($applicant->full_name) }}</p>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-600">NIK:</p>
                                <p class="text-gray-800">{{ $applicant->nik ?: '-' }}</p>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-600">Tempat, Tgl Lahir:</p>
                                <p class="text-gray-800">{{ $applicant->place_of_birth }}, {{ $applicant->date_of_birth ? $applicant->date_of_birth->format('d/m/Y') : '-' }}</p>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-600">Jenis Kelamin:</p>
                                <p class="text-gray-800">{{ $applicant->gender ? ($applicant->gender->value == 'Male' ? 'Laki-laki' : 'Perempuan') : '-' }}</p>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-600">Email:</p>
                                <p class="text-gray-800 truncate">{{ $applicant->email ?: '-' }}</p>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-600">Telepon:</p>
                                <p class="text-gray-800">{{ $applicant->phone_number ? '+' . $applicant->phone_number : '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="bg-gray-50 p-3 rounded">
                        <h3 class="text-sm font-bold text-gray-800 mb-2 border-b pb-1">ALAMAT</h3>
                        <div class="text-xs">
                            <p class="text-gray-800 mb-1">{{ $applicant->address ?: '-' }}</p>
                            @if($applicant->village)
                                <p class="text-gray-600">
                                    {{ $applicant->village->name }}, {{ $applicant->village->district->name }},
                                    {{ $applicant->village->district->city->name }}, {{ $applicant->village->district->city->province->name }}
                                </p>
                            @endif
                        </div>
                    </div>

                    <!-- Study Program -->
                    <div class="bg-gray-50 p-3 rounded">
                        <h3 class="text-sm font-bold text-gray-800 mb-2 border-b pb-1">PILIHAN PROGRAM STUDI</h3>
                        <div class="space-y-1 text-xs">
                            @if($applicant->firstAdmissionStudyProgram)
                                <p> 1. {{ $applicant->firstAdmissionStudyProgram->studyProgram->name }}</p>
                            @endif
                            @if($applicant->secondAdmissionStudyProgram)
                                <p> 2. {{ $applicant->secondAdmissionStudyProgram->studyProgram->name }}</p>
                            @endif
                            @if($applicant->thirdAdmissionStudyProgram)
                                <p> 3. {{ $applicant->thirdAdmissionStudyProgram->studyProgram->name }}</p>
                            @endif
                        </div>
                    </div>


                </div>
            </div>

            <!-- Card Footer -->
            <div class="bg-blue-50 p-2 text-center border-t rounded-b-lg">
                <div class="flex justify-between items-center">
                    <div class="text-left">
                        <p class="text-xs text-blue-800 font-semibold">{{ $applicant->admissionWave->name }}</p>
                        <p class="text-xs text-gray-600">Dicetak: {{ now()->format('d/m/Y H:i') }} WIB</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-bold text-blue-800">STAIBMW - PENERIMAAN MAHASISWA BARU</p>
                        <p class="text-xs text-gray-600">Tahun Akademik {{ $applicant->admissionWave->academicYear->year }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto print dialog on load
        window.addEventListener('load', function() {
            setTimeout(function() {
                window.print();
            }, 500);
        });
    </script>
</body>
</html>
