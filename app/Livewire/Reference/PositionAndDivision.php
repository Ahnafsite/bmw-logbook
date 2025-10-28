<?php

namespace App\Livewire\Reference;

use App\Models\Position;
use App\Models\Division;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;

#[Title('Divisi and Jabatan')]
class PositionAndDivision extends Component
{
    use WithPagination;

    // Common properties
    public $activeTab = 'positions'; // 'positions' or 'divisions'

    // Position properties
    public $positionSearch = '';
    public $positionPerPage = 10;

    // Position Create Modal
    public $showPositionCreateModal = false;
    public $positionName = '';
    public $positionDesc = '';

    // Position Edit Modal
    public $showPositionEditModal = false;
    public $editPositionId = null;
    public $editPositionName = '';
    public $editPositionDesc = '';
    public $selectedPosition = null;

    // Position Delete Modal
    public $showPositionDeleteModal = false;
    public $deletePositionId = null;
    public $deletePositionModel = null;

    // Division properties
    public $divisionSearch = '';
    public $divisionPerPage = 10;

    // Division Create Modal
    public $showDivisionCreateModal = false;
    public $divisionName = '';
    public $divisionDesc = '';

    // Division Edit Modal
    public $showDivisionEditModal = false;
    public $editDivisionId = null;
    public $editDivisionName = '';
    public $editDivisionDesc = '';
    public $selectedDivision = null;

    // Division Delete Modal
    public $showDivisionDeleteModal = false;
    public $deleteDivisionId = null;
    public $deleteDivisionModel = null;

    protected $queryString = ['positionSearch', 'divisionSearch', 'activeTab'];

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    // Position methods
    public function updatingPositionSearch()
    {
        $this->resetPage();
    }

    public function updatingPositionPerPage()
    {
        $this->resetPage();
    }

    public function openPositionCreateModal()
    {
        $this->resetPositionCreateForm();
        $this->showPositionCreateModal = true;
    }

    public function createPosition()
    {
        $this->validate([
            'positionName' => 'required|string|max:255|unique:positions,name',
            'positionDesc' => 'nullable|string',
        ], [
            'positionName.required' => 'Nama jabatan wajib diisi.',
            'positionName.unique' => 'Nama jabatan sudah ada.',
        ]);

        Position::create([
            'name' => $this->positionName,
            'desc' => $this->positionDesc,
        ]);

        session()->flash('message', 'Jabatan berhasil ditambahkan.');
        $this->closePositionCreateModal();
    }

    public function closePositionCreateModal()
    {
        $this->showPositionCreateModal = false;
        $this->resetPositionCreateForm();
        $this->resetErrorBag();
    }

    private function resetPositionCreateForm()
    {
        $this->positionName = '';
        $this->positionDesc = '';
    }

    public function editPosition($positionId)
    {
        $this->editPositionId = $positionId;
        $this->selectedPosition = Position::find($positionId);

        if ($this->selectedPosition) {
            $this->editPositionName = $this->selectedPosition->name;
            $this->editPositionDesc = $this->selectedPosition->desc;
            $this->showPositionEditModal = true;
        }
    }

    public function updatePosition()
    {
        $this->validate([
            'editPositionName' => 'required|string|max:255|unique:positions,name,' . $this->editPositionId,
            'editPositionDesc' => 'nullable|string',
        ], [
            'editPositionName.required' => 'Nama jabatan wajib diisi.',
            'editPositionName.unique' => 'Nama jabatan sudah ada.',
        ]);

        $position = Position::find($this->editPositionId);

        if ($position) {
            $position->update([
                'name' => $this->editPositionName,
                'desc' => $this->editPositionDesc,
            ]);

            session()->flash('message', 'Jabatan berhasil diperbarui.');
        }

        $this->closePositionEditModal();
    }

    public function closePositionEditModal()
    {
        $this->showPositionEditModal = false;
        $this->editPositionId = null;
        $this->editPositionName = '';
        $this->editPositionDesc = '';
        $this->selectedPosition = null;
        $this->resetErrorBag();
    }

    public function deletePosition($positionId)
    {
        $this->deletePositionId = $positionId;
        $this->deletePositionModel = Position::find($positionId);

        if ($this->deletePositionModel) {
            $this->showPositionDeleteModal = true;
        }
    }

    public function confirmPositionDelete()
    {
        if (!$this->deletePositionModel) {
            session()->flash('error', 'Jabatan tidak ditemukan.');
            $this->closePositionDeleteModal();
            return;
        }

        try {
            $this->deletePositionModel->delete();
            session()->flash('message', 'Jabatan berhasil dihapus.');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat menghapus jabatan.');
        }

        $this->closePositionDeleteModal();
    }

