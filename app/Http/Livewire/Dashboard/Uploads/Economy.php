<?php

namespace App\Http\Livewire\Dashboard\Uploads;

use App\Imports\ConevalImport;
use App\Imports\EmpleoImport;
use App\Imports\InflationImport;
use App\Imports\InpcmeridaImport;
use App\Imports\InpcnacionalImport;
use App\Models\Load;
use App\Models\Type;
use App\Traits\UploadTrait;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class Economy extends Component
{
    use WithFileUploads;

    public int $type_id=0;
    public string $tag='success_employment';
    public string $path;
    public $Employment;
    public $InpcMerida;
    public $InpcNacional;
    public $Inflation;
    public $Coneval;
    public $Load;

    use UploadTrait;


    public function updatedEmploymentFile()
    {
        $this->Employment['name'] = $this->Employment['file']->getClientOriginalName() ;
    }

    public function uploadEmployment()
    {
        $this->type_id=Type::where('name','regexp','empleo')->firstOrFail()->id;
        $this->validate(['Employment.file' => 'required|file|mimes:xls,xlsx,csv|max:102400',
                        'Employment.name' => ['required','regex:/Trimestre|Empleo|trimestre|empleo|TRIMESTRE|EMPLEO/',
                            Rule::unique('loads', 'name')
                                ->where('type_id', $this->type_id)],
                        'Employment.source_id'=>'required'
        ]);

        $this->uploadFile($this->Employment['file'],$this->Employment['source_id'],$this->Employment['name']);

        try {
            Excel::import(new EmpleoImport($this->Load), $this->path);
            $this->tag='success_employment';
            $this->successPulled($this->Employment['name']);
        }catch (\Exception $e){
            $this->tag='error_employment';
            $this->errorPulled($this->Employment['name']);
            Log::error($e->getMessage());
        }

        $this->reset(['Employment']);
    }

    public function updatedInpcNacionalFile()
    {
        $this->InpcNacional['name'] = $this->InpcNacional['file']->getClientOriginalName() ;
    }

    public function uploadInpcNacional()
    {
        $this->type_id=Type::where('name','regexp','INPC Nacional')->firstOrFail()->id;
        $this->validate(['InpcNacional.file' => 'required|file|mimes:xls,xlsx,csv|max:102400',
                        'InpcNacional.name' => ['required','regex:/INPC NACIONAL|inpc nacional|Inpc Nacional/',
                            Rule::unique('loads', 'name')
                                ->where('type_id', $this->type_id)],
                        'InpcNacional.source_id'=>'required'
                    ]);

        $this->uploadFile($this->InpcNacional['file'],$this->InpcNacional['source_id'],$this->InpcNacional['name']);

        try {
            Excel::import(new InpcnacionalImport($this->Load), $this->path);
            $this->tag='success_inpcnacional';
            $this->successPulled($this->InpcNacional['name']);
        }catch (\Exception $e){
            $this->tag='error_inpcnacional';
            $this->errorPulled($this->InpcNacional['name']);
            Log::error($e->getMessage());
        }

        $this->reset(['InpcNacional']);
    }

    public function updatedInpcMeridaFile()
    {
        $this->InpcMerida['name'] = $this->InpcMerida['file']->getClientOriginalName() ;
    }

    public function uploadInpcMerida()
    {
        $this->type_id=Type::where('name','regexp','INPC Mérida')->firstOrFail()->id;
        $this->validate(['InpcMerida.file' => 'required|file|mimes:xls,xlsx,csv|max:102400',
            'InpcMerida.name' => ['required','regex:/INPC MERIDA|inpc merida|Inpc Merida/',
                Rule::unique('loads', 'name')
                    ->where('type_id', $this->type_id)],
            'InpcMerida.source_id'=>'required'
        ]);

        $this->uploadFile($this->InpcMerida['file'],$this->InpcMerida['source_id'],$this->InpcMerida['name']);

        try {
            Excel::import(new InpcmeridaImport($this->Load), $this->path);
            $this->tag='success_inpcmerida';
            $this->successPulled($this->InpcMerida['name']);
        }catch (\Exception $e){
            $this->tag='error_inpcmerida';
            $this->errorPulled($this->InpcMerida['name']);
            Log::error($e->getMessage());
        }

        $this->reset(['InpcMerida']);
    }

    public function updatedInflationFile()
    {
        $this->Inflation['name'] = $this->Inflation['file']->getClientOriginalName() ;
    }

    public function uploadInflation()
    {
        $this->type_id=Type::where('name','regexp','Inflación')->firstOrFail()->id;
        $this->validate(['Inflation.file' => 'required|file|mimes:xls,xlsx,csv|max:102400',
            'Inflation.name' => ['required','regex:/inflación|INFLACIÓN|Inflación/',
                Rule::unique('loads', 'name')
                        ->where('type_id', $this->type_id)],
                        'Inflation.source_id'=>'required'
        ]);

        $this->uploadFile($this->Inflation['file'],$this->Inflation['source_id'],$this->Inflation['name']);

        try {
            Excel::import(new InflationImport($this->Load), $this->path);
            $this->tag='success_inflation';
            $this->successPulled($this->Inflation['name']);
        }catch (\Exception $e){
            $this->tag='error_inflation';
            $this->errorPulled($this->Inflation['name']);
            Log::error($e->getMessage());
        }

        $this->reset(['Inflation']);
    }

    public function updatedConevalFile()
    {
        $this->Coneval['name'] = $this->Coneval['file']->getClientOriginalName() ;
    }

    public function uploadConeval()
    {
        $this->type_id=Type::where('name','regexp','Coneval')->firstOrFail()->id;
        $this->validate(['Coneval.file' => 'required|file|mimes:xls,xlsx,csv|max:102400',
            'Coneval.name' => ['required','regex:/Coneval|CONEVAL|coneval/',
                Rule::unique('loads', 'name')
                    ->where('type_id', $this->type_id)],
            'Coneval.source_id'=>'required'
        ]);

        $this->uploadFile($this->Coneval['file'],$this->Coneval['source_id'],$this->Coneval['name']);

        try {
            Excel::import(new ConevalImport($this->Load), $this->path);
            $this->tag='success_coneval';
            $this->successPulled($this->Coneval['name']);
        }catch (\Exception $e){
            $this->tag='error_coneval';
            $this->errorPulled($this->Coneval['name']);
            Log::error($e->getMessage());
        }

        $this->reset(['Coneval']);
    }


    public function render()
    {
        return view('livewire.dashboard.upload.economy')
            ->extends('layouts.admin')
            ->section('content');
    }
}
