<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = \Spatie\Permission\Models\Role::all();
        return view('dashboard.users.create',['roles'=>$roles])
                    ->extends('layouts.admin')
                    ->section('content');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
                'name'=>'required',
                'email'=>'required|email|unique:users,email',
                'password'=>'required|min:6',
                'rol'=>'required|numeric'
        ]);

        $user=$request->all();
        $user['password']=bcrypt($request->password);

        try {
            User::create($user)->assignRole($request->rol);
        }catch (\Exception $e){
            Log::error($e->getMessage());
        }
        return redirect()->route('dashboard.users.list');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user=User::find($id);
        $roles = \Spatie\Permission\Models\Role::all();
        return view('dashboard.users.edit',['roles'=>$roles,'user'=>$user])
            ->extends('layouts.admin')
            ->section('content');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'=>'required',
            'email'=>"required|email|unique:users,email,$id",
            'password'=>!is_null($request->password)?'required|min:8':'sometimes',
            'rol'=>'required|numeric'
        ]);

        $user=User::findOrFail($id);
        $user->name=$request->name;
        $user->email=$request->email;
        if($request->password)
            $user->password = bcrypt($request->password);

        try {
            $user->save();
            $user->syncRoles($request->rol);
        }catch (\Exception $e){
            Log::error($e->getMessage());
        }

        return redirect()->route('dashboard.users.list');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $User = User::findOrFail($id);
            $User->delete();

        }catch (\Exception $e){
            Log::error($e->getMessage());
        }

        return redirect()->route('dashboard.users.list');
    }
}
