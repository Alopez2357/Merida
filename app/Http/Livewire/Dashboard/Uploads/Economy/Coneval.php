<?php

namespace App\Http\Livewire\Dashboard\Uploads\Economy;

use App\Imports\ConevalImport;
use App\Models\Type;
use App\Traits\UploadTrait;
use App\Traits\WithSorting;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Coneval extends Component
{
    use  WithSorting, WithPagination;

    public $key;
    public $type_id;
    public $regexp;

    use UploadTrait;

    protected $listeners = ['importFile'];

    public function  mount()
    {
        $this->key='Coneval';
        $this->regexp='regex:/Coneval|CONEVAL|coneval/';
        $this->type_id=Type::where('name','REGEXP',"{$this->key}")->firstOrFail()->id;
    }

    public function importFile($Load,$path)
    {
        try {
            Excel::import(new ConevalImport($Load), $path);
            $this->emit('refreshHistory');
        }catch (\Exception $e){
            Log::error($e->getMessage());
        }

    }

    public function render()
    {
        return view('livewire.dashboard.uploads.economy.coneval')
            ->extends('layouts.admin')
            ->section('content');
    }
}
