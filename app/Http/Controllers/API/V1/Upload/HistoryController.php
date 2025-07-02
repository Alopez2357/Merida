<?php

namespace App\Http\Controllers\API\V1\Upload;

use App\Http\Controllers\API\BaseController;
use App\Models\Load;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class HistoryController extends BaseController
{
    public function source(Request $request)
    {

        $validated=validator($request->all(),["load_id"=>"required|exists:loads,id",
                                                "source_id"=>'required|exists:sources,id']);

        if($validated->fails()) {
            return response($validated->errors(), 400);
        }

        try {

            $Load=Load::find($request->load_id);
            $Load->source_id=$request->source_id;
            $Load->save();

            $this->response=[
                'body'=>$Load,
                'message' => "Successful"];
            $this->code=200;

        }catch (\Exception $e){
            Log::error('History: '.$e->getMessage());
        }

        return response($this->response,$this->code);

    }
}
