<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Position;
use App\Models\Division;
use App\Models\UserPositionAndDivision;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

#[Title('Manajemen Pengguna')]
class UserManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;

    // Edit User Modal
    public $showEditModal = false;
    public $editUserId = null;
    public $editName = '';
    public $editEmail = '';
    public $editPassword = '';
    public $selectedUser = null;

    // Role Management Modal
    public $showRoleModal = false;
    public $roleUserId = null;
    public $selectedRoles = [];
    public $availableRoles = [];
    public $roleUser = null;
    public $selectedPosition = null;
    public $selectedDivision = null;
    public $availablePositions = [];
    public $availableDivisions = [];

    // Delete User Modal
    public $showDeleteModal = false;
    public $deleteUserId = null;
    public $deleteUserModel = null;
    public $confirmDeleteName = '';

    protected $queryString = ['search', 'page'];

    public function mount()
    {
        $this->availableRoles = Role::all()->pluck('name')->toArray();
        $this->availablePositions = Position::all();
        $this->availableDivisions = Division::all();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    // Edit User Methods
    public function editUser($userId)
    {
        $this->editUserId = $userId;
        $this->selectedUser = User::find($userId);

        if ($this->selectedUser) {
            $this->editName = $this->selectedUser->name;
            $this->editEmail = $this->selectedUser->email;
            $this->editPassword = '';
            $this->showEditModal = true;
        }
    }

    public function updateUser()
    {
        $this->validate([
            'editName' => 'required|string|max:255',
            'editEmail' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->editUserId)],
            'editPassword' => 'nullable|min:8',
        ]);

        $user = User::find($this->editUserId);

        if ($user) {
            $user->name = $this->editName;
            $user->email = $this->editEmail;

            // Only update password if provided
            if (!empty($this->editPassword)) {
                $user->password = Hash::make($this->editPassword);
            }

            $user->save();

            session()->flash('message', 'Pengguna berhasil diperbarui.');
        }

        $this->closeEditModal();
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->editUserId = null;
        $this->editName = '';
        $this->editEmail = '';
        $this->editPassword = '';
        $this->selectedUser = null;
        $this->resetErrorBag();
    }

    // Role Management Methods
    public function manageRole($userId)
    {
        $this->roleUserId = $userId;
        $this->roleUser = User::with(['roles', 'userPositionAndDivision.position', 'userPositionAndDivision.division'])->find($userId);

        if ($this->roleUser) {
            $this->selectedRoles = $this->roleUser->roles->pluck('name')->toArray();

            // Load current position and division
            if ($this->roleUser->userPositionAndDivision) {
                $this->selectedPosition = $this->roleUser->userPositionAndDivision->position_id;
                $this->selectedDivision = $this->roleUser->userPositionAndDivision->division_id;
            } else {
                $this->selectedPosition = null;
                $this->selectedDivision = null;
            }

            $this->showRoleModal = true;
        }
    }

    public function updateRoles()
    {
        // Add validation for the form
        $this->validate([
            'selectedRoles' => 'array',
            'selectedRoles.*' => 'string|exists:roles,name',
            'selectedPosition' => 'nullable|exists:positions,id',
            'selectedDivision' => 'nullable|exists:divisions,id',
        ]);

        $user = User::find($this->roleUserId);

        if ($user) {
            // Sync roles - this will remove old roles and add new ones
            $user->syncRoles($this->selectedRoles);

            // Update or create position and division
            if ($this->selectedPosition || $this->selectedDivision) {
                UserPositionAndDivision::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'position_id' => $this->selectedPosition ?: null,
                        'division_id' => $this->selectedDivision ?: null,
                    ]
                );
            } else {
                // Remove position and division if both are null
                UserPositionAndDivision::where('user_id', $user->id)->delete();
            }

            session()->flash('message', 'Role, posisi, dan divisi pengguna berhasil diperbarui.');
        }

        $this->closeRoleModal();
    }

    public function closeRoleModal()
    {
        $this->showRoleModal = false;
        $this->roleUserId = null;
        $this->selectedRoles = [];
        $this->roleUser = null;
        $this->selectedPosition = null;
        $this->selectedDivision = null;
    }

    // Delete User Methods
    public function deleteUser($userId)
    {
        $this->deleteUserId = $userId;
        $this->deleteUserModel = User::find($userId);

        if ($this->deleteUserModel) {
            $this->confirmDeleteName = '';
            $this->showDeleteModal = true;
        }
    }

    public function confirmDelete()
    {
        $this->validate([
            'confirmDeleteName' => 'required|string',
        ]);

        if (!$this->deleteUserModel) {
            session()->flash('error', 'Pengguna tidak ditemukan.');
            $this->closeDeleteModal();
            return;
        }

        // Check if the entered name matches the user's name
        if (trim($this->confirmDeleteName) !== trim($this->deleteUserModel->name)) {
            $this->addError('confirmDeleteName', 'Nama yang dimasukkan tidak sesuai dengan nama pengguna.');
            return;
        }

        // Prevent self-deletion
        if ($this->deleteUserModel->id === Auth::id()) {
            session()->flash('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
            $this->closeDeleteModal();
            return;
        }

        try {
            $userName = $this->deleteUserModel->name;
            $this->deleteUserModel->delete();

            session()->flash('message', "Pengguna {$userName} berhasil dihapus.");
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat menghapus pengguna.');
        }

        $this->closeDeleteModal();
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->deleteUserId = null;
        $this->deleteUserModel = null;
        $this->confirmDeleteName = '';
        $this->resetErrorBag();
    }

    public function render()
    {
        $users = User::with(['roles', 'userPositionAndDivision.position', 'userPositionAndDivision.division'])
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhereHas('roles', function ($q) {
                          $q->where('name', 'like', '%' . $this->search . '%');
                      });
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.user-management', [
            'users' => $users,
            'roles' => Role::all()
        ]);
    }
}
