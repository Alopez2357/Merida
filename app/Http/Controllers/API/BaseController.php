<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class BaseController extends Controller
{
    protected $success=false;
    protected $response;
    protected $message="Not Found";
    protected $code=404;
    protected $per_page;
    protected $now;
    protected $sortBy = 'id';
    protected $sortDirection = 'desc';
    protected $search=null;
    protected $rules;
    protected $allowedFields;

    public function __construct()
    {
        $this->response=['body'=>null,'message'=>$this->message];
        $this->per_page=config('constants.api.per_page');
        $date=Carbon::now();
        $this->now=$date->format('Y-m-d H:i:s');
        $this->allowedFields=['id','name','created_at'];
        $this->rules=[
            'direction'=>[
                'sometimes',
                Rule::in(['asc', 'desc']),
            ],
            'sort'=>[
                'sometimes',
                Rule::in($this->allowedFields)
            ],
            'search'=>[
                'sometimes',
                'alpha_num'
            ]
        ];
    }

    public function searchSort($validated)
    {
        $this->sortBy = $validated['sort'] ?? $this->sortBy;
        $this->sortDirection = $validated['direction'] ?? $this->sortDirection;
        $this->search = $validated['search'] ?? null;
    }

    public function validatorErrors($errors)
    {
        $messages = [];
        foreach ($errors as $k => $value) {
            foreach ($value as $i => $val) {
                array_push($messages, $val);
            }
        }
        return $messages;
    }
}
