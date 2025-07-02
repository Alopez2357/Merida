@section('title','CARGA DE DOCUMENTOS ECONOMÍA')
<section class="py-5 overflow-y-auto">
    <div class="container">
        <div class="w-full flex  flex-wrap">
            <!-- EMPLEO-->
            <div class="w-full  flex flex-wrap justify-center my-6 bg-white rounded-lg shadow-lg py-2 px-4">
                <h2 class="text-xl font-semibold w-full text-start">Empleo</h2>
                <div class="w-full flex">
                    <div class="w-full md:w-1/3 flex flex-wrap p-4  items-center justify-center">
                        <label class="w-full flex flex-col px-6 py-4 text-center cursor-pointer  rounded-lg shadow hover:shadow-lg uppercase text-white bg-blue-500 hover:text-blue-500 hover:bg-white  hover:text-blue-500 border border-blue-500">
                            <i class="fas fa-cloud-upload-alt mx-auto text-xl "></i>
                            <span class="mt-2 text-base leading-normal">Upload File</span>
                            <!-- File Input -->
                            <input type="file" class="hidden" wire:model="Employment.file">
                            <!-- Progress Bar -->
                        </label>
                        @error('Employment.file')
                        <div class="w-full text-red-500 font-semibold inline-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="w-full md:w-2/3 p-2">
                        <div class="w-full py-2">
                            <label for="" class="inline-block w-full font-semibold"> Nombre del Archivo:</label>
                            <input type="text" placeholder="EMPLEO" wire:model="Employment.name" class="p-1 w-full shadow rounded border">
                            @error('Employment.name')
                            <div class="text-red-500 font-semibold">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="w-full py-2">
                            <label for="" class="inline-block w-full font-semibold">Fuente de Origen:</label>
                            <select wire:model="Employment.source_id" class="rounded-lg shadow border w-full p-2">
                                <option >--SELECT--</option>
                                @foreach($sources as $source)
                                    <option value="{{ $source->id }}">{{ $source->name }}</option>
                                @endforeach
                            </select>
                            @error('Employment.source_id')
                            <div class="text-red-500 font-semibold">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="w-full flex">
                    <div class="w-full md:w-1/2 flex items-center">
                        @if (session()->has('success_employment'))
                            <p class="w-full p-4 text-green-600 font-semibold rounded-full italic text-center uppercase text-xl">
                                {!!  session('success_employment') !!}
                            </p>
                        @endif
                        @if (session()->has('error_employment'))
                            <p class="w-full p-4 text-red-500 font-semibold rounded-full italic text-center uppercase text-xl ">
                                {!!  session('error_employment') !!}
                            </p>
                        @endif
                    </div>
                    <div class="w-full md:w-1/2 flex justify-end items-center">
                        <button wire:click="uploadEmployment" wire:loading.attr="disabled" wire:loading.class.remove="bg-green-600" wire:loading.class="bg-gray-200"
                                class=" bg-green-600 hover:bg-green-500 text-white py-2 px-4  rounded-lg shadow hover:shadow-lg  transition duration-300 ease-in-out ">
                            <span wire:loading.class="hidden" wire:target="uploadEmployment">
                                 <i class="fas fa-save"></i> Guardar</span>
                            <span wire:loading wire:target="uploadEmployment"> Guardando...</span>
                        </button>
                    </div>
                </div>
            </div>
            <!-- INPC MERIDA -->
            <div class="w-full  flex flex-wrap justify-center my-6 bg-white rounded-lg shadow-lg py-2 px-4">
                <h2 class="w-full font-semibold text-xl text-start">INPC MERIDA</h2>
                <div class="w-full flex">
                    <div class="w-full md:w-1/3 flex flex-wrap p-4  items-center justify-center">
                        <label class="w-full flex flex-col px-6 py-4 text-center cursor-pointer  rounded-lg shadow hover:shadow-lg uppercase text-white bg-blue-500 hover:text-blue-500 hover:bg-white  hover:text-blue-500 border border-blue-500">
                            <i class="fas fa-cloud-upload-alt mx-auto text-xl "></i>
                            <span class="mt-2 text-base leading-normal">Upload File</span>
                            <!-- File Input -->
                            <input type="file" class="hidden" wire:model="InpcMerida.file">
                            <!-- Progress Bar -->
                        </label>
                        @error('InpcMerida.file')
                        <div class="w-full text-red-500 font-semibold inline-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="w-full md:w-2/3 p-2">
                        <div class="w-full py-2">
                            <label for="" class="inline-block w-full font-semibold"> Nombre del Archivo:</label>
                            <input type="text" placeholder="INPC Merida" wire:model="InpcMerida.name" class="p-1 w-full shadow rounded border">
                            @error('InpcMerida.name')
                            <div class="text-red-500 font-semibold">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="w-full py-2">
                            <label for="" class="inline-block w-full font-semibold">Fuente de Origen:</label>
                            <select wire:model="InpcMerida.source_id" class="rounded-lg shadow border w-full p-2">
                                <option >--SELECT--</option>
                                @foreach($sources as $source)
                                    <option value="{{ $source->id }}">{{ $source->name }}</option>
                                @endforeach
                            </select>
                            @error('InpcMerida.source_id')
                            <div class="text-red-500 font-semibold">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="w-full flex">
                    <div class="w-full md:w-1/2 flex items-center">
                        @if (session()->has('success_inpcmerida'))
                            <p class="w-full p-4 text-green-600 font-semibold rounded-full italic text-center uppercase text-xl">
                                {!!  session('success_inpcmerida') !!}
                            </p>
                        @endif
                        @if (session()->has('error_inpcmerida'))
                            <p class="w-full p-4 text-red-500 font-semibold rounded-full italic text-center uppercase text-xl ">
                                {!!  session('error_inpcmerida') !!}
                            </p>
                        @endif
                    </div>
                    <div class="w-full md:w-1/2 flex justify-end items-center">
                        <button wire:click="uploadInpcMerida" wire:loading.attr="disabled" wire:loading.class.remove="bg-green-600" wire:loading.class="bg-gray-200"
                                class=" bg-green-600 hover:bg-green-500 text-white py-2 px-4  rounded-lg shadow hover:shadow-lg  transition duration-300 ease-in-out ">
                            <span wire:loading.class="hidden" wire:target="uploadInpcMerida">
                                 <i class="fas fa-save"></i>Guardar</span>
                            <span wire:loading wire:target="uploadInpcMerida">Guardando...</span>
                        </button>
                    </div>
                </div>
            </div>
            <!-- INPC Nacional -->
            <div class="w-full  flex flex-wrap justify-center my-6 bg-white rounded-lg shadow-lg py-2 px-4">
                <h2 class="w-full font-semibold text-xl text-start">INPC Nacional</h2>
                <div class="w-full flex">
                    <div class="w-full md:w-1/3 flex flex-wrap p-4  items-center justify-center">
                        <label class="w-full flex flex-col px-6 py-4 text-center cursor-pointer  rounded-lg shadow hover:shadow-lg uppercase text-white bg-blue-500 hover:text-blue-500 hover:bg-white  hover:text-blue-500 border border-blue-500">
                            <i class="fas fa-cloud-upload-alt mx-auto text-xl "></i>
                            <span class="mt-2 text-base leading-normal">Upload File</span>
                            <!-- File Input -->
                            <input type="file" class="hidden" wire:model="InpcNacional.file">
                            <!-- Progress Bar -->
                        </label>
                        @error('InpcNacional.file')
                        <div class="w-full text-red-500 font-semibold inline-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="w-full md:w-2/3 p-2">
                        <div class="w-full py-2">
                            <label for="" class="inline-block w-full font-semibold"> Nombre del Archivo:</label>
                            <input type="text" placeholder="INPC Nacional" wire:model="InpcNacional.name" class="p-1 w-full shadow rounded border">
                            @error('InpcNacional.name')
                            <div class="text-red-500 font-semibold">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="w-full py-2">
                            <label for="" class="inline-block w-full font-semibold">Fuente de Origen:</label>
                            <select wire:model="InpcNacional.source_id" class="rounded-lg shadow border w-full p-2">
                                <option >--SELECT--</option>
                                @foreach($sources as $source)
                                    <option value="{{ $source->id }}">{{ $source->name }}</option>
                                @endforeach
                            </select>
                            @error('InpcNacional.source_id')
                            <div class="text-red-500 font-semibold">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="w-full flex">
                    <div class="w-full md:w-1/2 flex items-center">
                        @if (session()->has('success_inpcnacional'))
                            <p class="w-full p-4 text-green-600 font-semibold rounded-full italic text-center uppercase text-xl">
                                {!!  session('success_inpcnacional') !!}
                            </p>
                        @endif
                        @if (session()->has('error_inpcnacional'))
                            <p class="w-full p-4 text-red-500 font-semibold rounded-full italic text-center uppercase text-xl ">
                                {!!  session('error_inpcnacional') !!}
                            </p>
                        @endif
                    </div>
                    <div class="w-full md:w-1/2 flex justify-end items-center">
                        <button wire:click="uploadInpcNacional" wire:loading.attr="disabled" wire:loading.class.remove="bg-green-600" wire:loading.class="bg-gray-200"
                                class=" bg-green-600 hover:bg-green-500 text-white py-2 px-4  rounded-lg shadow hover:shadow-lg  transition duration-300 ease-in-out ">
                            <span wire:loading.class="hidden" wire:target="uploadInpcNacional">
                                 <i class="fas fa-save"></i>Guardar</span>
                            <span wire:loading wire:target="uploadInpcNacional">Guardando...</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Inflación -->
            {{--<div class="w-full  flex flex-wrap justify-center my-6 bg-white rounded-lg shadow-lg py-2 px-4">
                <h2 class="w-full font-semibold text-xl text-start">Inflación</h2>
                <div class="w-full flex">
                    <div class="w-full md:w-1/3 flex flex-wrap p-4  items-center justify-center">
                        <label class="w-full flex flex-col px-6 py-4 text-center cursor-pointer  rounded-lg shadow hover:shadow-lg uppercase text-white bg-blue-500 hover:text-blue-500 hover:bg-white  hover:text-blue-500 border border-blue-500">
                            <i class="fas fa-cloud-upload-alt mx-auto text-xl "></i>
                            <span class="mt-2 text-base leading-normal">Upload File</span>
                            <!-- File Input -->
                            <input type="file" class="hidden" wire:model="Inflation.file">
                            <!-- Progress Bar -->
                        </label>
                        @error('Inflation.file')
                        <div class="w-full text-red-500 font-semibold inline-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="w-full md:w-2/3 p-2">
                        <div class="w-full py-2">
                            <label for="" class="inline-block w-full font-semibold"> Nombre del Archivo:</label>
                            <input type="text" placeholder="Inflación" wire:model="Inflation.name" class="p-1 w-full shadow rounded border">
                            @error('Inflation.name')
                            <div class="text-red-500 font-semibold">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="w-full py-2">
                            <label for="" class="inline-block w-full font-semibold">Fuente de Origen:</label>
                            <select wire:model="Inflation.source_id" class="rounded-lg shadow border w-full p-2">
                                <option >--SELECT--</option>
                                @foreach($sources as $source)
                                    <option value="{{ $source->id }}">{{ $source->name }}</option>
                                @endforeach
                            </select>
                            @error('Inflation.source_id')
                            <div class="text-red-500 font-semibold">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="w-full flex">
                    <div class="w-full md:w-1/2 flex items-center">
                        @if (session()->has('success_inflation'))
                            <p class="w-full p-4 text-green-600 font-semibold rounded-full italic text-center uppercase text-xl">
                                {!!  session('success_inflation') !!}
                            </p>
                        @endif
                        @if (session()->has('error_inflation'))
                            <p class="w-full p-4 text-red-500 font-semibold rounded-full italic text-center uppercase text-xl ">
                                {!!  session('error_inflation') !!}
                            </p>
                        @endif
                    </div>
                    <div class="w-full md:w-1/2 flex justify-end items-center">
                        <button wire:click="uploadInflation" wire:loading.attr="disabled"
                                wire:loading.class.remove="bg-green-600" wire:loading.class="bg-gray-200"
                                class=" bg-green-600 hover:bg-green-500 text-white py-2 px-4  rounded-lg shadow hover:shadow-lg  transition duration-300 ease-in-out ">
                            <span wire:loading.class="hidden" wire:target="uploadInflation">
                                 <i class="fas fa-save"></i>Guardar</span>
                            <span wire:loading wire:target="uploadInflation">Guardando...</span>
                        </button>
                    </div>
                </div>
            </div>
            --}}

            <!-- CONEVAL-->
            <div class="w-full  flex flex-wrap justify-center my-6 bg-white rounded-lg shadow-lg py-2 px-4">
                <h2 class="w-full font-semibold text-xl text-start">Coneval</h2>
                <div class="w-full flex">
                    <div class="w-full md:w-1/3 flex flex-wrap p-4  items-center justify-center">
                        <label class="w-full flex flex-col px-6 py-4 text-center cursor-pointer  rounded-lg shadow hover:shadow-lg uppercase text-white bg-blue-500 hover:text-blue-500 hover:bg-white  hover:text-blue-500 border border-blue-500">
                            <i class="fas fa-cloud-upload-alt mx-auto text-xl "></i>
                            <span class="mt-2 text-base leading-normal">Upload File</span>
                            <!-- File Input -->
                            <input type="file" class="hidden" wire:model="Coneval.file">
                            <!-- Progress Bar -->
                        </label>
                        @error('Coneval.file')
                        <div class="w-full text-red-500 font-semibold inline-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="w-full md:w-2/3 p-2">
                        <div class="w-full py-2">
                            <label for="" class="inline-block w-full font-semibold"> Nombre del Archivo:</label>
                            <input type="text" placeholder="CONEVAL" wire:model="Coneval.name" class="p-1 w-full shadow rounded border">
                            @error('Coneval.name')
                            <div class="text-red-500 font-semibold">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="w-full py-2">
                            <label for="" class="inline-block w-full font-semibold">Fuente de Origen:</label>
                            <select wire:model="Coneval.source_id" class="rounded-lg shadow border w-full p-2">
                                <option >--SELECT--</option>
                                @foreach($sources as $source)
                                    <option value="{{ $source->id }}">{{ $source->name }}</option>
                                @endforeach
                            </select>
                            @error('Coneval.source_id')
                            <div class="text-red-500 font-semibold">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="w-full flex">
                    <div class="w-full md:w-1/2 flex items-center">
                        @if (session()->has('success_coneval'))
                            <p class="w-full p-4 text-green-600 font-semibold rounded-full italic text-center uppercase text-xl">
                                {!!  session('success_coneval') !!}
                            </p>
                        @endif
                        @if (session()->has('error_coneval'))
                            <p class="w-full p-4 text-red-500 font-semibold rounded-full italic text-center uppercase text-xl ">
                                {!!  session('error_coneval') !!}
                            </p>
                        @endif
                    </div>
                    <div class="w-full md:w-1/2 flex justify-end items-center">
                        <button wire:click="uploadConeval" wire:loading.attr="disabled"
                                wire:loading.class.remove="bg-green-600" wire:loading.class="bg-gray-200"
                                class=" bg-green-600 hover:bg-green-500 text-white py-2 px-4  rounded-lg shadow hover:shadow-lg  transition duration-300 ease-in-out ">
                    <span wire:loading.class="hidden" wire:target="uploadConeval">
                         <i class="fas fa-save"></i> Guardar</span>
                            <span wire:loading wire:target="uploadConeval"> Guardando...</span>
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
