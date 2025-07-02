<aside
        class="fixed inset-y-0 z-20 flex-shrink-0 w-64 mt-16 overflow-y-auto bg-indigo-700  lg:hidden"
        x-show="isSideMenuOpen"
        x-transition:enter="transition ease-in-out duration-150"
        x-transition:enter-start="opacity-0 transform -translate-x-20"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in-out duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0 transform -translate-x-20"
        @click.away="closeSideMenu"
        @keydown.escape="closeSideMenu"s
>
    <div class="py-4 text-gray-500 ">
        <a class="ml-6 text-lg font-bold text-gray-800 "
                href="{{ route('dashboard.main') }}">
            <img src="{{ asset('img/logo-white.png') }}" class="w-1/2 h-auto mx-auto" alt="">
        </a>
        <ul class="mt-6">
            <li class="relative px-6 py-3">
              <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
                <a class="inline-flex items-center w-full text-sm font-semibold text-white transition-colors duration-150 hover:text-gray-300 dark:hover:text-gray-200 dark:text-gray-100"
                        href="{{ route('dashboard.main') }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span class="ml-4">Dashboard</span>
                </a>
            </li>
        </ul>
        <ul>
            @foreach($items as $k=>$item)
                <li class="relative px-6 py-3">
                <button
                        class="inline-flex items-center justify-between w-full text-sm font-semibold transition-colors duration-150 text-white hover:text-gray-300 dark:hover:text-gray-200"
                        @click="toggle{{$k}}Menu"
                        aria-haspopup="true"
                >
                <span class="inline-flex items-center">
                  {!! $item['icon'] !!}
                  <span class="ml-4">{{ $item['name'] }}</span>
                </span>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <template x-if="is{{$k}}MenuOpen">
                    <ul
                            x-transition:enter="transition-all ease-in-out duration-300"
                            x-transition:enter-start="opacity-25 max-h-0"
                            x-transition:enter-end="opacity-100 max-h-xl"
                            x-transition:leave="transition-all ease-in-out duration-300"
                            x-transition:leave-start="opacity-100 max-h-xl"
                            x-transition:leave-end="opacity-0 max-h-0"
                            class="p-2 mt-2 space-y-2 overflow-hidden text-sm font-medium text-white rounded-md shadow-inner bg-indigo-500 overflow-y-auto max-h-[30vh]"
                            aria-label="submenu">
                        @foreach($item['subs'] as $r=>$sub)
                            <li class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200">
                                <a class="w-full" href="{{ route($sub['route']) }}">{{$sub['name']}}</a>
                            </li>
                        @endforeach
                    </ul>
                </template>
            </li>
            @endforeach
        </ul>
        @role('SuperAdmin|Admin|Cinco Consulting|Pasantes')
            <div class="px-6 my-6 w-full">
                <button class="w-full flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    Fuentes
                    <span class="ml-2" aria-hidden="true"><i class="fas fa-book-reader"></i></span>
                </button>
            </div>

            <ul class="px-4 py-2 text-white">
                <li class="w-full relative py-2 text-sm font-semibold ">
                    <a href="#" class="w-full border-white border-b inline-block  py-2"> <i class="fas fa-upload"></i> Carga de Documentos</a>
                </li>
                @foreach($loads as $k=>$item)
                    <li class="relative  py-2">
                        <button  class="inline-flex items-center justify-between w-full text-sm  transition-colors duration-150 hover:text-gray-300"
                                    @click="toggle{{ $k }}Menu"
                                    aria-haspopup="true">
                        <span class="inline-flex items-center">
                            {{  $item['name'] }}
                        </span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <template x-if="is{{$k}}MenuOpen">
                            <ul
                                    x-transition:enter="transition-all ease-in-out duration-300"
                                    x-transition:enter-start="opacity-25 max-h-0"
                                    x-transition:enter-end="opacity-100 max-h-xl"
                                    x-transition:leave="transition-all ease-in-out duration-300"
                                    x-transition:leave-start="opacity-100 max-h-xl"
                                    x-transition:leave-end="opacity-0 max-h-0"
                                    class="p-2  space-y-2 overflow-hidden text-xs font-medium text-white rounded bg-indigo-500 overflow-y-auto max-h-[30vh]"
                                    aria-label="submenu">
                                @foreach($item['subs'] as $r=>$sub)
                                    <li class="p-1 transition-colors duration-150
                                        @if(request()->routeIS($sub['route'])) bg-indigo-600 @endif
                                            hover:bg-indigo-600 rounded-lg">
                                        <a class="w-full" href="{{ route($sub['route']) }}">{{ $sub['name'] }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </template>
                    </li>
                @endforeach


            </ul>
        @endrole

        @role('SuperAdmin|Admin|Cinco Consulting')
        <div class="px-6 my-6">
            <a href="{{ route('dashboard.users.list') }}"
               class="flex items-center justify-between w-full px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                Usuarios
                <span class="ml-2" aria-hidden="true"><i class="fas fa-users"></i></span>
            </a>
        </div>
        @endrole
    </div>
</aside>