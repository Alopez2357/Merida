<?php

namespace App\Http\Livewire\Dashboard\Uploads;

use App\Traits\UploadTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;


class File extends Component
{
    use WithFileUploads;

    public $tag;
    public $file;
    public $name;
    public $type_id;
    public $key;
    public $source_id;
    public $regexp;
    public $Load;
    public $path;
    public $ext;

    use UploadTrait;

    public function mount($type_id, $key, $regexp)
    {
        $this->type_id=$type_id;
        $this->key=$key;
        $this->regexp=$regexp;
    }

    public function updatedFile()
    {
        $this->ext = pathinfo($this->file->getClientOriginalName(), PATHINFO_EXTENSION);
        $this->name = pathinfo($this->file->getClientOriginalName(),PATHINFO_FILENAME);
    }

    public function upload()
    {

        $this->validate(['file' => 'required|file|mimes:xls,xlsx,csv|max:102400',
                        'name' => ['required',"{$this->regexp}",
                                    Rule::unique('loads', 'name')
                                    ->where('type_id', $this->type_id)],
                        'source_id'=>'required'
                    ]);

        $this->uploadFile();

        try {
            $this->emitUp('importFile', $this->Load,$this->path);
            $this->successPulled('success');
        }catch (\Exception $e){
            $this->errorPulled('error');
            Log::error($e->getMessage());
        }

        $this->reset(['file','name','source_id']);
    }


    public function render()
    {
        return view('livewire.dashboard.uploads.file');
    }
}
