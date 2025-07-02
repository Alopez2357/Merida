<?php

namespace App\Http\Controllers\API\V1\Upload;

use App\Http\Controllers\API\BaseController;
use App\Imports\AirdnaImport;
use App\Models\Load;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;

class MapsController extends BaseController
{
    public $type_id;
    public $file;
    public $path;
    public $Load;

    public function airdna(Request $request)
    {
        $this->type_id=Type::where('name','REGEXP',"airdna")->firstOrFail()->id;
        $validated=validator($request->all(),["name"=>["required","unique:loads",Rule::unique('loads','name')->where('type_id','=',$this->type_id)],
                                            "file"=> 'required|file|mimes:csv|max:102400',
                                            "source_id"=>'required|exists:sources,id']);

        if($validated->fails()) {
            return response($validated->errors(), 400);
        }

        if ($file = $request->file('file')) {
            $this->file = $file;
            $this->name = $request->name;
            $this->source_id = $request->source_id;
        }

        $this->uploadFile();

        try {
            $headings=(new HeadingRowImport)->toArray($this->path);
            Excel::import(new AirdnaImport($this->Load, $headings), $this->path);

            $this->response=[
                'body'=>[$this->path],
                'message' => "Successful Filled"];
            $this->code=200;

        }catch (\Exception $e){
            Log::error('Import: '.$e->getMessage());

            $this->response=[
                'body'=>[$e->getCode(),$e->getLine()],
                'message' => $e->getMessage()];
            $this->code=400;
        }

        return response($this->response,$this->code);
    }


    public function uploadFile()
    {

        try{
            $this->file->storeAs('public/files', $this->name);
            $this->path='public/files/'.$this->name;
        }catch (\Exception $e){
            Log::error('Store: '.$e->getMessage());
        }

        try{
            $this->Load=Load::create(['name'=>$this->name,
                'file'=>$this->name,
                'type_id'=>$this->type_id,
                'source_id'=>$this->source_id,
                'user_id'=>1]);
        }catch (\Exception $e){
            Log::error('Load: '.$e->getMessage());
        }

    }
}
