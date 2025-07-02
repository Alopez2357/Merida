<div class="h-full overflow-y-auto py-6">
    @section('title','Fuentes')
    <div class="container">
        <div id="accordionFlushExample">
            @foreach($sources as $k=>$source)
                <div
                        class="rounded-none border-b  bg-white">
                    <h2 class="mb-0" id="flush-heading{{ $k }}">
                        <button
                                class="group relative flex w-full items-center rounded-none border-0 bg-white py-4 px-5 text-left text-base text-neutral-800 transition [overflow-anchor:none] hover:z-[2] focus:z-[3] focus:outline-none dark:bg-neutral-800 dark:text-white [&:not([data-te-collapse-collapsed])]:bg-white [&:not([data-te-collapse-collapsed])]:text-primary [&:not([data-te-collapse-collapsed])]:[box-shadow:inset_0_-1px_0_rgba(229,231,235)] dark:[&:not([data-te-collapse-collapsed])]:bg-neutral-800 dark:[&:not([data-te-collapse-collapsed])]:text-primary-400 dark:[&:not([data-te-collapse-collapsed])]:[box-shadow:inset_0_-1px_0_rgba(75,85,99)]"
                                type="button"
                                data-te-collapse-init
                                data-te-collapse-collapsed
                                data-te-target="#flush-collapse{{ $k }}"
                                aria-expanded="false"
                                aria-controls="flush-collapse{{ $k }}">
                            {{ $source->name }}
                            <span
                                    class="ml-auto -mr-1 h-5 w-5 shrink-0 rotate-[-180deg] fill-[#336dec] transition-transform duration-200 ease-in-out group-[[data-te-collapse-collapsed]]:mr-0 group-[[data-te-collapse-collapsed]]:rotate-0 group-[[data-te-collapse-collapsed]]:fill-[#212529] motion-reduce:transition-none dark:fill-blue-300 dark:group-[[data-te-collapse-collapsed]]:fill-white">
          <svg
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke-width="1.5"
                  stroke="currentColor"
                  class="h-6 w-6">
            <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
          </svg>
        </span>
                        </button>
                    </h2>
                    <div
                            id="flush-collapse{{ $k }}"
                            class="!visible hidden"
                            data-te-collapse-item
                            aria-labelledby="flush-heading{{ $k }}"
                            data-te-parent="#accordionFlushExample">
                        <div class="accordion-body py-4 px-5 h-[50vh] overflow-y-auto shadow-inner">
                            <table class="table table-auto w-full shadow-md">
                                <thead class="bg-black text-white">
                                <tr>
                                    <th>#</th>
                                    <th>Nombre</th>
                                    <th>Fecha Carga</th>
                                    <th>Descargar Archivo</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($source->loads as $k=>$load)
                                    <tr class="{{ ($k%2)?'bg-white':'bg-gray-100' }}">
                                        <td class="text-center py-1">{{ $load->id }}</td>
                                        <td class="text-center py-1">{{ $load->name }}</td>
                                        <td class="text-center py-1">{{ $load->created_at }}</td>
                                        <td class="text-center py-1">
                                            <button wire:click="download({{$load->id}})" class="px-4 py-1 rounded-lg shadow text-white border border-blue-500 bg-blue-500 hover:bg-white hover:text-blue-500">
                                                <i class="fas fa-download"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
       {{--<div class="accordion" id="accordionExample">
            @foreach($sources as $k=>$source)
                <div class="accordion-item bg-white border border-gray-200">
                    <h2 class="accordion-header mb-0" id="heading{{ $k }}">
                        <button class="
                                    accordion-button
                                    collapsed
                                    relative
                                    flex
                                    items-center
                                    w-full
                                    py-4
                                    px-5
                                    text-base text-gray-800 text-left
                                    bg-white
                                    border-0
                                    rounded-none
                                    transition
                                    focus:outline-none"
                                type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $k }}" aria-expanded="false"
                                aria-controls="collapse{{ $k }}">
                          {{ $source->name }}
                        </button>
                    </h2>
                    <div id="collapse{{ $k }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $k }}"
                     data-bs-parent="#accordionExample">
                    <div class="accordion-body py-4 px-5 h-[50vh] overflow-y-auto">
                        <table class="table table-auto w-full">
                            <thead class="bg-black text-white">
                                <tr>
                                    <th>#</th>
                                    <th>Nombre</th>
                                    <th>Fecha Carga</th>
                                    <th>Descargar Archivo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($source->loads as $k=>$load)
                                    <tr class="{{ ($k%2)?'bg-white':'bg-gray-100' }}">
                                        <td class="text-center py-1">{{ $load->id }}</td>
                                        <td class="text-center py-1">{{ $load->name }}</td>
                                        <td class="text-center py-1">{{ $load->created_at }}</td>
                                        <td class="text-center py-1">
                                            <button wire:click="download({{$load->id}})" class="px-4 py-1 rounded-lg shadow text-white border border-blue-500 bg-blue-500 hover:bg-white hover:text-blue-500">
                                                <i class="fas fa-download"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endforeach
        </div>--}}
    </div>
    {{-- In work, do what you enjoy. --}}
</div>
