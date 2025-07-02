@extends('layouts.admin')
@section('title','Nuevo Usuario')
@section('content')
<section class="h-full overflow-y-auto p-2">
    <div class="container">
        <div class="flex rounded-lg bg-white shadow-lg justify-center">
            <div class="w-full lg:w-7/12 bg-white p-5 rounded-lg lg:rounded-l-none">
                <h3 class="pt-4 text-2xl text-center"></h3>
                <form class="px-8 pt-6 pb-8 mb-4 bg-white rounded" method="POST" action="{{ route('dashboard.users.store') }}">
                    @csrf
                    <div class="mb-4 md:flex md:justify-between">
                        <div class="mb-4 md:mr-2 md:mb-0 ">
                            <label class="block mb-2 text-sm font-bold text-gray-700" for="firstName">
                                First Name
                            </label>
                            <input
                                    class="w-full px-3 py-2 text-sm leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                    id="firstName"
                                    name="name"
                                    value="{{ old('name') }}"
                                    type="text"
                                    placeholder="First Name"
                            />
                        </div>
                        <div class="md:ml-2">
                            <label class="block mb-2 text-sm font-bold text-gray-700" for="lastName">
                                Rol
                            </label>
                            <select name="rol" id="rol" class="w-full px-3 py-2 text-sm leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline">
                                <option value="">--ELEGIR--</option>
                                @foreach($roles as $rol)
                                    <option value="{{ $rol->id }}" {{ (old('rol') ==$rol->id)?'selected':'' }}>{{ $rol->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block mb-2 text-sm font-bold text-gray-700" for="email">
                            Email
                        </label>
                        <input
                                class="w-full px-3 py-2 mb-3 text-sm leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="Email"
                        />
                    </div>
                    <div class="mb-4 md:flex md:justify-between">
                        <div class="mb-4 md:mr-2 md:mb-0">
                            <label class="block mb-2 text-sm font-bold text-gray-700" for="password">
                                Password
                            </label>
                            <input
                                    class="w-full px-3 py-2 mb-3 text-sm leading-tight text-gray-700 border border-red-500 rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                    id="password"
                                    name="password"
                                    type="password"
                                    value="{{ old('password') }}"
                                    placeholder="******************"
                            />
                            <p class="text-xs italic text-red-500">Please choose a password.</p>
                        </div>
                        <!--<div class="md:ml-2">
                            <label class="block mb-2 text-sm font-bold text-gray-700" for="c_password">
                                Confirm Password
                            </label>
                            <input
                                    class="w-full px-3 py-2 mb-3 text-sm leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                    id="c_password"
                                    type="password"
                                    placeholder="******************"
                            />
                        </div>-->
                    </div>
                    <div class=" text-center">
                        <button
                                class="w-full px-4 py-2 font-bold text-white bg-blue-500 rounded-full hover:bg-blue-700 focus:outline-none focus:shadow-outline"
                                type="submit"
                        >
                            Registrar
                        </button>
                    </div>
                </form>
                <div>
                    @if ($errors->any())
                        <div class="text-red-500">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection