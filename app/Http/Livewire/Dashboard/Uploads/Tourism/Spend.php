<?php

namespace App\Http\Livewire\Dashboard\Uploads\Tourism;

use App\Imports\SpendImport;
use App\Models\Type;
use App\Traits\UploadTrait;
use App\Traits\WithSorting;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Spend extends Component
{
    use  WithSorting, WithPagination;

    public $key;
    public $type_id;
    public $regexp;

    use UploadTrait;

    protected $listeners = ['importFile'];

    public function  mount()
    {
        $this->key='Gasto Promedio';
        $this->regexp='regex:/gasto|Gasto|GASTO/';
        $this->type_id=Type::where('name','REGEXP',"{$this->key}")->firstOrFail()->id;
    }

    public function importFile($Load,$path)
    {
        try {
            Excel::import(new SpendImport($Load), $path);
            $this->emit('refreshHistory');
        }catch (\Exception $e){
            Log::error($e->getMessage());
        }

    }

    public function render()
    {
        return view('livewire.dashboard.uploads.tourism.spend')
            ->extends('layouts.admin')
            ->section('content');
    }
}
