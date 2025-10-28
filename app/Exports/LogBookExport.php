<?php

namespace App\Exports;

use App\Models\LogBook;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;

class LogBookExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithTitle, WithCustomStartCell, WithEvents
{
    public $dateFrom;
    public $dateTo;
    public $selectedUserId;
    protected $rowNumber = 0;

    public function __construct($dateFrom, $dateTo, $selectedUserId = null)
    {
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
        $this->selectedUserId = $selectedUserId;
    }

    public function title(): string
    {
        return 'Logbook Staff';
    }

    public function startCell(): string
    {
        return 'A6'; // Start data from row 6 to leave space for headers
    }

    public function collection()
    {
        $query = LogBook::with('user');

        // Get filters from constructor or session fallback
        $filters = session('export_filters', []);
        $dateFrom = $this->dateFrom ?: ($filters['dateFrom'] ?? null);
        $dateTo = $this->dateTo ?: ($filters['dateTo'] ?? null);
        $userId = $this->selectedUserId ?: ($filters['userId'] ?? null);

        // Apply date filter if both dates are provided
        if (!empty($dateFrom) && !empty($dateTo)) {
            $query->where('date', '>=', $dateFrom)
                  ->where('date', '<=', $dateTo);
        }

        // Apply user filter if specified
        if (!empty($userId)) {
            $query->where('user_id', $userId);
        }

        return $query->orderBy('date', 'desc')
                     ->orderBy('start_time', 'desc')
                     ->get();
    }

    public function headings(): array
    {
        return [
            'NO',
            'NAMA STAFF',
            'TANGGAL',
            'WAKTU',
            'PEKERJAAN',
            'DESKRIPSI'
        ];
    }

    public function map($logbook): array
    {
        $this->rowNumber++;
        return [
            $this->rowNumber,
            $logbook->user->name,
            $logbook->date->format('d/m/Y'),
            $logbook->start_time->format('H:i') . ' - ' . $logbook->end_time->format('H:i'),
            $logbook->job,
            $logbook->desc ?: '-'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the header row (row 6) as bold text
            6 => ['font' => ['bold' => true]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Add main header
                $sheet->setCellValue('A1', 'REKAPITULASI LOGBOOK HARIAN STAFF');
                $sheet->setCellValue('A2', 'STAI BINA MUWAHHIDIN');

                // Get filters from constructor or session fallback
                $filters = session('export_filters', []);
                $dateFrom = $this->dateFrom ?: ($filters['dateFrom'] ?? null);
                $dateTo = $this->dateTo ?: ($filters['dateTo'] ?? null);

                // Format date range
                $dateFromFormatted = $dateFrom ? Carbon::parse($dateFrom)->format('d/m/Y') : 'N/A';
                $dateToFormatted = $dateTo ? Carbon::parse($dateTo)->format('d/m/Y') : 'N/A';
                $dateRange = ($dateFromFormatted === $dateToFormatted && $dateFromFormatted !== 'N/A')
                    ? $dateFromFormatted
                    : $dateFromFormatted . ' - ' . $dateToFormatted;

                $sheet->setCellValue('A4', 'Tanggal : ' . $dateRange);

                // Style main headers
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);
                $sheet->getStyle('A4')->getFont()->setBold(true);

                // Center align headers
                $sheet->getStyle('A1:F1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A2:F2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Merge cells for headers
                $sheet->mergeCells('A1:F1');
                $sheet->mergeCells('A2:F2');

                // Get the last row with data
                $lastRow = $sheet->getHighestDataRow();

                // Add borders to data table
                $sheet->getStyle('A6:F' . $lastRow)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

                // Style header row of table
                $sheet->getStyle('A6:F6')->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('E6E6E6');

                // Center align table headers
                $sheet->getStyle('A6:F6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Center align NO column
                $sheet->getStyle('A7:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}
