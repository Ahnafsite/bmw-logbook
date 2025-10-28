<?php

namespace App\Livewire\Admin;

use App\Models\LogBook;
use App\Models\User;
use App\Exports\LogBookExport;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

#[Title('Monitoring Logbook')]
class Monitoring extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $dateFrom = '';
    public $dateTo = '';
    public $selectedUserId = '';

    // Export Modal
    public $showExportModal = false;
    public $exportDateFrom = '';
    public $exportDateTo = '';
    public $exportUserId = '';

    protected $queryString = ['search', 'page', 'dateFrom', 'dateTo', 'selectedUserId'];

    public function mount()
    {
        // Set default filter to today
        $this->dateFrom = now()->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function updatingDateFrom()
    {
        $this->resetPage();
    }

    public function updatingDateTo()
    {
        $this->resetPage();
    }

    public function updatingSelectedUserId()
    {
        $this->resetPage();
    }

    public function resetDateFilter()
    {
        $this->dateFrom = now()->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
        $this->resetPage();
    }

    public function resetUserFilter()
    {
        $this->selectedUserId = '';
        $this->resetPage();
    }

    public function resetAllFilters()
    {
        $this->search = '';
        $this->dateFrom = now()->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
        $this->selectedUserId = '';
        $this->resetPage();
    }

    // Export Methods
    public function openExportModal()
    {
        $this->resetExportForm();
        $this->exportDateFrom = now()->format('Y-m-d');
        $this->exportDateTo = now()->format('Y-m-d');
        $this->showExportModal = true;
    }

    public function closeExportModal()
    {
        $this->showExportModal = false;
        $this->resetExportForm();
    }

    private function resetExportForm()
    {
        $this->exportDateFrom = '';
        $this->exportDateTo = '';
        $this->exportUserId = '';
    }

    public function exportLogbook()
    {
        $this->validate([
            'exportDateFrom' => 'required|date',
            'exportDateTo' => 'required|date|after_or_equal:exportDateFrom',
            'exportUserId' => 'nullable|exists:users,id',
        ], [
            'exportDateTo.after_or_equal' => 'Tanggal akhir harus sama atau setelah tanggal mulai.',
            'exportUserId.exists' => 'Staff yang dipilih tidak valid.',
        ]);

        try {
            $fileName = 'logbook_' . now()->format('Y-m-d_H-i-s') . '.xlsx';

            // Store export parameters in session for LogBookExport to access
            session(['export_filters' => [
                'dateFrom' => $this->exportDateFrom,
                'dateTo' => $this->exportDateTo,
                'userId' => $this->exportUserId ?: null,
            ]]);

            session()->flash('message', 'Export logbook berhasil! File sedang diunduh...');
            $this->closeExportModal();

            return Excel::download(
                new LogBookExport(
                    $this->exportDateFrom,
                    $this->exportDateTo,
                    $this->exportUserId ?: null
                ),
                $fileName
            );

        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat mengekspor data: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $logbooks = LogBook::with('user')
            ->when($this->dateFrom && $this->dateTo, function ($query) {
                $query->whereBetween('date', [$this->dateFrom, $this->dateTo]);
            })
            ->when($this->selectedUserId, function ($query) {
                $query->where('user_id', $this->selectedUserId);
            })
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('job', 'like', '%' . $this->search . '%')
                      ->orWhere('desc', 'like', '%' . $this->search . '%')
                      ->orWhereHas('user', function ($userQuery) {
                          $userQuery->where('name', 'like', '%' . $this->search . '%');
                      });
                });
            })
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'desc')
            ->paginate($this->perPage);

        $users = User::orderBy('name')->get();

        return view('livewire.admin.monitoring', [
            'logbooks' => $logbooks,
            'users' => $users
        ]);
    }
}
