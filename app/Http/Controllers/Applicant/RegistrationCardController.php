<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\Applicant;
use Illuminate\Support\Facades\Auth;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class RegistrationCardController extends Controller
{
    public function show($id)
    {
        // Get applicant data for current user
        $applicant = Applicant::with([
            'admissionWave.academicYear',
            'village.district.city.province',
            'firstAdmissionStudyProgram.studyProgram',
            'secondAdmissionStudyProgram.studyProgram',
            'thirdAdmissionStudyProgram.studyProgram',
        ])
        ->where('id', $id)
        ->where('user_id', Auth::id())
        ->firstOrFail();

        // Generate QR Code
        $qrCodeDataUri = $this->generateQrCode($applicant);

        return view('applicant.registration-card', [
            'applicant' => $applicant,
            'qrCodeDataUri' => $qrCodeDataUri
        ]);
    }

    private function generateQrCode($applicant)
    {
        // Create QR code with URL format: APP_URL/registration_number
        $qrData = env('APP_URL') . '/public/' . ($applicant->registration_number ?: 'PENDING');

        $qrCode = new QrCode($qrData);
        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        return $result->getDataUri();
    }
}
