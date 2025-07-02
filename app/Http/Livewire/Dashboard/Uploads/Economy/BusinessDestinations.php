<?php

namespace App\Http\Livewire\Dashboard\Uploads\Economy;

use App\Imports\BusinessDestinationsImport;
use App\Models\Type;
use App\Traits\UploadTrait;
use App\Traits\WithSorting;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use function view;

class BusinessDestinations extends Component
{
    use  WithSorting, WithPagination;

    public $key;
    public $regexp;
    public $type_id;

    use UploadTrait;

    protected $listeners = ['importFile'];

    public function  mount()
    {
        $this->key='Destinos comerciales';
        $this->regexp='regex:/Destinos|Comerciales|destinos|comerciales|DESTINOS|COMERCIALES/';
        $this->type_id=Type::where('name','REGEXP',"{$this->key}")->firstOrFail()->id;
    }

    public function importFile($Load,$path)
    {
        try {
            Excel::import(new BusinessDestinationsImport($Load), $path);
            $this->emit('refreshHistory');
        }catch (\Exception $e){
            Log::error($e->getMessage());
        }

    }

    public function render()
    {
        return view('livewire.dashboard.uploads.economy.business-destinations')
                                                        ->extends('layouts.admin')
                                                        ->section('content');
    }
}
