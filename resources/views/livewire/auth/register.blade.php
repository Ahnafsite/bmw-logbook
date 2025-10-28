<?php

use App\Models\User;
use App\Models\Position;
use App\Models\Division;
use App\Models\UserPositionAndDivision;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth-large')] class extends Component {
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public ?int $position_id = null;
    public ?int $division_id = null;
    public string $new_position_name = '';
    public string $new_division_name = '';
    public bool $show_new_position = false;
    public bool $show_new_division = false;

    /**
     * Get all positions for the select dropdown
     */
    public function getPositionsProperty()
    {
        return Position::orderBy('name')->get();
    }

    /**
     * Get all divisions for the select dropdown
     */
    public function getDivisionsProperty()
    {
        return Division::orderBy('name')->get();
    }

    /**
     * Toggle new position input
     */
    public function toggleNewPosition()
    {
        $this->show_new_position = !$this->show_new_position;
        if ($this->show_new_position) {
            $this->position_id = null;
        } else {
            $this->new_position_name = '';
        }
    }

    /**
     * Toggle new division input
     */
    public function toggleNewDivision()
    {
        $this->show_new_division = !$this->show_new_division;
        if ($this->show_new_division) {
            $this->division_id = null;
        } else {
            $this->new_division_name = '';
        }
    }

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'position_id' => ['nullable', 'exists:positions,id'],
            'division_id' => ['nullable', 'exists:divisions,id'],
            'new_position_name' => ['nullable', 'string', 'max:255'],
            'new_division_name' => ['nullable', 'string', 'max:255'],
        ]);

        // Validate that either existing position is selected or new position name is provided
        if (!$validated['position_id'] && !$validated['new_position_name']) {
            $this->addError('position_id', 'Please select a position or create a new one.');
            return;
        }

        // Validate that either existing division is selected or new division name is provided
        if (!$validated['division_id'] && !$validated['new_division_name']) {
            $this->addError('division_id', 'Please select a division or create a new one.');
            return;
        }

        // Create new position if needed
        if ($validated['new_position_name']) {
            $position = Position::create([
                'name' => $validated['new_position_name'],
                'desc' => null,
            ]);
            $validated['position_id'] = $position->id;
        }

        // Create new division if needed
        if ($validated['new_division_name']) {
            $division = Division::create([
                'name' => $validated['new_division_name'],
                'desc' => null,
            ]);
            $validated['division_id'] = $division->id;
        }

        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
        ]);

        // Create user position and division relationship
        UserPositionAndDivision::create([
            'user_id' => $user->id,
            'position_id' => $validated['position_id'],
            'division_id' => $validated['division_id'],
        ]);

        event(new Registered($user));

        Auth::login($user);

        Session::regenerate();

        $this->redirectIntended(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-header :title="__('Create an account')" :description="__('Enter your details below to create your account')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form method="POST" wire:submit="register" class="flex flex-col gap-6">
        <!-- Two Column Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- First Column - Basic Info -->
            <div class="space-y-6">
                <!-- Name -->
                <flux:input
                    wire:model="name"
                    :label="__('Name')"
                    type="text"
                    required
                    autofocus
                    autocomplete="name"
                    :placeholder="__('Full name')"
                />

                <!-- Email Address -->
                <flux:input
                    wire:model="email"
                    :label="__('Email address')"
                    type="email"
                    required
                    autocomplete="email"
                    placeholder="email@example.com"
                />

                <!-- Password -->
                <flux:input
                    wire:model="password"
                    :label="__('Password')"
                    type="password"
                    required
                    autocomplete="new-password"
                    :placeholder="__('Password')"
                    viewable
                />
            </div>

            <!-- Second Column - Position & Division -->
            <div class="space-y-6">
                <!-- Position -->
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Jabatan') }}</label>
                        <button type="button" wire:click="toggleNewPosition" class="text-xs text-green-600 hover:text-green-700 px-3 rounded-md transition-colors">
                            {{ $show_new_position ? __('Pilih Tersedia') : __('Buat Baru') }}
                        </button>
                    </div>

                    @if($show_new_position)
                        <flux:input
                            wire:model="new_position_name"
                            type="text"
                            :placeholder="__('masukkan nama jabatan baru')"
                        />
                    @else
                        <flux:select wire:model="position_id" :placeholder="__('Select Position')">
                            <flux:select.option value="">{{ __('Pilih Salah Satu') }}</flux:select.option>
                            @foreach($this->positions as $position)
                                <flux:select.option value="{{ $position->id }}">{{ $position->name }}</flux:select.option>
                            @endforeach
                        </flux:select>
                    @endif
                </div>

                <!-- Division -->
                <div class="space-y-1">
                    <div class="flex items-center justify-between">
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Divisi') }}</label>
                        <button type="button" wire:click="toggleNewDivision" class="text-xs text-green-600 hover:text-green-700 px-3 rounded-md transition-colors">
                            {{ $show_new_division ? __('Pilih Tersedia') : __('Buat Baru') }}
                        </button>
                    </div>

                    @if($show_new_division)
                        <flux:input
                            wire:model="new_division_name"
                            type="text"
                            :placeholder="__('masukkan nama divisi baru')"
                        />
                    @else
                        <flux:select wire:model="division_id" :placeholder="__('Select Division')">
                            <flux:select.option value="">{{ __('Pilih Salah Satu') }}</flux:select.option>
                            @foreach($this->divisions as $division)
                                <flux:select.option value="{{ $division->id }}">{{ $division->name }}</flux:select.option>
                            @endforeach
                        </flux:select>
                    @endif
                </div>
                <!-- Confirm Password -->
                <flux:input
                    wire:model="password_confirmation"
                    :label="__('Confirm password')"
                    type="password"
                    required
                    autocomplete="new-password"
                    :placeholder="__('Confirm password')"
                    viewable
                />
            </div>
        </div>

        <div class="flex items-center justify-end">
            <flux:button type="submit" variant="primary" class="w-full" data-test="register-user-button">
                {{ __('Create account') }}
            </flux:button>
        </div>
    </form>

    <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
        <span>{{ __('Already have an account?') }}</span>
        <flux:link :href="route('login')" wire:navigate>{{ __('Log in') }}</flux:link>
    </div>
</div>
