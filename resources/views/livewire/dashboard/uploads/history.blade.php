<div class="flex flex-wrap py-2">
    {{--<h1 class="text-xl dark:text-white font-bold uppercase text-center">Historial de Cargas</h1>--}}
    <div class="w-full bg-white">
        <table id="" class="table w-full table-auto two-axis">
            <thead class="text-white bg-black text-center uppercase">
                <tr>
                    @foreach($columns as $c)
                        <th data-label="sortBy('{{$c}}')" class="px-6 py-2 text-xs hover:cursor-pointer" wire:click="sortBy('{{$c}}')">
                            <span>{{$c}}
                                @if($sortBy == $c)
                                    {!!  ($sortDirection =='asc')? '&uarr;': '&darr;' !!}
                                @endif
                            </span>
                        </th>
                    @endforeach
                    <th data-label="Download" class="p-2">Download</th>
                    <th data-label="Type" class="p-2">Type</th>
                    <th data-label="Source" class="p-2">Source</th>
                    <th data-label="User" class="p-2">User</th>
                    <th data-label="Created" class="p-2">Created</th>
                    @role('SuperAdmin')
                        
                        <th data-label="Status" class="p-2">Status</th>
                        <th data-label="Delete" class="p-2">Delete</th>
                        
                    @endrole
                </tr>
            </thead>
            <tbody class="text-center">
                @foreach($loads as $k=>$load)
                    <tr class="">
                    <td data-label="Id" class="p-2">{{ $load->id }}</td>
                    <td data-label="Name" class="p-2 name_file">{{ $load->file }}</td>
                    <td data-label="Dowload" class="p-2">
                        <button wire:click="download({{$load->id}})"
                                class="px-4 py-1 rounded-lg shadow text-white border border-blue-500 bg-blue-500 hover:bg-white hover:text-blue-500">
                            <i class="fas fa-download"></i>
                        </button>
                    </td>
                    <td data-label="Type" class="p-2">{{ $load->type->name }}</td>
                    <td class="p-2" data-label="Source">
                        <select onchange="changeSource({{$load->id}},this)" class="rounded-lg">
                            @foreach($sources as $source)
                                <option {{ ($load->source->id==$source->id)?'selected':'' }} value="{{ $source->id }}">{{ $source->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td class="p-2" data-label="User">{{ $load->user->name }}</td>
                    <td class="p-2" data-label="Created">{{ $load->created_at }}</td>
                    @role('SuperAdmin')
                        @if(is_null($load->status))
                        <td class="p-2" data-label="Status">
                            <span class="px-4 py-1 rounded-lg shadow text-white border border-green-500 bg-green-500">
                                Correcto
                            </span>
                        </td>
                        @else
                        <td class="p-2" data-label="Status">
                            <span class="px-4 py-1 rounded-lg shadow text-white border border-red-500 bg-red-500">
                                {{$load->status}}
                            </span>
                        </td>
                        @endif
                        <td class="p-2" data-label="Delete">
                                <button wire:click="destroy({{ $load->id }})"
                                        class="px-4 py-1 rounded-lg shadow text-white border border-red-500 bg-red-500 hover:bg-white hover:text-red-500">
                                    <i class="fas fa-trash"></i>
                                </button>
                        </td>
                    @endrole
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="w-full py-2 flex justify-end">
        {{ $loads->links() }}
    </div>
    <script>

        async function changeSource(load_id, obj)
        {
            let body={load_id:load_id,source_id:obj.value};
            const URL =host+"/api/v1/upload/history/source?api_key="+Api_key;
            const response = await fetch(URL,{
                method:'POST',
                body:JSON.stringify(body),
                headers: {"Content-type": "application/json;charset=UTF-8"}
            });

            const result = await response.json();
            if(response.status===200) {
                console.log("Success");
            }else{
                alert("Error al Actualizar");
            }
        }
    </script>
</div>