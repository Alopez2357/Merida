<?php

namespace App\Http\Livewire\Dashboard\Uploads;

use App\Imports\ArrivesImport;
use App\Imports\OccupationImport;
use App\Imports\OdImport;
use App\Imports\OperationalImport;
use App\Imports\SpendImport;
use App\Imports\StopoverImport;
use App\Models\Load;
use App\Models\Type;
use App\Traits\UploadTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class Tourism extends Component
{
    use WithFileUploads;

    public $type_id=0;
    public $tag='success_arrives';
    public $path;
    public $Arrives;
    public $Spend;
    public $Occupation;
    public $Od;
    public $Operational;
    public $Stopover;
    public $Load;

    use UploadTrait;

    public function updatedArrivesFile()
    {
        $this->Arrives['name'] = $this->Arrives['file']->getClientOriginalName() ;
    }

    public function uploadArrives()
    {
        $this->type_id=Type::where('name','REGEXP','Arribos al Aeropuerto')->firstOrFail()->id;
        $this->validate(['Arrives.file' => 'required|file|mimes:xls,xlsx,csv|max:102400',
                        'Arrives.name' => ['required','regex:/arribos|Arribos|ARRIBOS/',
                                        Rule::unique('loads', 'name')
                                        ->where('type_id', $this->type_id)],
                        'Arrives.source_id'=>'required'
                        ]);

        $this->uploadFile($this->Arrives['file'],$this->Arrives['source_id'],$this->Arrives['name']);

        try {
            Excel::import(new ArrivesImport($this->Load), $this->path);
            $this->tag='success_arrives';
            $this->successPulled($this->Arrives['name']);
        }catch (\Exception $e){
            $this->tag='error_arrives';
            $this->errorPulled($this->Arrives['name']);
            Log::error($e->getMessage());
        }

        $this->reset(['Arrives']);
    }

    public function updatedSpendFile()
    {
        $this->Spend['name'] = $this->Spend['file']->getClientOriginalName() ;
    }

    public function uploadSpend()
    {
        $this->type_id=Type::where('name','REGEXP','Gasto Promedio')->firstOrFail()->id;
        $this->validate(['Spend.file' => 'required|file|mimes:xls,xlsx,csv|max:102400',
                            'Spend.name' => ['required','regex:/gasto|Gasto|GASTO/',
                                Rule::unique('loads', 'name')
                                    ->where('type_id', $this->type_id)],
                            'Spend.source_id'=>'required'
                        ]);

        $this->uploadFile($this->Spend['file'],$this->Spend['source_id'],$this->Spend['name']);

        try {
            Excel::import(new SpendImport($this->Load), $this->path);
            $this->tag='success_spend';
            $this->successPulled($this->Spend['name']);
        }catch (\Exception $e){
            $this->tag='error_spend';
            $this->errorPulled($this->Spend['name']);
            Log::error($e->getMessage());
        }

        $this->reset(['Spend']);
    }

    public function updatedOccupationFile()
    {
        $this->Occupation['name'] = $this->Occupation['file']->getClientOriginalName() ;
    }

    public function uploadOccupation()
    {
        $this->type_id=Type::where('name','REGEXP','Ocupación Hotelera')->firstOrFail()->id;
        $this->validate(['Occupation.file' => 'required|file|mimes:xls,xlsx,csv|max:102400',
            'Occupation.name' => ['required','regex:/OCUPACIÓN HOTELERA|ocupación hotelera|Ocupación Hotelera/',
                Rule::unique('loads', 'name')
                    ->where('type_id', $this->type_id)],
            'Occupation.source_id'=>'required'
        ]);

        $this->uploadFile($this->Occupation['file'],$this->Occupation['source_id'],$this->Occupation['name']);

        try {
            Excel::import(new OccupationImport($this->Load), $this->path);
            $this->tag='success_occupation';
            $this->successPulled($this->Occupation['name']);
        }catch (\Exception $e){
            $this->tag='error_occupation';
            $this->errorPulled($this->Occupation['name']);
            Log::error($e->getMessage());
        }

        $this->reset(['Occupation']);
    }

    public function updatedStopoverFile()
    {
        $this->Stopover['name'] = $this->Stopover['file']->getClientOriginalName() ;
    }

    public function uploadStopover()
    {
        $this->type_id=Type::where('name','REGEXP','Pernocta')->firstOrFail()->id;
        $this->validate(['Stopover.file' => 'required|file|mimes:xls,xlsx,csv|max:102400',
                        'Stopover.name' => ['required','regex:/Pernocta|pernocta|PERNOCTA/',
                            Rule::unique('loads', 'name')
                                ->where('type_id',$this->type_id)],//Pernocta
                        'Stopover.source_id'=>'required'
                        ]);

        $this->uploadFile($this->Stopover['file'],$this->Stopover['source_id'],$this->Stopover['name']);

        try {
            Excel::import(new StopoverImport($this->Load), $this->path);
            $this->tag='success_stopover';
            $this->successPulled($this->Stopover['name']);
        }catch (\Exception $e){
            $this->tag='error_stopover';
            $this->errorPulled($this->Stopover['name']);
            Log::error($e->getMessage());
        }

        $this->reset(['Stopover']);
    }

    public function updatedOdFile()
    {
        $this->Od['name'] = $this->Od['file']->getClientOriginalName() ;
    }

    public function uploadOd()
    {
        $this->type_id=Type::where('name','REGEXP','Origen Destino')->firstOrFail()->id;;
        $this->validate(['Od.file' => 'required|file|mimes:xls,xlsx,csv|max:102400',
                        'Od.name' => ['required','regex:/origen|destino|Origen|Destino|ORIGEN|DESTINO/',
                            Rule::unique('loads', 'name')
                                ->where('type_id',$this->type_id)],//Origen Destino
                        'Od.source_id'=>'required'
                    ]);

        $this->uploadFile($this->Od['file'],$this->Od['source_id'],$this->Od['name']);

        try {
            Excel::import(new OdImport($this->Load), $this->path);
            $this->tag='success_od';
            $this->successPulled($this->Od['name']);
        }catch (\Exception $e){
            $this->tag='error_od';
            $this->errorPulled($this->Od['name']);
            Log::error($e->getMessage());
        }
        $this->reset(['Od']);
    }

    public function updatedOperationalFile()
    {
        $this->Operational['name'] = $this->Operational['file']->getClientOriginalName() ;
    }

    public function uploadOperational()
    {
        $this->type_id=Type::where('name','REGEXP','Operaciones Aeropuerto')->firstOrFail()->id;
        $this->validate(['Operational.file' => 'required|file|mimes:xls,xlsx,csv|max:102400',
                            'Operational.name' => ['required','regex:/movimiento|operacional|Movimiento|Operacional|MOVIMIENTO|OPERACIONAL/',
                            Rule::unique('loads', 'name')
                                ->where('type_id',$this->type_id)],// Movimiento Operacional
                            'Operational.source_id'=>'required'
        ]);

        $this->uploadFile($this->Operational['file'],$this->Operational['source_id'],$this->Operational['name']);

        try {
            Excel::import(new OperationalImport($this->Load), $this->path);
            $this->tag='success_operational';
            $this->successPulled($this->Operational['name']);
        }catch (\Exception $e){
            $this->tag='error_operational';
            $this->errorPulled($this->Operational['name']);
            Log::error($e->getMessage());
        }
        $this->reset(['Operational']);
    }

    public function render()
    {
        return view('livewire.dashboard.upload.tourism')
            ->extends('layouts.admin')
            ->section('content');
    }
}
