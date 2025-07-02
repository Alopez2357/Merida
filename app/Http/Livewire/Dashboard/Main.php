<?php

namespace App\Http\Livewire\Dashboard;

use Livewire\Component;
use function view;

class Main extends Component
{
    public function mount()
    {
        $user=auth()->user();
        if(!$user->hasAnyRole('SuperAdmin', 'Admin','Cinco Consulting','Altos Funcionarios')){
            return redirect()->route('dashboard.home');
        }
    }

    public function render()
    {
        return view('livewire.dashboard.main')
            ->extends('layouts.admin')
            ->section('content');
    }
}
