<?php

namespace App\Livewire\Staff;

use App\Models\LogBook as ModelLogBook;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

#[Title('Logbook')]
class Logbook extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $dateFrom = '';
    public $dateTo = '';

    // Create Logbook Modal
    public $showCreateModal = false;
    public $date = '';
    public $start_time = '';
    public $end_time = '';
    public $job = '';
    public $desc = '';

    // Edit Logbook Modal
    public $showEditModal = false;
    public $editLogbookId = null;
    public $editDate = '';
    public $editStartTime = '';
    public $editEndTime = '';
    public $editJob = '';
    public $editDesc = '';
    public $selectedLogbook = null;

    // Delete Logbook Modal
    public $showDeleteModal = false;
    public $deleteLogbookId = null;
    public $deleteLogbookModel = null;

    protected $queryString = ['search', 'page', 'dateFrom', 'dateTo'];

    public function mount()
    {
        $this->date = now()->format('Y-m-d');
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

    public function resetDateFilter()
    {
        $this->dateFrom = now()->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
        $this->resetPage();
    }

    public function canEditDelete($logbookDate)
    {
        return $logbookDate === now()->format('Y-m-d');
    }

    // Create Logbook Methods
    public function openCreateModal()
    {
        $this->resetCreateForm();
        $this->date = now()->format('Y-m-d');
        $this->showCreateModal = true;
    }

    public function createLogbook()
    {
        $this->validate([
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'job' => 'required|string|max:255',
            'desc' => 'nullable|string',
        ], [
            'end_time.after' => 'Waktu selesai harus setelah waktu mulai.',
        ]);

        ModelLogBook::create([
            'user_id' => Auth::id(),
            'date' => $this->date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'job' => $this->job,
            'desc' => $this->desc,
        ]);

        session()->flash('message', 'Logbook berhasil ditambahkan.');
        $this->closeCreateModal();
    }

    public function closeCreateModal()
    {
        $this->showCreateModal = false;
        $this->resetCreateForm();
        $this->resetErrorBag();
    }

    private function resetCreateForm()
    {
        $this->date = '';
        $this->start_time = '';
        $this->end_time = '';
        $this->job = '';
        $this->desc = '';
    }

    // Edit Logbook Methods
    public function editLogbook($logbookId)
    {
        $this->editLogbookId = $logbookId;
        $this->selectedLogbook = ModelLogBook::find($logbookId);

        if ($this->selectedLogbook && $this->selectedLogbook->user_id === Auth::id()) {
            // Only allow editing today's entries
            if ($this->selectedLogbook->date->format('Y-m-d') !== now()->format('Y-m-d')) {
                session()->flash('error', 'Hanya bisa mengedit logbook hari ini.');
                return;
            }

            $this->editDate = $this->selectedLogbook->date->format('Y-m-d');
            $this->editStartTime = Carbon::parse($this->selectedLogbook->start_time)->format('H:i');
            $this->editEndTime = Carbon::parse($this->selectedLogbook->end_time)->format('H:i');
            $this->editJob = $this->selectedLogbook->job;
            $this->editDesc = $this->selectedLogbook->desc;
            $this->showEditModal = true;
        }
    }

    public function updateLogbook()
    {
        $this->validate([
            'editDate' => 'required|date',
            'editStartTime' => 'required',
            'editEndTime' => 'required|after:editStartTime',
            'editJob' => 'required|string|max:255',
            'editDesc' => 'nullable|string',
        ], [
            'editEndTime.after' => 'Waktu selesai harus setelah waktu mulai.',
        ]);

        $logbook = ModelLogBook::find($this->editLogbookId);

        if ($logbook && $logbook->user_id === Auth::id()) {
            $logbook->update([
                'date' => $this->editDate,
                'start_time' => $this->editStartTime,
                'end_time' => $this->editEndTime,
                'job' => $this->editJob,
                'desc' => $this->editDesc,
            ]);

            session()->flash('message', 'Logbook berhasil diperbarui.');
        }

        $this->closeEditModal();
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->editLogbookId = null;
        $this->editDate = '';
        $this->editStartTime = '';
        $this->editEndTime = '';
        $this->editJob = '';
        $this->editDesc = '';
        $this->selectedLogbook = null;
        $this->resetErrorBag();
    }

    // Delete Logbook Methods
    public function deleteLogbook($logbookId)
    {
        $this->deleteLogbookId = $logbookId;
        $this->deleteLogbookModel = ModelLogBook::find($logbookId);

        if ($this->deleteLogbookModel && $this->deleteLogbookModel->user_id === Auth::id()) {
            // Only allow deleting today's entries
            if ($this->deleteLogbookModel->date !== now()->format('Y-m-d')) {
                session()->flash('error', 'Hanya bisa menghapus logbook hari ini.');
                return;
            }
            $this->showDeleteModal = true;
        }
    }

    public function confirmDelete()
    {
        if (!$this->deleteLogbookModel || $this->deleteLogbookModel->user_id !== Auth::id()) {
            session()->flash('error', 'Logbook tidak ditemukan.');
            $this->closeDeleteModal();
            return;
        }

        try {
            $this->deleteLogbookModel->delete();
            session()->flash('message', 'Logbook berhasil dihapus.');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat menghapus logbook.');
        }

        $this->closeDeleteModal();
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->deleteLogbookId = null;
        $this->deleteLogbookModel = null;
    }

    public function render()
    {
        $logbooks = ModelLogBook::where('user_id', Auth::id())
            ->when($this->dateFrom && $this->dateTo, function ($query) {
                $query->whereBetween('date', [$this->dateFrom, $this->dateTo]);
            })
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('job', 'like', '%' . $this->search . '%')
                      ->orWhere('desc', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'desc')
            ->paginate($this->perPage);

        return view('livewire.staff.logbook', [
            'logbooks' => $logbooks
        ]);
    }
}
