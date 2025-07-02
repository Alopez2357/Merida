<?php

namespace App\Http\Livewire\Dashboard\Uploads\Maps;

use App\Imports\AirdnaImport;
use App\Models\Load;
use App\Models\Type;
use App\Traits\WithSorting;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;
use function auth;
use function session;
use function view;

class Airdna extends Component
{
    use WithFileUploads, WithSorting, WithPagination;

    public $airdna;
    public $source_id=null;
    public $file;
    public $path;
    public $name;
    public $Load;
    public $key;
    public $type_id;

    public $search = '';
    public $perPage = '10';

    public $columns=[
        'id',
        'name'
    ];

    protected $queryString = ['search' => ['except' => ''],
        'perPage' => ['except' => '10']
    ];

    public function  mount()
    {
        $this->key='airdna';
        $this->type_id=Type::where('name','REGEXP',"{$this->key}")->firstOrFail()->id;
    }


    public function updatedAirdna()
    {
        $this->name = $this->airdna->getClientOriginalName();
    }

    public function render()
    {
        return view('livewire.dashboard.uploads.maps.airdna')
            ->extends('layouts.admin')
            ->section('content');
    }
}
