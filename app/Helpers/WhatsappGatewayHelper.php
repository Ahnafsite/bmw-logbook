<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsappGatewayHelper
{
    /**
     * Send individual WhatsApp message
     *
     * @param string $phoneNumber The recipient's phone number (e.g., "6285877159577")
     * @param string $message The text message to send
     * @return array Response from WhatsApp API
     */
    public static function sendIndividualMessage(string $phoneNumber, string $message): array
    {
        try {
            $url = env('WA_URL');
            $token = env('WA_KEY');

            if (!$url || !$token) {
                throw new \Exception('WhatsApp API URL or Key not configured in environment variables');
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json',
            ])->post($url, [
                'recipient_type' => 'individual',
                'to' => $phoneNumber,
                'type' => 'text',
                'text' => [
                    'body' => $message
                ]
            ]);

            $responseData = $response->json();

            if ($response->successful()) {
                Log::info('WhatsApp message sent successfully', [
                    'phone_number' => $phoneNumber,
                    'message' => $message,
                    'response' => $responseData
                ]);

                return [
                    'success' => true,
                    'data' => $responseData,
                    'status_code' => $response->status()
                ];
            } else {
                Log::error('Failed to send WhatsApp message', [
                    'phone_number' => $phoneNumber,
                    'message' => $message,
                    'status_code' => $response->status(),
                    'response' => $responseData
                ]);

                return [
                    'success' => false,
                    'error' => $responseData,
                    'status_code' => $response->status()
                ];
            }
        } catch (\Exception $e) {
            Log::error('WhatsApp Gateway Helper Exception', [
                'phone_number' => $phoneNumber,
                'message' => $message,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'status_code' => 500
            ];
        }
    }

    /**
     * Translate registration status to Indonesian
     *
     * @param string $status The registration status in English
     * @return string The status in Indonesian
     */
    public static function translateRegistrationStatus(string $status): string
    {
        $translations = [
            'Pending Verification' => '*Menunggu Verifikasi*',
            'Verified' => '*Terverifikasi*',
            'Rejected' => '*Ditolak*',
            'ACCEPTED' => '*Diterima*',
            'PENDING_PAYMENT' => '*Menunggu Pembayaran*',
            'PAID' => '*Sudah Dibayar*',
            'ENROLLED' => '*Terdaftar*',
        ];

        return $translations[$status] ?? '*' . ucfirst(strtolower(str_replace('_', ' ', $status))) . '*';
    }

    /**
     * Send registration notification to applicant
     *
     * @param string $phoneNumber The recipient's phone number
     * @param string $fullName The applicant's full name
     * @param string $registrationStatus The registration status
     * @return array Response from WhatsApp API
     */
    public static function sendRegistrationNotification(string $phoneNumber, string $fullName, string $registrationStatus): array
    {
        $statusInIndonesian = self::translateRegistrationStatus($registrationStatus);

        $message = "Assalamu'alaikum {$fullName},\n\n";
        $message .= "Selamat Anda telah berhasil mendaftar Sebagai Calon Mahasiswa STAI Bina Muwahhidin.\n\n";
        $message .= "Saat ini Status Pendaftaran Anda\n\n";
        $message .= "{$statusInIndonesian}\n\n";
        $message .= "Silahkan cek secara berkala status pendaftaran anda dilaman resmi\n";
        $message .= "pmb.staibinamuwahhidin.ac.id";

        return self::sendIndividualMessage($phoneNumber, $message);
    }
}
