@section('title','CARGA DE DOCUMENTOS TURISMO')
<section class="py-5 overflow-y-auto">
    <div class="container">
        <div class="w-full flex  flex-wrap">
            <div class="w-full  flex flex-wrap justify-center my-6 bg-white rounded-lg shadow-lg py-2 px-4">
                <h2 class="text-xl font-semibold w-full text-start">Arribos al Aeropuerto</h2>
                <div class="w-full flex">
                    <div class="w-full md:w-1/3 flex flex-wrap p-4  items-center justify-center">
                    <label class="w-full flex flex-col px-6 py-4 text-center cursor-pointer  rounded-lg shadow hover:shadow-lg uppercase text-white bg-blue-500 hover:text-blue-500 hover:bg-white  hover:text-blue-500 border border-blue-500">
                    <i class="fas fa-cloud-upload-alt mx-auto text-xl "></i>
                    <span class="mt-2 text-base leading-normal">Upload File</span>
                        <!-- File Input -->
                        <input type="file" class="hidden" wire:model="Arrives.file">
                        <!-- Progress Bar -->
                </label>
                    @error('Arrives.file')
                         <div class="w-full text-red-500 font-semibold inline-block">{{ $message }}</div>
                    @enderror
                </div>
                    <div class="w-full md:w-2/3 p-2">
                    <div class="w-full py-2">
                        <label for="" class="inline-block w-full font-semibold"> Nombre del Archivo:</label>
                        <input type="text" wire:model="Arrives.name" class="p-1 w-full shadow rounded border">
                        @error('Arrives.name')
                            <div class="text-red-500 font-semibold">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="w-full py-2">
                        <label for="" class="inline-block w-full font-semibold">Fuente de Origen:</label>
                        <select wire:model="Arrives.source_id" class="rounded-lg shadow border w-full p-2">
                            <option >--SELECT--</option>
                            @foreach($sources as $source)
                                <option value="{{ $source->id }}">{{ $source->name }}</option>
                            @endforeach
                        </select>
                        @error('Arrives.source_id')
                            <div class="text-red-500 font-semibold">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                 </div>
                <div class="w-full flex">
                    <div class="w-full md:w-1/2 flex items-center">
                        @if (session()->has('success_arrives'))
                            <p class="w-full p-4 text-green-600 font-semibold rounded-full italic text-center uppercase text-xl">
                                {!!  session('success_arrives') !!}
                            </p>
                        @endif
                        @if (session()->has('error_arrives'))
                            <p class="w-full p-4 text-red-500 font-semibold rounded-full italic text-center uppercase text-xl ">
                                {!!  session('error_arrives') !!}
                            </p>
                        @endif
                    </div>
                    <div class="w-full md:w-1/2 flex justify-end items-center">
                        <button wire:click="uploadArrives" wire:loading.attr="disabled" wire:loading.class.remove="bg-green-600" wire:loading.class="bg-gray-200"
                                class=" bg-green-600 hover:bg-green-500 text-white py-2 px-4  rounded-lg shadow hover:shadow-lg  transition duration-300 ease-in-out ">
                                <span wire:loading.class="hidden" wire:target="uploadArrives">
                                 <i class="fas fa-save"></i>Guardar</span>
                            <span wire:loading wire:target="uploadArrives">Guardando...</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="w-full  flex flex-wrap justify-center my-6 bg-white rounded-lg shadow-lg py-2 px-4">
                <h2 class="w-full font-semibold text-xl text-start">Gasto Promedio Turistas</h2>
                <div class="w-full flex">
                    <div class="w-full md:w-1/3 flex flex-wrap p-4  items-center justify-center">
                        <label class="w-full flex flex-col px-6 py-4 text-center cursor-pointer  rounded-lg shadow hover:shadow-lg uppercase text-white bg-blue-500 hover:text-blue-500 hover:bg-white  hover:text-blue-500 border border-blue-500">
                            <i class="fas fa-cloud-upload-alt mx-auto text-xl "></i>
                            <span class="mt-2 text-base leading-normal">Upload File</span>
                            <!-- File Input -->
                            <input type="file" class="hidden" wire:model="Spend.file">
                            <!-- Progress Bar -->
                        </label>
                        @error('Spend.file')
                            <div class="w-full text-red-500 font-semibold inline-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="w-full md:w-2/3 p-2">
                        <div class="w-full py-2">
                            <label for="" class="inline-block w-full font-semibold"> Nombre del Archivo:</label>
                            <input type="text" wire:model="Spend.name" class="p-1 w-full shadow rounded border">
                            @error('Spend.name')
                                <div class="text-red-500 font-semibold">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="w-full py-2">
                            <label for="" class="inline-block w-full font-semibold">Fuente de Origen:</label>
                            <select wire:model="Spend.source_id" class="rounded-lg shadow border w-full p-2">
                                <option >--SELECT--</option>
                                @foreach($sources as $source)
                                    <option value="{{ $source->id }}">{{ $source->name }}</option>
                                @endforeach
                            </select>
                            @error('Spend.source_id')
                                <div class="text-red-500 font-semibold">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="w-full flex">
                    <div class="w-full md:w-1/2 flex items-center">
                        @if (session()->has('success_spend'))
                            <p class="w-full p-4 text-green-600 font-semibold rounded-full italic text-center uppercase text-xl">
                                {!!  session('success_spend') !!}
                            </p>
                        @endif
                            @if (session()->has('error_spend'))
                                <p class="w-full p-4 text-red-500 font-semibold rounded-full italic text-center uppercase text-xl ">
                                    {!!  session('error_spend') !!}
                                </p>
                            @endif
                    </div>
                    <div class="w-full md:w-1/2 flex justify-end items-center">
                        <button wire:click="uploadSpend" wire:loading.attr="disabled" wire:loading.class.remove="bg-green-600" wire:loading.class="bg-gray-200"
                                class=" bg-green-600 hover:bg-green-500 text-white py-2 px-4  rounded-lg shadow hover:shadow-lg  transition duration-300 ease-in-out ">
                                <span wire:loading.class="hidden" wire:target="uploadSpend">
                                 <i class="fas fa-save"></i>Guardar</span>
                            <span wire:loading wire:target="uploadSpend">Guardando...</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="w-full  flex flex-wrap justify-center my-6 bg-white rounded-lg shadow-lg py-2 px-4">
                <h1 class="w-full text-start font-semibold text-xl">Ocupación Hotelera</h1>
                <div class="w-full flex">
                    <div class="w-full md:w-1/3 flex flex-wrap p-4  items-center justify-center">
                        <label class="w-full flex flex-col px-6 py-4 text-center cursor-pointer  rounded-lg shadow hover:shadow-lg uppercase text-white bg-blue-500 hover:text-blue-500 hover:bg-white  hover:text-blue-500 border border-blue-500">
                            <i class="fas fa-cloud-upload-alt mx-auto text-xl "></i>
                            <span class="mt-2 text-base leading-normal">Upload File</span>
                            <!-- File Input -->
                            <input type="file" class="hidden" wire:model="Occupation.file">
                        </label>
                        @error('Occupation.file')
                        <div class="w-full text-red-500 font-semibold inline-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="w-full md:w-2/3 p-2">
                        <div class="w-full py-2">
                            <label for="" class="inline-block w-full font-semibold"> Nombre del Archivo:</label>
                            <input type="text" wire:model="Occupation.name" class="p-1 w-full shadow rounded border">
                            @error('Occupation.name')
                            <div class="text-red-500 font-semibold">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="w-full py-2">
                            <label for="" class="inline-block w-full font-semibold">Fuente de Origen:</label>
                            <select wire:model="Occupation.source_id" class="rounded-lg shadow border w-full p-2">
                                <option >--SELECT--</option>
                                @foreach($sources as $source)
                                    <option value="{{ $source->id }}">{{ $source->name }}</option>
                                @endforeach
                            </select>
                            @error('Occupation.source_id')
                                <div class="text-red-500 font-semibold">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="w-full flex">
                    <div class="w-full md:w-1/2 flex items-center">
                        @if (session()->has('success_occupation'))
                            <p class="w-full p-4 text-green-600 font-semibold rounded-full italic text-center uppercase text-xl">
                                {!!  session('success_occupation') !!}
                            </p>
                        @endif
                        @if (session()->has('error_occupation'))
                            <p class="w-full p-4 text-red-500 font-semibold rounded-full italic text-center uppercase text-xl ">
                                {!!  session('error_occupation') !!}
                            </p>
                        @endif
                    </div>
                    <div class="w-full md:w-1/2 flex justify-end items-center">
                        <button wire:click="uploadOccupation" wire:loading.attr="disabled" wire:loading.class.remove="bg-green-600" wire:loading.class="bg-gray-200"
                                class=" bg-green-600 hover:bg-green-500 text-white py-2 px-4  rounded-lg shadow hover:shadow-lg  transition duration-300 ease-in-out ">
                                <span wire:loading.class="hidden" wire:target="uploadOccupation">
                                 <i class="fas fa-save"></i>Guardar</span>
                            <span wire:loading wire:target="uploadOccupation">Guardando...</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="w-full  flex flex-wrap justify-center my-6 bg-white rounded-lg shadow-lg py-2 px-4">
                <h1 class="text-xl font-semibold text-start w-full">Turistas Pernocta</h1>
                <div class="w-full flex">
                    <div class="w-full md:w-1/3 flex flex-wrap p-4  items-center justify-center">
                        <label class="w-full flex flex-col px-6 py-4 text-center cursor-pointer  rounded-lg shadow hover:shadow-lg uppercase text-white bg-blue-500 hover:text-blue-500 hover:bg-white  hover:text-blue-500 border border-blue-500">
                            <i class="fas fa-cloud-upload-alt mx-auto text-xl"></i>
                            <span class="mt-2 text-base leading-normal">Upload File</span>
                            <!-- File Input -->
                            <input type="file" class="hidden" wire:model="Stopover.file">
                        </label>
                        @error('Stopover.file')
                            <div class="w-full text-red-500 font-semibold inline-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="w-full md:w-2/3 p-2">
                        <div class="w-full py-2">
                            <label for="" class="inline-block w-full font-semibold"> Nombre del Archivo:</label>
                            <input type="text" wire:model="Stopover.name" class="p-1 w-full shadow rounded border">
                            @error('Stopover.name')
                            <div class="text-red-500 font-semibold">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="w-full py-2">
                            <label for="" class="inline-block w-full font-semibold">Fuente de Origen:</label>
                            <select wire:model="Stopover.source_id" class="rounded-lg shadow border w-full p-2">
                                <option >--SELECT--</option>
                                @foreach($sources as $source)
                                    <option value="{{ $source->id }}">{{ $source->name }}</option>
                                @endforeach
                            </select>
                            @error('Stopover.source_id')
                            <div class="text-red-500 font-semibold">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="w-full flex">
                    <div class="w-full md:w-1/2 flex items-center">
                        @if (session()->has('success_stopover'))
                            <p class="w-full p-4 text-green-600 font-semibold rounded-full italic text-center uppercase text-xl">
                                {!!  session('success_stopover') !!}
                            </p>
                        @endif
                        @if (session()->has('error_stopover'))
                            <p class="w-full p-4 text-red-500 font-semibold rounded-full italic text-center uppercase text-xl ">
                                {!!  session('error_stopover') !!}
                            </p>
                        @endif
                    </div>
                    <div class="w-full md:w-1/2 flex justify-end items-center">
                        <button wire:click="uploadStopover" wire:loading.attr="disabled" wire:loading.class.remove="bg-green-600" wire:loading.class="bg-gray-200"
                                class=" bg-green-600 hover:bg-green-500 text-white py-2 px-4  rounded-lg shadow hover:shadow-lg  transition duration-300 ease-in-out ">
                                <span wire:loading.class="hidden" wire:target="uploadStopover">
                                 <i class="fas fa-save"></i>Guardar</span>
                            <span wire:loading wire:target="uploadStopover">Guardando...</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="w-full  flex flex-wrap justify-center my-6 bg-white rounded-lg shadow-lg py-2 px-4">
                <h1 class="text-xl font-semibold text-start w-full">Origen y Destino</h1>
                <div class="w-full flex">
                    <div class="w-full md:w-1/3 flex flex-wrap p-4  items-center justify-center">
                        <label class="w-full flex flex-col px-6 py-4 text-center cursor-pointer  rounded-lg shadow hover:shadow-lg uppercase text-white bg-blue-500 hover:text-blue-500 hover:bg-white  hover:text-blue-500 border border-blue-500">
                            <i class="fas fa-cloud-upload-alt font-semibold text-xl mx-auto"></i>
                            <span class="mt-2 text-base leading-normal">Upload File</span>
                            <!-- File Input -->
                            <input type="file" class="hidden" wire:model="Od.file">
                        </label>
                        @error('Od.file')
                            <div class="w-full text-red-500 font-semibold inline-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="w-full md:w-2/3 p-2">
                        <div class="w-full py-2">
                            <label for="" class="inline-block w-full font-semibold"> Nombre del Archivo:</label>
                            <input type="text" wire:model="Od.name" class="p-1 w-full shadow rounded border">
                            @error('Od.name')
                            <div class="text-red-500 font-semibold">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="w-full py-2">
                            <label for="" class="inline-block w-full font-semibold">Fuente de Origen:</label>
                            <select wire:model="Od.source_id" class="rounded-lg shadow border w-full p-2">
                                <option >--SELECT--</option>
                                @foreach($sources as $source)
                                    <option value="{{ $source->id }}">{{ $source->name }}</option>
                                @endforeach
                            </select>
                            @error('Od.source_id')
                            <div class="text-red-500 font-semibold">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="w-full flex">
                    <div class="w-full md:w-1/2 flex items-center">
                        @if (session()->has('success_od'))
                            <p class="w-full p-4 text-green-600 font-semibold rounded-full italic text-center uppercase text-xl">
                                {!!  session('success_od') !!}
                            </p>
                        @endif
                        @if (session()->has('error_od'))
                            <p class="w-full p-4 text-red-500 font-semibold rounded-full italic text-center uppercase text-xl ">
                                {!!  session('error_od') !!}
                            </p>
                        @endif
                    </div>
                    <div class="w-full md:w-1/2 flex justify-end items-center">
                        <button wire:click="uploadOd" wire:loading.attr="disabled" wire:loading.class.remove="bg-green-600" wire:loading.class="bg-gray-200"
                                class=" bg-green-600 hover:bg-green-500 text-white py-2 px-4  rounded-lg shadow hover:shadow-lg  transition duration-300 ease-in-out ">
                                <span wire:loading.class="hidden" wire:target="uploadOd">
                                 <i class="fas fa-save"></i>Guardar</span>
                            <span wire:loading wire:target="uploadOd">Guardando...</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="w-full  flex flex-wrap justify-center my-6 bg-white rounded-lg shadow-lg py-2 px-4">
                <h1 class="text-xl font-semibold text-start w-full">Movimiento Operacional</h1>
                <div class="w-full flex">
                    <div class="w-full md:w-1/3 flex flex-wrap p-4  items-center justify-center">
                        <label class="w-full flex flex-col px-6 py-4 text-center cursor-pointer  rounded-lg shadow hover:shadow-lg uppercase text-white bg-blue-500 hover:text-blue-500 hover:bg-white  hover:text-blue-500 border border-blue-500">
                            <i class="fas fa-cloud-upload-alt font-semibold text-xl mx-auto"></i>
                            <span class="mt-2 text-base leading-normal">Upload File</span>
                            <!-- File Input -->
                            <input type="file" class="hidden" wire:model="Operational.file">
                        </label>
                        @error('Operational.file')
                            <div class="w-full text-red-500 font-semibold inline-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="w-full md:w-2/3 p-2">
                        <div class="w-full py-2">
                            <label for="" class="inline-block w-full font-semibold"> Nombre del Archivo:</label>
                            <input type="text" wire:model="Operational.name" class="p-1 w-full shadow rounded border">
                            @error('Operational.name')
                                <div class="text-red-500 font-semibold">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="w-full py-2">
                            <label for="" class="inline-block w-full font-semibold">Fuente de Origen:</label>
                            <select wire:model="Operational.source_id" class="rounded-lg shadow border w-full p-2">
                                <option >--SELECT--</option>
                                @foreach($sources as $source)
                                    <option value="{{ $source->id }}">{{ $source->name }}</option>
                                @endforeach
                            </select>
                            @error('Operational.source_id')
                                <div class="text-red-500 font-semibold">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="w-full flex">
                    <div class="w-full md:w-1/2 flex items-center">
                        @if (session()->has('success_operational'))
                            <p class="w-full p-4 text-green-600 font-semibold rounded-full italic text-center uppercase text-xl">
                                {!!  session('success_operational') !!}
                            </p>
                        @endif
                        @if (session()->has('error_operational'))
                            <p class="w-full p-4 text-red-500 font-semibold rounded-full italic text-center uppercase text-xl ">
                                {!!  session('error_operational') !!}
                            </p>
                        @endif
                    </div>
                    <div class="w-full md:w-1/2 flex justify-end items-center">
                        <button wire:click="uploadOperational" wire:loading.attr="disabled" wire:loading.class.remove="bg-green-600" wire:loading.class="bg-gray-200"
                                class=" bg-green-600 hover:bg-green-500 text-white py-2 px-4  rounded-lg shadow hover:shadow-lg  transition duration-300 ease-in-out ">
                                <span wire:loading.class="hidden" wire:target="uploadOperational">
                                 <i class="fas fa-save"></i>Guardar</span>
                            <span wire:loading wire:target="uploadOperational">Guardando...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
