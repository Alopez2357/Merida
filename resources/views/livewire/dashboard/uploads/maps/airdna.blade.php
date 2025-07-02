<section class="py-2 overflow-y-auto">
    @section('title','Airdna')
    <div class="container">
        <div class="w-full flex  flex-wrap" wire:ignore>
            <div class="w-full rounded-lg bg-white shadow-lg p-2">
                <h2 class="w-full text-start text-xl font-semibold">AirDNA</h2>
                <div class="w-full flex">
                    <div class="w-full md:w-1/3 flex flex-wrap p-4  items-center justify-center">
                        <label class="w-full flex flex-col px-6 py-4 text-center cursor-pointer  rounded-lg shadow hover:shadow-lg uppercase text-white bg-blue-500 hover:text-blue-500 hover:bg-white  hover:text-blue-500 border border-blue-500">
                            <i class="fas fa-cloud-upload-alt mx-auto text-xl"></i>
                            <span class="mt-2 text-base leading-normal">Upload File</span>
                            <!-- File Input -->
                            <input type="file" onchange="loadName(this)" class="hidden" name="file" id="file" >
                        </label>
                    </div>
                    <div class="w-full md:w-2/3 p-2">
                        <p class="inline-block w-full py-2"><label for="" class="inline-block w-full font-semibold"> Nombre del Archivo:</label></p>
                        <p class="inline-block w-full py-2"><input type="text" name="name" id="name" placeholder="Ej. Airdna-{{ date('Y') }}" class="p-1 w-full shadow rounded border"></p>
                        <p class="inline-block w-full py-2"><label for="" class="inline-block w-full font-semibold">Fuente de Origen:</label></p>
                        <p class="inline-block w-full py-2">
                            <select name="source_id" id="source_id" class="rounded-lg shadow border w-full p-2">
                                <option >--SELECT--</option>
                                @foreach($sources as $source)
                                    <option value="{{ $source->id }}">{{ $source->name }}</option>
                                @endforeach
                            </select>
                        </p>
                    </div>
                </div>
                <div class="w-full flex">
                    <div class="w-full md:w-1/2 flex items-center ">
                        <ul class="list-inside list-disc text-red-800 font-semibold" id="messages">
                        </ul>
                    </div>
                    <div class="w-1/2 flex items-center ">
                        <p class="inline-block w-full py-2 flex justify-end">
                            <button id="submit" onclick="submit(this)"
                                    class=" bg-green-600 hover:bg-green-500 text-white py-2 px-4  rounded-lg shadow hover:shadow-lg  transition duration-300 ease-in-out ">
                                <i class="fas fa-save"></i> <span id="text-submit">Guardar</span>
                            </button>
                        </p>
                    </div>
                </div>
            </div>

            <div class="w-full  flex justify-center px-6">
                <div class="w-full flex justify-center items-center justify-center py-5">
                    @if (session()->has('success'))
                        <p class="w-full p-4 text-green-600 font-semibold rounded-full italic text-center uppercase text-xl">
                            {{ session('success') }}
                        </p>
                    @endif
                </div>
            </div>
        </div>

        @livewire('dashboard.uploads.history', ['type_id' => $type_id])
    </div>
    <script>
        let body={};
        let formData={};
        let name, file, source_id;
        let lists='';
        let messages=[];

        function submit(element){
            lists='';
            messages=[];
            if(document.getElementById('messages'))
                document.getElementById('messages').innerHTML = '';

            element.disabled=true;
            if(document.getElementById("text-submit"))
                document.getElementById("text-submit").innerHTML='Guardando...';

            getForm();
            sendAirdna();

        }

        function loadName(file)
        {
            name=file.files[0].name;
            if(document.getElementById('name')){
                document.getElementById('name').value=name;
            }
        }

        function getForm()
        {
            formData = new FormData();

            if(document.getElementById('name'))
                formData.append("name", document.getElementById('name').value);

            if(document.getElementById('source_id'))
                formData.append("source_id", document.getElementById('source_id').value);

            if(document.getElementById('file'))
                formData.append("file",document.getElementById('file').files[0]);

        }

        async function sendAirdna() {
            let codestatus=200;
            try {
                const URL = host + "/api/v1/upload/maps/airdna?api_key=" + Api_key;
                const response = await fetch(URL, {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();
                codestatus=response.status;
                if (response.status === 200) {
                    messages.push(result.message);
                    body = result.body;
                    messages.push('Successful Filled');
                    messages.push(document.getElementById('name').value);

                } else {
                    for (let x in result) {
                        Object.values(result[x]).forEach((value, index) => {
                            messages.push(value);
                        });
                    }
                }

            }catch(error){
                console.log(error);
                messages.push('Successful Filled');
                messages.push(document.getElementById('name').value);
            }

            showMessages();

            if(codestatus!==400) {
                setTimeout(function () {
                    document.location.reload();
                }, 5000)
            }

        }

        function showMessages()
        {
            messages=Array.from(new Set(messages));

            messages.forEach((value,index)=>{
                lists+='<li>'+value+'</li>';
            });
            if(document.getElementById('messages')) {
                document.getElementById('messages').innerHTML = lists;
            }

            if(document.getElementById("text-submit"))
                document.getElementById("text-submit").innerHTML='Guardar';

            if(document.getElementById("submit"))
                document.getElementById("submit").disabled=false;
        }

    </script>

</section>
