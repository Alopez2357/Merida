<?php

namespace App\Http\Livewire\Dashboard\Users;

use Livewire\Component;
use function view;

class Profile extends Component
{
    public function render()
    {
        return view('livewire.dashboard.users.profile')
            ->extends('layouts.admin')
            ->section('content');
    }
}
