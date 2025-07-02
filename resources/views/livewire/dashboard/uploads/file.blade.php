<div class="py-2">
    <div class="w-full  flex flex-wrap bg-white rounded-lg shadow-lg space-x-2 p-2">
        <div class="w-full flex flex-wrap md:flex-nowrap space-y-2 md:space-x-4">
            <div class="w-full md:w-1/3 flex flex-wrap  items-center justify-center ">
                <label class="w-full flex flex-col px-6 py-4 text-center cursor-pointer  rounded-lg shadow hover:shadow-lg uppercase text-white bg-blue-500 hover:text-blue-500 hover:bg-white  hover:text-blue-500 border border-blue-500">
                    <i class="fas fa-cloud-upload-alt mx-auto text-xl "></i>
                    <span class="mt-2 text-base leading-normal">Upload File</span>
                    <!-- File Input -->
                    <input type="file" class="hidden" wire:model="file">
                    <!-- Progress Bar -->
                </label>
                @error('file')
                <div class="w-full text-red-500 font-semibold inline-block">{{ $message }}</div>
                @enderror
            </div>
            <div class="w-full md:w-2/3 ">
                <div class="w-full py-2">
                    <label for="" class="inline-block w-full font-semibold"> Nombre del Archivo:</label>
                    <div class="flex flex-nowrap space-x-2">
                        <div class="w-[89%]">
                            <input type="text" wire:model="name" placeholder="Ej. {{ $key }}-{{ date('Y') }}-V1" class="p-1 w-full shadow rounded border">
                        </div>
                        <div class="w-[1%] flex items-end">.</div>
                        <div class="w-[10%]">
                            <input type="text" class="p-1 w-full shadow rounded border bg-gray-100" wire:model="ext" disabled >
                        </div>
                    </div>
                    @error('name')
                    <div class="text-red-500 font-semibold">{{ $message }}</div>
                    @enderror
                </div>
                <div class="w-full py-2">
                    <label for="" class="inline-block w-full font-semibold">Fuente de Origen:</label>
                    <select wire:model="source_id" class="rounded-lg shadow border w-full p-2">
                        <option >--SELECT--</option>
                        @foreach($sources as $source)
                            <option value="{{ $source->id }}">{{ $source->name }}</option>
                        @endforeach
                    </select>
                    @error('source_id')
                    <div class="text-red-500 font-semibold">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="w-full flex">
            <div class="w-full md:w-1/2 flex items-center">
                @if (session()->has('success'))
                    <p class="w-full p-2 text-green-600 font-semibold rounded-full italic text-center uppercase text-xl">
                        {!!  session('success') !!}
                    </p>
                @endif
                @if (session()->has('error'))
                    <p class="w-full p-2 text-red-500 font-semibold rounded-full italic text-center uppercase text-xl ">
                        {!!  session('error') !!}
                    </p>
                @endif
            </div>
            <div class="w-full md:w-1/2 flex justify-end items-center">
                <button wire:click="upload" wire:loading.attr="disabled" wire:loading.class.remove="bg-green-600" wire:loading.class="bg-gray-200"
                        class=" bg-green-600 hover:bg-green-500 text-white py-2 px-4  rounded-lg shadow hover:shadow-lg  transition duration-300 ease-in-out ">
                                <span wire:loading.class="hidden" wire:target="upload">
                                 <i class="fas fa-save"></i> Guardar</span>
                    <span wire:loading wire:target="upload"> Guardando...</span>
                </button>
            </div>
        </div>
    </div>
</div>
