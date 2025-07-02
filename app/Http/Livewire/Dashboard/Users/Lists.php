<?php

namespace App\Http\Livewire\Dashboard\Users;

use App\Models\User;
use Livewire\Component;

class Lists extends Component
{

    public function render()
    {
        $users=User::orderBy('id', 'DESC')->paginate(15);
        return view('livewire.dashboard.users.lists',['users'=>$users])
            ->extends('layouts.admin')
            ->section('content');
    }
}
