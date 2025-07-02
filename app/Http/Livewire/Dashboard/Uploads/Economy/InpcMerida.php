<?php

namespace App\Http\Livewire\Dashboard\Uploads\Economy;

use App\Imports\InpcmeridaImport;
use App\Models\Type;
use App\Traits\UploadTrait;
use App\Traits\WithSorting;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class InpcMerida extends Component
{
    use  WithSorting, WithPagination;

    public $key;
    public $type_id;
    public $regexp;

    use UploadTrait;

    protected $listeners = ['importFile'];

    public function mount()
    {
        $this->key='INPC Mérida';
        $this->regexp='regex:/INPC MERIDA|inpc merida|Inpc Merida/';
        $this->type_id=Type::where('name','REGEXP',"{$this->key}")->firstOrFail()->id;
    }

    public function importFile($Load,$path)
    {
      try
      {
        Excel::import(new InpcmeridaImport($Load), $path);
        $this->emit('refreshHistory');
      }
      catch (\Exception $ex)
      {
        Log::error($ex->getMessage());
      }
    }

    public function render()
    {
        return view('livewire.dashboard.uploads.economy.inpc-merida')
            ->extends('layouts.admin')
            ->section('content');
    }
}
