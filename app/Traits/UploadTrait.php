<?php

namespace App\Traits;

use App\Models\Load;
use Illuminate\Support\Facades\Log;

trait UploadTrait
{


    public function uploadFile()
    {
        try{
            $this->path="public/files";
            $this->file->storeAs($this->path, $this->name.'.'.$this->ext);
            $this->path.="/{$this->name}.{$this->ext}";
        }catch (\Exception $e){
            Log::error($e->getMessage());
        }

        try{
            $this->Load=Load::create([
                'name'=>$this->name,
                'file'=>"{$this->name}.{$this->ext}",
                'type_id'=>$this->type_id,
                'source_id'=>$this->source_id,
                'user_id'=>auth()->user()->id
            ]);
            $this->successStored('success');
        }catch (\Exception $e){
            Log::error($e->getMessage());
        }
    }


    public function successPulled($tag)
    {
        session()->flash($tag, "Successfull Pulled! <br> {$this->name}.{$this->ext}");
    }

    public function errorPulled($tag)
    {
        session()->flash($tag, "Error on Pulled! <br> {$this->name}.{$this->ext} <br> Formato Incorrecto");
    }

    public function successStored($tag)
    {
        session()->flash($tag, "Successfull Stored! <br> {$this->name}.{$this->ext}");
    }
}