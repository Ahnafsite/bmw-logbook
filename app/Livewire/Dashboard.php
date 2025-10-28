<?php

namespace App\Livewire;

use App\Models\LogBook as ModelLogBook;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

#[Title('Dashboard')]
class Dashboard extends Component
{
    public function mount()
    {
        // Component initialization
    }

    public function getTotalLogbooksToday()
    {
        return ModelLogBook::where('date', today()->format('Y-m-d'))->count();
    }

    public function getTotalLogbooksThisWeek()
    {
        return ModelLogBook::whereBetween('date', [
            Carbon::now()->startOfWeek()->format('Y-m-d'),
            Carbon::now()->endOfWeek()->format('Y-m-d')
        ])->count();
    }

    public function getTotalLogbooksThisMonth()
    {
        return ModelLogBook::whereYear('date', Carbon::now()->year)
            ->whereMonth('date', Carbon::now()->month)
            ->count();
    }

    public function getTodaysLogbooks()
    {
        return ModelLogBook::with('user')
            ->where('date', today()->format('Y-m-d'))
            ->orderBy('created_at', 'desc')
            ->orderBy('start_time', 'desc')
            ->take(10)
            ->get();
    }

    public function render()
    {
        return view('livewire.dashboard', [
            'totalLogbooksToday' => $this->getTotalLogbooksToday(),
            'totalLogbooksThisWeek' => $this->getTotalLogbooksThisWeek(),
            'totalLogbooksThisMonth' => $this->getTotalLogbooksThisMonth(),
            'todaysLogbooks' => $this->getTodaysLogbooks(),
        ]);
    }
}