    public function closePositionDeleteModal()
    {
        $this->showPositionDeleteModal = false;
        $this->deletePositionId = null;
        $this->deletePositionModel = null;
    }

    // Division methods
    public function updatingDivisionSearch()
    {
        $this->resetPage();
    }

    public function updatingDivisionPerPage()
    {
        $this->resetPage();
    }

    public function openDivisionCreateModal()
    {
        $this->resetDivisionCreateForm();
        $this->showDivisionCreateModal = true;
    }

    public function createDivision()
    {
        $this->validate([
            'divisionName' => 'required|string|max:255|unique:divisions,name',
            'divisionDesc' => 'nullable|string',
        ], [
            'divisionName.required' => 'Nama divisi wajib diisi.',
            'divisionName.unique' => 'Nama divisi sudah ada.',
        ]);

        Division::create([
            'name' => $this->divisionName,
            'desc' => $this->divisionDesc,
        ]);

        session()->flash('message', 'Divisi berhasil ditambahkan.');
        $this->closeDivisionCreateModal();
    }

    public function closeDivisionCreateModal()
    {
        $this->showDivisionCreateModal = false;
        $this->resetDivisionCreateForm();
        $this->resetErrorBag();
    }

    private function resetDivisionCreateForm()
    {
        $this->divisionName = '';
        $this->divisionDesc = '';
    }

    public function editDivision($divisionId)
    {
        $this->editDivisionId = $divisionId;
        $this->selectedDivision = Division::find($divisionId);

        if ($this->selectedDivision) {
            $this->editDivisionName = $this->selectedDivision->name;
            $this->editDivisionDesc = $this->selectedDivision->desc;
            $this->showDivisionEditModal = true;
        }
    }

    public function updateDivision()
    {
        $this->validate([
            'editDivisionName' => 'required|string|max:255|unique:divisions,name,' . $this->editDivisionId,
            'editDivisionDesc' => 'nullable|string',
        ], [
            'editDivisionName.required' => 'Nama divisi wajib diisi.',
            'editDivisionName.unique' => 'Nama divisi sudah ada.',
        ]);

        $division = Division::find($this->editDivisionId);

        if ($division) {
            $division->update([
                'name' => $this->editDivisionName,
                'desc' => $this->editDivisionDesc,
            ]);

            session()->flash('message', 'Divisi berhasil diperbarui.');
        }

        $this->closeDivisionEditModal();
    }

    public function closeDivisionEditModal()
    {
        $this->showDivisionEditModal = false;
        $this->editDivisionId = null;
        $this->editDivisionName = '';
        $this->editDivisionDesc = '';
        $this->selectedDivision = null;
        $this->resetErrorBag();
    }

    public function deleteDivision($divisionId)
    {
        $this->deleteDivisionId = $divisionId;
        $this->deleteDivisionModel = Division::find($divisionId);

        if ($this->deleteDivisionModel) {
            $this->showDivisionDeleteModal = true;
        }
    }

    public function confirmDivisionDelete()
    {
        if (!$this->deleteDivisionModel) {
            session()->flash('error', 'Divisi tidak ditemukan.');
            $this->closeDivisionDeleteModal();
            return;
        }

        try {
            $this->deleteDivisionModel->delete();
            session()->flash('message', 'Divisi berhasil dihapus.');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat menghapus divisi.');
        }

        $this->closeDivisionDeleteModal();
    }

    public function closeDivisionDeleteModal()
    {
        $this->showDivisionDeleteModal = false;
        $this->deleteDivisionId = null;
        $this->deleteDivisionModel = null;
    }

    public function render()
    {
        $positions = Position::when($this->positionSearch, function ($query) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->positionSearch . '%')
                  ->orWhere('desc', 'like', '%' . $this->positionSearch . '%');
            });
        })
        ->orderBy('name')
        ->paginate($this->positionPerPage, ['*'], 'positionsPage');

        $divisions = Division::when($this->divisionSearch, function ($query) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->divisionSearch . '%')
                  ->orWhere('desc', 'like', '%' . $this->divisionSearch . '%');
            });
        })
        ->orderBy('name')
        ->paginate($this->divisionPerPage, ['*'], 'divisionsPage');

        return view('livewire.reference.position-and-division', [
            'positions' => $positions,
            'divisions' => $divisions
        ]);
    }
}
